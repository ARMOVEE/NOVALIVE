<?php
/**
 * Model User
 * Mengelola akun penyelenggara dan staf, otentikasi login, serta hashing kata sandi
 */

class User extends Model {
    protected string $table = 'users';

    /**
     * Mengambil seluruh pengguna untuk manajemen staf di dashboard
     */
    public function allUsers(): array {
        $sql = "
            SELECT id, username, email, full_name, role, is_active, phone, avatar, last_login_at, created_at
            FROM {$this->table}
            ORDER BY id ASC
        ";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Memeriksa ketersediaan email (apakah sudah dipakai pengguna lain)
     */
    public function emailExists(string $email, int $excludeId = 0): bool {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE LOWER(email) = LOWER(:email)";
        $params = [':email' => trim($email)];

        if ($excludeId > 0) {
            $sql .= " AND id != :excludeId";
            $params[':excludeId'] = $excludeId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return ((int)$stmt->fetchColumn()) > 0;
    }

    /**
     * Mencari pengguna aktif berdasarkan email
     */
    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE LOWER(email) = LOWER(:email) AND is_active = 1
            LIMIT 1
        ");
        $stmt->execute([':email' => trim($email)]);
        return $stmt->fetch();
    }

    /**
     * Mencari pengguna aktif berdasarkan username
     */
    public function findByUsername(string $username) {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE LOWER(username) = LOWER(:username) AND is_active = 1
            LIMIT 1
        ");
        $stmt->execute([':username' => trim($username)]);
        return $stmt->fetch();
    }

    /**
     * Memvalidasi login menggunakan email dan password
     * Mengembalikan data pengguna tanpa hash kata sandi jika berhasil, atau false jika gagal
     */
    public function login(string $email, string $password) {
        $user = $this->findByEmail($email);
        if (!$user) {
            return false;
        }

        if (!password_verify($password, $user['password'])) {
            return false;
        }

        // Hapus hash password sebelum mengembalikan data
        unset($user['password']);
        return $user;
    }

    /**
     * Memperbarui stempel waktu login terakhir pengguna
     */
    public function touchLogin(int $userId): void {
        $stmt = $this->db->prepare("
            UPDATE {$this->table}
            SET last_login_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([':id' => $userId]);
    }

    /**
     * Memperbarui profil akun pengguna (nama, email, phone, avatar)
     */
    public function updateProfile(int $id, array $data): bool {
        $allowed = ['full_name', 'email', 'phone', 'avatar'];
        $updateData = [];
        foreach ($data as $col => $val) {
            if (in_array($col, $allowed, true)) {
                $updateData[$col] = $val;
            }
        }
        if (empty($updateData)) {
            return false;
        }
        return $this->update($id, $updateData);
    }

    /**
     * Memperbarui kata sandi pengguna dengan hashing bcrypt
     */
    public function updatePassword(int $id, string $newPassword): bool {
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($id, ['password' => $hash]);
    }

    /**
     * Mengubah peran pengguna (admin/manager/staff)
     */
    public function updateRole(int $id, string $role): bool {
        if (!in_array($role, ['admin', 'manager', 'staff'], true)) {
            return false;
        }
        return $this->update($id, ['role' => $role]);
    }

    /**
     * Mengubah status aktif / nonaktif akun pengguna
     */
    public function toggleActive(int $id): bool {
        $user = $this->find($id);
        if (!$user) {
            return false;
        }
        $newStatus = empty($user['is_active']) ? 1 : 0;
        return $this->update($id, ['is_active' => $newStatus]);
    }

    /**
     * Menambahkan akun pengguna staf baru
     */
    public function createUser(array $data): int {
        $password = $data['password'] ?? 'nova1234';
        $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        if (empty($data['username'])) {
            $data['username'] = strtolower(explode('@', $data['email'] ?? 'user')[0]) . rand(100, 999);
        }
        if (!isset($data['is_active'])) {
            $data['is_active'] = 1;
        }
        return $this->create($data);
    }
}
