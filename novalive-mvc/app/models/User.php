<?php
/**
 * Model User
 * Mengelola akun penyelenggara dan staf, otentikasi login, serta hashing kata sandi
 */

class User extends Model {
    protected string $table = 'users';

    /**
     * Mencari pengguna aktif berdasarkan email
     */
    public function findByEmail(string $email) {
        $stmt = $this->db->prepare("
            SELECT * FROM users
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
            SELECT * FROM users
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
            UPDATE users
            SET last_login_at = NOW()
            WHERE id = :id
        ");
        $stmt->execute([':id' => $userId]);
    }
}
