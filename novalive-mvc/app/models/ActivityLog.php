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
     * Mengambil daftar log aktivitas dengan paginasi
     */
    public function paginate(int $page = 1, int $perPage = 15): array {
        $page = max(1, $page);
        $offset = ($page - 1) * $perPage;

        $countSql = "SELECT COUNT(*) FROM activity_logs";
        $total = (int)$this->db->query($countSql)->fetchColumn();

        $sql = "
            SELECT a.*, u.full_name AS user_name, u.role AS user_role, u.email AS user_email
            FROM activity_logs a
            LEFT JOIN users u ON a.user_id = u.id
            ORDER BY a.id DESC
            LIMIT {$perPage} OFFSET {$offset}
        ";
        $data = $this->db->query($sql)->fetchAll();

        return [
            'data'        => $data,
            'total'       => $total,
            'perPage'     => $perPage,
            'currentPage' => $page,
            'totalPages'  => (int)ceil($total / $perPage)
        ];
    }
}
