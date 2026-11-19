<?php
/**
 * Model Notification (Pemberitahuan Sistem & Pembeli)
 * NOVA LIVE MVC — Tahap 3
 */

class Notification extends Model {
    protected string $table = 'notifications';

    /**
     * Ambil notifikasi terbaru untuk popover topbar
     */
    public function latest(int $limit = 5): array {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT :lim");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Hitung jumlah notifikasi yang belum dibaca
     */
    public function unreadCount(?int $userId = null): int {
        if ($userId !== null) {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE is_read = 0 AND (user_id = :uid OR user_id IS NULL)");
            $stmt->execute([':uid' => $userId]);
        } else {
            $stmt = $this->db->query("SELECT COUNT(*) FROM {$this->table} WHERE is_read = 0");
        }
        return (int)$stmt->fetchColumn();
    }

    /**
     * Tandai satu notifikasi sebagai dibaca
     */
    public function markAsRead(int $id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET is_read = 1 WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Tandai semua notifikasi sebagai dibaca
     */
    public function markAllAsRead(?int $userId = null): bool {
        if ($userId !== null) {
            $stmt = $this->db->prepare("UPDATE {$this->table} SET is_read = 1 WHERE (user_id = :uid OR user_id IS NULL)");
            return $stmt->execute([':uid' => $userId]);
        } else {
            return (bool)$this->db->exec("UPDATE {$this->table} SET is_read = 1");
        }
    }

    /**
     * Buat notifikasi baru
     */
    public function createNotification(string $type, string $title, string $message, ?string $link = null, ?int $userId = null): int {
        return $this->create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Otomatis cek event yang kuotanya sisa <= 20% dan buat notifikasi jika belum dibuat hari ini
     */
    public function checkAndCreateStockAlerts(): int {
        $sql = "SELECT id, title, quota, sold FROM events 
                WHERE deleted_at IS NULL AND quota > 0 AND (sold / quota) >= 0.8";
        $events = $this->db->query($sql)->fetchAll();
        $created = 0;

        foreach ($events as $ev) {
            $rem = max(0, (int)$ev['quota'] - (int)$ev['sold']);
            $pct = round(((int)$ev['sold'] / (int)$ev['quota']) * 100);

            // Cek apakah sudah ada notifikasi stok untuk event ini dalam 24 jam terakhir
            $chk = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} 
                                      WHERE type = 'stock' AND link LIKE :link 
                                      AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
            $chk->execute([':link' => "%events/stock%"]);
            if ((int)$chk->fetchColumn() === 0) {
                $this->createNotification(
                    'stock',
                    'Peringatan Stok Tiket Menipis',
                    "Event '{$ev['title']}' telah terisi {$pct}%. Sisa kuota hanya {$rem} tiket lagi.",
                    '/dashboard/events/stock'
                );
                $created++;
            }
        }

        return $created;
    }
}
