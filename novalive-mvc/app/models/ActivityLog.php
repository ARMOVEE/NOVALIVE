<?php
/**
 * Model ActivityLog
 * Mencatat jejak audit trail seluruh aktivitas administratif ke tabel activity_logs
 */

class ActivityLog extends Model {
    protected string $table = 'activity_logs';

    /**
     * Mencatat aksi pengguna ke log audit trail
     * Terbungkus try/catch agar kegagalan log tidak pernah menggagalkan transaksi bisnis
     */
    public function record(string $action, ?string $entity = null, ?int $entityId = null, ?string $description = null): bool {
        try {
            $userId = $_SESSION['user']['id'] ?? null;
            $ip = Security::clientIp();

            $stmt = $this->db->prepare("
                INSERT INTO activity_logs (user_id, action, entity, entity_id, description, ip_address)
                VALUES (:user_id, :action, :entity, :entity_id, :description, :ip)
            ");

            return $stmt->execute([
                ':user_id'     => $userId,
                ':action'      => substr($action, 0, 50),
                ':entity'      => $entity ? substr($entity, 0, 50) : null,
                ':entity_id'   => $entityId,
                ':description' => $description ? substr($description, 0, 255) : null,
                ':ip'          => $ip
            ]);
        } catch (\Throwable $e) {
            Logger::error('Gagal menulis activity log: ' . $e->getMessage(), [
                'action' => $action,
                'entity' => $entity
            ]);
            return false;
        }
    }

    /**
     * Mengambil daftar log aktivitas dengan paginasi dan filter
     */
    public function paginate(int $page = 1, int $perPage = 20, array $filters = []): array {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $conditions = ["1=1"];
        $params = [];

        if (!empty($filters['action'])) {
            $conditions[] = "a.action = :action";
            $params[':action'] = trim($filters['action']);
        }

        if (!empty($filters['user_id'])) {
            $conditions[] = "a.user_id = :uid";
            $params[':uid'] = (int)$filters['user_id'];
        }

        if (!empty($filters['start_date'])) {
            $conditions[] = "DATE(a.created_at) >= :start_date";
            $params[':start_date'] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $conditions[] = "DATE(a.created_at) <= :end_date";
            $params[':end_date'] = $filters['end_date'];
        }

        if (!empty($filters['search'])) {
            $conditions[] = "(a.action LIKE :s1 OR a.entity LIKE :s2 OR a.description LIKE :s3 OR a.ip_address LIKE :s4 OR u.full_name LIKE :s5)";
            $sVal = '%' . trim($filters['search']) . '%';
            $params[':s1'] = $sVal;
            $params[':s2'] = $sVal;
            $params[':s3'] = $sVal;
            $params[':s4'] = $sVal;
            $params[':s5'] = $sVal;
        }

        $whereClause = implode(" AND ", $conditions);

        $countSql = "SELECT COUNT(*) FROM activity_logs a LEFT JOIN users u ON a.user_id = u.id WHERE {$whereClause}";
        $countStmt = $this->db->prepare($countSql);
        $countStmt->execute($params);
        $total = (int)$countStmt->fetchColumn();

        $sql = "
            SELECT a.*, u.full_name AS user_name, u.role AS user_role, u.email AS user_email
            FROM activity_logs a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE {$whereClause}
            ORDER BY a.id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $data = $stmt->fetchAll();

        return [
            'data'        => $data,
            'total'       => $total,
            'perPage'     => $perPage,
            'currentPage' => $page,
            'totalPages'  => (int)ceil($total / $perPage)
        ];
    }

    /**
     * Ambil daftar aksi unik untuk opsi dropdown filter
     */
    public function getDistinctActions(): array {
        return $this->db->query("SELECT DISTINCT action FROM activity_logs ORDER BY action ASC")->fetchAll(PDO::FETCH_COLUMN);
    }
}
