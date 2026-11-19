<?php
/**
 * Model Setting
 * Mengelola konfigurasi umum, profil penyelenggara, dan kontak sistem
 */

class Setting extends Model {
    protected string $table = 'settings';
    protected string $primaryKey = 'setting_key';

    /**
     * Mengambil nilai pengaturan berdasarkan kunci
     */
    public function get(string $key, ?string $default = null): ?string {
        $stmt = $this->db->prepare("SELECT setting_value FROM {$this->table} WHERE setting_key = :k LIMIT 1");
        $stmt->execute([':k' => $key]);
        $val = $stmt->fetchColumn();
        return ($val !== false && $val !== null) ? (string)$val : $default;
    }

    /**
     * Menyimpan atau memperbarui satu nilai pengaturan
     */
    public function set(string $key, ?string $value): bool {
        $stmt = $this->db->prepare("
            INSERT INTO {$this->table} (setting_key, setting_value, updated_at)
            VALUES (:k, :v, NOW())
            ON DUPLICATE KEY UPDATE setting_value = :v2, updated_at = NOW()
        ");
        return $stmt->execute([
            ':k'  => $key,
            ':v'  => $value,
            ':v2' => $value
        ]);
    }

    /**
     * Mengambil seluruh pengaturan dalam bentuk associative array (key => value)
     */
    public function allSettings(): array {
        $rows = $this->db->query("SELECT setting_key, setting_value FROM {$this->table}")->fetchAll();
        $settings = [];
        foreach ($rows as $r) {
            $settings[$r['setting_key']] = $r['setting_value'];
        }
        return $settings;
    }

    /**
     * Menyimpan banyak pengaturan sekaligus dari array asosiatif
     */
    public function updateMultiple(array $data): bool {
        foreach ($data as $key => $val) {
            $this->set($key, (string)$val);
        }
        return true;
    }
}
