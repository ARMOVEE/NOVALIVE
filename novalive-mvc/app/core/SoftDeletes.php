<?php
/**
 * Trait SoftDeletes
 * Menyediakan fungsionalitas soft delete dan pemulihan data untuk Model
 */

trait SoftDeletes {
    public function softDelete($id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NOW() WHERE {$this->primaryKey} = :id");
        return $stmt->execute([':id' => $id]);
    }

    public function restore($id): bool {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET deleted_at = NULL WHERE {$this->primaryKey} = :id");
        return $stmt->execute([':id' => $id]);
    }
}
