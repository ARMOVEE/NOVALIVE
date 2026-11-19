<?php
/**
 * Model City
 * Mengelola data master kota, ikon landmark, dan resolusi alias pencarian (contoh: Denpasar <-> Bali)
 */

class City extends Model {
    protected string $table = 'cities';

    /**
     * Mengambil seluruh data kota aktif beserta jumlah event aktif per kota
     */
    public function allWithCount(): array {
        $sql = "
            SELECT ci.*, COUNT(e.id) AS event_count
            FROM cities ci
            LEFT JOIN events e ON e.city_id = ci.id AND e.deleted_at IS NULL
            WHERE ci.deleted_at IS NULL
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
     * Menambahkan kota baru
     */
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO cities (name, slug, landmark, accent, search_alias)
            VALUES (:name, :slug, :landmark, :accent, :search_alias)
        ");
        $stmt->execute([
            ':name'         => $data['name'],
            ':slug'         => $data['slug'],
            ':landmark'     => $data['landmark'] ?? null,
            ':accent'       => $data['accent'] ?? '#0E3254',
            ':search_alias' => $data['search_alias'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Memperbarui data kota
     */
    public function update($id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE cities
            SET name = :name, slug = :slug, landmark = :landmark, accent = :accent, search_alias = :search_alias
            WHERE id = :id AND deleted_at IS NULL
        ");
        return $stmt->execute([
            ':id'           => $id,
            ':name'         => $data['name'],
            ':slug'         => $data['slug'],
            ':landmark'     => $data['landmark'] ?? null,
            ':accent'       => $data['accent'] ?? '#0E3254',
            ':search_alias' => $data['search_alias'] ?? null
        ]);
    }

    /**
     * Mencari kota berdasarkan slug (contoh: 'jakarta', 'denpasar')
     */
    public function findBySlug(string $slug, ?int $excludeId = null) {
        $sql = "SELECT * FROM cities WHERE LOWER(slug) = LOWER(:slug) AND deleted_at IS NULL";
        $params = [':slug' => trim($slug)];
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Mencari kota berdasarkan nama asli ATAU alias pencarian
     */
    public function findByName(string $name, ?int $excludeId = null) {
        $clean = trim($name);
        $sql = "SELECT * FROM cities WHERE (LOWER(name) = LOWER(:name) OR LOWER(search_alias) = LOWER(:alias)) AND deleted_at IS NULL";
        $params = [':name' => $clean, ':alias' => $clean];
        if ($excludeId !== null) {
            $sql .= " AND id != :exclude_id";
            $params[':exclude_id'] = $excludeId;
        }
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }

    /**
     * Mencari kota berdasarkan nama asli termasuk yang ter-soft delete
     */
    public function findByNameRaw(string $name) {
        $stmt = $this->db->prepare("SELECT * FROM cities WHERE LOWER(name) = LOWER(:name) LIMIT 1");
        $stmt->execute([':name' => trim($name)]);
        return $stmt->fetch();
    }

    /**
     * Menghitung jumlah event aktif di kota ini
     */
    public function eventCount(int $id): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM events
            WHERE city_id = :id AND deleted_at IS NULL
        ");
        $stmt->execute([':id' => $id]);
        return (int)$stmt->fetchColumn();
    }
}
