<?php
/**
 * Model City
 * Mengelola data master kota, ikon landmark, dan resolusi alias pencarian (contoh: Denpasar <-> Bali)
 */

class City extends Model {
    protected string $table = 'cities';

    /**
     * Mengambil seluruh data kota beserta jumlah event aktif per kota
     */
    public function allWithCount(): array {
        $sql = "
            SELECT ci.*, COUNT(e.id) AS event_count
            FROM cities ci
            LEFT JOIN events e ON e.city_id = ci.id AND e.deleted_at IS NULL
            GROUP BY ci.id
            ORDER BY ci.id ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Override all() untuk mengembalikan daftar kota dengan jumlah event
     */
    public function all(bool $includeDeleted = false): array {
        return $this->allWithCount();
    }

    /**
     * Mencari kota berdasarkan slug (contoh: 'jakarta', 'denpasar')
     */
    public function findBySlug(string $slug) {
        $stmt = $this->db->prepare("
            SELECT * FROM cities
            WHERE LOWER(slug) = LOWER(:slug)
            LIMIT 1
        ");
        $stmt->execute([':slug' => trim($slug)]);
        return $stmt->fetch();
    }

    /**
     * Mencari kota berdasarkan nama asli ATAU alias pencarian (contoh: Bali -> Denpasar, Jogja -> Yogyakarta)
     */
    public function findByName(string $name) {
        $clean = trim($name);
        $stmt = $this->db->prepare("
            SELECT * FROM cities
            WHERE LOWER(name) = LOWER(:name)
               OR LOWER(search_alias) = LOWER(:alias)
            LIMIT 1
        ");
        $stmt->execute([
            ':name'  => $clean,
            ':alias' => $clean
        ]);
        return $stmt->fetch();
    }
}
