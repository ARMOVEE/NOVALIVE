<?php
/**
 * Base Model
 * Kelas dasar untuk seluruh model dalam aplikasi NOVA LIVE MVC
 */

abstract class Model {
    use SoftDeletes;

    protected PDO $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Mengambil seluruh record dari tabel
     */
    public function all(bool $includeDeleted = false): array {
        $sql = "SELECT * FROM {$this->table}";
        if (!$includeDeleted && $this->hasDeletedAt()) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $sql .= " ORDER BY {$this->primaryKey} DESC";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    /**
     * Mencari satu record berdasarkan primary key
     */
    public function find($id, bool $includeDeleted = false) {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        if (!$includeDeleted && $this->hasDeletedAt()) {
            $sql .= " AND deleted_at IS NULL";
        }
        $sql .= " LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Menghapus record secara soft delete
     */
    public function delete($id): bool {
        if ($this->hasDeletedAt()) {
            return $this->softDelete($id);
        }

        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Menghitung jumlah record dalam tabel
     */
    public function count(bool $includeDeleted = false): int {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        if (!$includeDeleted && $this->hasDeletedAt()) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        return (int)$this->db->query($sql)->fetchColumn();
    }

    /**
     * Memeriksa apakah tabel memiliki kolom deleted_at
     */
    protected function hasDeletedAt(): bool {
        return in_array($this->table, ['events', 'categories']);
    }
}
