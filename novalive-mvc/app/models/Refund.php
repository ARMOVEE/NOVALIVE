<?php
/**
 * Model Refund
 * Mengelola permohonan pengembalian dana tiket (Refund)
 */

class Refund extends Model {
    protected string $table = 'refunds';

    /**
     * Mengambil semua refund standar
     */
    public function all(bool $includeDeleted = false): array {
        return $this->allFiltered([]);
    }

    /**
     * Mengambil daftar refund beserta data order dan event dengan filter
     */
    public function allFiltered(array $filters = []): array {
        $sql = "
            SELECT r.*,
                   o.invoice, o.buyer_name, o.buyer_email, o.qty, o.total_price AS order_total,
                   o.status AS order_status, o.ticket_code,
                   e.id AS event_id, e.title AS event_title, e.event_date, e.venue,
                   u.full_name AS processor_name
            FROM refunds r
            JOIN orders o ON r.order_id = o.id
            JOIN events e ON o.event_id = e.id
            LEFT JOIN users u ON r.processed_by = u.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['status']) && in_array($filters['status'], ['Diajukan', 'Disetujui', 'Ditolak'], true)) {
            $sql .= " AND r.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (o.invoice LIKE :search OR o.buyer_name LIKE :search OR o.buyer_email LIKE :search OR e.title LIKE :search OR r.reason LIKE :search)";
            $params[':search'] = '%' . trim($filters['search']) . '%';
        }

        $sql .= " ORDER BY r.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil detail satu permohonan refund
     */
    public function find($id, bool $includeDeleted = false): ?array {
        $sql = "
            SELECT r.*,
                   o.invoice, o.buyer_name, o.buyer_email, o.qty, o.total_price AS order_total,
                   o.status AS order_status, o.ticket_code, o.checked_in_at,
                   e.id AS event_id, e.title AS event_title, e.sold AS event_sold,
                   u.full_name AS processor_name
            FROM refunds r
            JOIN orders o ON r.order_id = o.id
            JOIN events e ON o.event_id = e.id
            LEFT JOIN users u ON r.processed_by = u.id
            WHERE r.id = :id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => (int)$id]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Cek apakah order sudah pernah diajukan refund
     */
    public function findByOrderId(int $orderId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM refunds WHERE order_id = :order_id LIMIT 1");
        $stmt->execute([':order_id' => $orderId]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Tambah pengajuan refund baru
     */
    public function createRefund(int $orderId, string $reason, float $amount): int {
        $stmt = $this->db->prepare("
            INSERT INTO refunds (order_id, reason, amount, status, created_at)
            VALUES (:order_id, :reason, :amount, 'Diajukan', NOW())
        ");
        $stmt->execute([
            ':order_id' => $orderId,
            ':reason'   => $reason,
            ':amount'   => $amount
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Setujui refund:
     * - status refund = 'Disetujui'
     * - orders.status = 'Dibatalkan'
     * - events.sold dikurangi qty order (tidak boleh di bawah 0)
     */
    public function approve(int $id, int $userId): bool {
        $refund = $this->find($id);
        if (!$refund || $refund['status'] !== 'Diajukan') {
            return false;
        }

        try {
            $this->db->beginTransaction();

            // 1. Update status refund
            $stmt1 = $this->db->prepare("
                UPDATE refunds 
                SET status = 'Disetujui', processed_by = :user_id, processed_at = NOW() 
                WHERE id = :id
            ");
            $stmt1->execute([
                ':user_id' => $userId,
                ':id'      => $id
            ]);

            // 2. Update status order menjadi Dibatalkan
            $stmt2 = $this->db->prepare("
                UPDATE orders 
                SET status = 'Dibatalkan' 
                WHERE id = :order_id
            ");
            $stmt2->execute([':order_id' => $refund['order_id']]);

            // 3. Kurangi kuota sold event
            $qty = (int)$refund['qty'];
            $stmt3 = $this->db->prepare("
                UPDATE events 
                SET sold = GREATEST(0, sold - :qty) 
                WHERE id = :event_id
            ");
            $stmt3->execute([
                ':qty'      => $qty,
                ':event_id' => $refund['event_id']
            ]);

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    /**
     * Tolak refund:
     * - status refund = 'Ditolak'
     * - alasan penolakan bisa ditambahkan ke reason
     */
    public function reject(int $id, int $userId, string $rejectReason = ''): bool {
        $refund = $this->find($id);
        if (!$refund || $refund['status'] !== 'Diajukan') {
            return false;
        }

        $reason = $refund['reason'];
        if (!empty($rejectReason)) {
            $reason .= ' (Ditolak: ' . $rejectReason . ')';
        }

        $stmt = $this->db->prepare("
            UPDATE refunds 
            SET status = 'Ditolak', reason = :reason, processed_by = :user_id, processed_at = NOW() 
            WHERE id = :id
        ");
        return $stmt->execute([
            ':reason'  => $reason,
            ':user_id' => $userId,
            ':id'      => $id
        ]);
    }

    /**
     * Hitung refund dengan status Diajukan untuk badge menu
     */
    public function countPending(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM refunds WHERE status = 'Diajukan'")->fetchColumn();
    }

    /**
     * Hitung total per status
     */
    public function countsByStatus(): array {
        $rows = $this->db->query("SELECT status, COUNT(*) AS count FROM refunds GROUP BY status")->fetchAll();
        $counts = ['Semua' => 0, 'Diajukan' => 0, 'Disetujui' => 0, 'Ditolak' => 0];
        foreach ($rows as $r) {
            $st = $r['status'];
            $c = (int)$r['count'];
            $counts[$st] = $c;
            $counts['Semua'] += $c;
        }
        return $counts;
    }
}
