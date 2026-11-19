<?php
/**
 * Model Category
 * Mengelola data kategori event dengan dukungan penghitungan jumlah event aktif
 */

class Category extends Model {
    protected string $table = 'categories';

    /**
     * Mengambil seluruh kategori beserta jumlah event aktif per kategori
     */
    public function allWithCount(): array {
        $sql = "
            SELECT c.*, COUNT(e.id) AS event_count
            FROM categories c
            LEFT JOIN events e ON e.category_id = c.id AND e.deleted_at IS NULL
            WHERE c.deleted_at IS NULL
            GROUP BY c.id
            ORDER BY c.id ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Override all() untuk mengembalikan kategori aktif
     */
    public function all(bool $includeDeleted = false): array {
        return $this->allWithCount();
    }

    /**
     * Menambahkan kategori baru
     */
    public function create(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO categories (name, description, icon, color)
            VALUES (:name, :description, :icon, :color)
        ");
        $stmt->execute([
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':icon'        => $data['icon'] ?? null,
            ':color'       => $data['color'] ?? '#0E3254'
        ]);
        return (int)$this->db->lastInsertId();
    }

    /**
     * Memperbarui kategori
     */
    public function update($id, array $data): bool {
        $stmt = $this->db->prepare("
            UPDATE categories
            SET name = :name, description = :description, icon = :icon, color = :color
            WHERE id = :id AND deleted_at IS NULL
        ");
        return $stmt->execute([
            ':id'          => $id,
            ':name'        => $data['name'],
            ':description' => $data['description'] ?? null,
            ':icon'        => $data['icon'] ?? null,
            ':color'       => $data['color'] ?? '#0E3254'
        ]);
    }

    /**
     * Mencari kategori aktif berdasarkan nama (dengan opsi mengecualikan ID tertentu)
     */
    public function findByName(string $name, ?int $excludeId = null) {
        $sql = "SELECT * FROM categories WHERE LOWER(name) = LOWER(:name) AND deleted_at IS NULL";
        $params = [':name' => trim($name)];
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
     * Mencari kategori berdasarkan nama termasuk yang ter-soft delete
     */
    public function findByNameRaw(string $name) {
        $stmt = $this->db->prepare("SELECT * FROM categories WHERE LOWER(name) = LOWER(:name) LIMIT 1");
        $stmt->execute([':name' => trim($name)]);
        return $stmt->fetch();
    }

    /**
     * Menghitung jumlah event aktif yang menggunakan kategori ini
     */
    public function eventCount(int $id): int {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) FROM events
            WHERE category_id = :id AND deleted_at IS NULL
        ");
        $stmt->execute([':id' => $id]);
        return (int)$stmt->fetchColumn();
    }
}
