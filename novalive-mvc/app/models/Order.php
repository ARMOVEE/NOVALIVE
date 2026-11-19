<?php
/**
 * Model Order
 * Mengelola transaksi pesanan tiket, status verifikasi, dan statistik pendapatan
 */

class Order extends Model {
    protected string $table = 'orders';

    /**
     * Mengambil detail satu pesanan beserta informasi event terkait
     */
    public function find($id, bool $includeDeleted = false) {
        $sql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code, e.event_date, e.venue, e.price AS unit_price
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE o.id = :id
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Paginasi pesanan tiket dengan filter status dan kata kunci pencarian
     */
    public function paginate(array $filters = [], int $page = 1, int $perPage = 10): array {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = ["1=1"];
        $params = [];

        if (!empty($filters['status']) && in_array($filters['status'], ['Terkirim', 'Menunggu', 'Selesai', 'Dibatalkan'], true)) {
            $conditions[] = "o.status = :status";
            $params[':status'] = $filters['status'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = "(o.invoice LIKE :search OR o.buyer_name LIKE :search OR o.buyer_email LIKE :search OR e.title LIKE :search)";
            $params[':search'] = '%' . trim($filters['search']) . '%';
        }

        $whereClause = implode(" AND ", $conditions);

        // Hitung total data
        $countSql = "
            SELECT COUNT(o.id)
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        // Ambil data halaman aktif
        $dataSql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code, e.event_date, e.venue
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
            ORDER BY o.id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $dataStmt = $this->db->prepare($dataSql);
        $dataStmt->execute($params);
        $data = $dataStmt->fetchAll();

        return [
            'data'        => $data,
            'total'       => $total,
            'perPage'     => $perPage,
            'currentPage' => $page,
            'totalPages'  => (int)ceil($total / $perPage)
        ];
    }

    /**
     * Mengambil daftar pesanan tiket terbaru untuk dashboard
     */
    public function recent(int $limit = 5): array {
        $sql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code
            FROM orders o
            JOIN events e ON o.event_id = e.id
            ORDER BY o.id DESC
            LIMIT :limit
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Menghitung rincian jumlah pesanan berdasarkan status
     */
    public function countsByStatus(): array {
        $sql = "
            SELECT status, COUNT(*) AS count
            FROM orders
            GROUP BY status
        ";
        $stmt = $this->db->query($sql);
        $rows = $stmt->fetchAll();

        $counts = [
            'Semua'      => 0,
            'Terkirim'   => 0,
            'Menunggu'   => 0,
            'Selesai'    => 0,
            'Dibatalkan' => 0
        ];

        foreach ($rows as $row) {
            $st = $row['status'];
            $c = (int)$row['count'];
            $counts[$st] = $c;
            $counts['Semua'] += $c;
        }

        return $counts;
    }

    /**
     * Menghitung total omset / pendapatan dari pesanan yang sukses ('Terkirim' & 'Selesai')
     */
    public function totalRevenue(): float {
        $sql = "
            SELECT COALESCE(SUM(total_price), 0)
            FROM orders
            WHERE status IN ('Terkirim', 'Selesai')
        ";
        return (float)$this->db->query($sql)->fetchColumn();
    }

    /**
     * Memperbarui status pesanan tiket
     */
    public function updateStatus(int $id, string $status): bool {
        $valid = ['Terkirim', 'Menunggu', 'Selesai', 'Dibatalkan'];
        if (!in_array($status, $valid, true)) {
            return false;
        }

        $stmt = $this->db->prepare("
            UPDATE orders
            SET status = :status
            WHERE id = :id
        ");
        return $stmt->execute([
            ':id'     => $id,
            ':status' => $status
        ]);
    }

    /**
     * Cari pesanan berdasarkan kode tiket (NVL-...)
     */
    public function findByTicketCode(string $code): ?array {
        $sql = "
            SELECT o.*, 
                   e.id AS event_id, e.title AS event_title, e.code AS event_code, 
                   e.event_date, e.venue, e.price AS unit_price
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE o.ticket_code = :code
            LIMIT 1
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':code' => trim($code)]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Tandai pesanan sudah check-in
     */
    public function markCheckedIn(int $id): bool {
        $stmt = $this->db->prepare("UPDATE orders SET checked_in_at = NOW() WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Ambil riwayat check-in hari ini
     */
    public function getTodayCheckins(int $eventId = 0): array {
        $sql = "
            SELECT o.*, 
                   e.title AS event_title, e.event_date, e.venue
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE o.checked_in_at IS NOT NULL
              AND DATE(o.checked_in_at) = CURDATE()
        ";
        $params = [];
        if ($eventId > 0) {
            $sql .= " AND o.event_id = :eventId";
            $params[':eventId'] = $eventId;
        }
        $sql .= " ORDER BY o.checked_in_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Statistik check-in tiket per event atau keseluruhan
     */
    public function getCheckinStats(int $eventId = 0): array {
        $sql = "
            SELECT 
                COALESCE(SUM(CASE WHEN checked_in_at IS NOT NULL THEN qty ELSE 0 END), 0) AS checked_in,
                COALESCE(SUM(qty), 0) AS total_tickets
            FROM orders
            WHERE status IN ('Terkirim', 'Selesai')
        ";
        $params = [];
        if ($eventId > 0) {
            $sql .= " AND event_id = :eventId";
            $params[':eventId'] = $eventId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $row = $stmt->fetch() ?: ['checked_in' => 0, 'total_tickets' => 0];
        $checkedIn = (int)$row['checked_in'];
        $total = (int)$row['total_tickets'];
        $percentage = $total > 0 ? round(($checkedIn / $total) * 100, 1) : 0;
        return [
            'checked_in'    => $checkedIn,
            'total_tickets' => $total,
            'percentage'    => $percentage
        ];
    }

    /**
     * Daftar pesanan yang memiliki e-tiket (status Terkirim / Selesai)
     */
    public function tickets(array $filters = [], int $page = 1, int $perPage = 15): array {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));
        $offset = ($page - 1) * $perPage;

        $conditions = [
            "o.ticket_code IS NOT NULL",
            "o.ticket_code != ''",
            "o.status IN ('Terkirim', 'Selesai')"
        ];
        $params = [];

        if (!empty($filters['search'])) {
            $conditions[] = "(o.invoice LIKE :search OR o.buyer_name LIKE :search OR o.buyer_email LIKE :search OR o.ticket_code LIKE :search OR e.title LIKE :search)";
            $params[':search'] = '%' . trim($filters['search']) . '%';
        }

        if (!empty($filters['event_id'])) {
            $conditions[] = "o.event_id = :event_id";
            $params[':event_id'] = (int)$filters['event_id'];
        }

        $whereClause = implode(" AND ", $conditions);

        $countSql = "
            SELECT COUNT(o.id)
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
        ";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $dataSql = "
            SELECT o.*, 
                   e.title AS event_title, e.code AS event_code, e.event_date, e.venue
            FROM orders o
            JOIN events e ON o.event_id = e.id
            WHERE {$whereClause}
            ORDER BY o.id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $dataStmt = $this->db->prepare($dataSql);
        $dataStmt->execute($params);
        $data = $dataStmt->fetchAll();

        return [
            'data'        => $data,
            'total'       => $total,
            'perPage'     => $perPage,
            'currentPage' => $page,
            'totalPages'  => (int)ceil($total / $perPage)
        ];
    }
}
