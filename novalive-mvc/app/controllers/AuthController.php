<?php
/**
 * AuthController
 * Menangani alur otentikasi login, logout, dan pengamanan sesi pengguna
 */

class AuthController extends Controller {
    /**
     * Menampilkan formulir login
     */
    public function login(): void {
        if (isset($_SESSION['user'])) {
            $this->redirect('dashboard');
        }

        $this->view('auth/login', [
            'pageTitle' => 'NOVA LIVE — Form Login'
        ], false);
    }

    /**
     * Memproses verifikasi kredensial login (POST)
     */
    public function authenticate(): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('login');
        }

        $email = trim((string)post_input('email', ''));
        $password = (string)post_input('password', '');

        if ($email === '' || $password === '') {
            $this->flash('error', 'Silakan masukkan email dan kata sandi.');
            $this->redirect('login');
        }

        $userModel = $this->model('User');
        $user = $userModel->login($email, $password);

        if (!$user) {
            // Catat kegagalan tanpa membocorkan apakah email terdaftar (CWE-204)
            $this->logActivity('auth.failed', 'user', null, 'Percobaan login gagal untuk kredensial yang dimasukkan.');
            $this->flash('error', 'Email atau password salah.');
            $this->flash('danger', 'Email atau password salah.');
            $this->redirect('login');
        }

        // Login sukses: simpan data ke sesi & catat audit trail
        Security::loginUser($user);
        $userModel->touchLogin($user['id']);
        $this->logActivity('auth.login', 'user', $user['id'], 'Pengguna berhasil masuk ke sistem');

        $this->flash('success', 'Selamat datang kembali, ' . $user['full_name'] . '!');
        $this->redirect('dashboard');
    }

    /**
     * Mengeluarkan pengguna dari sistem (Logout)
     */
    public function logout(): void {
        $userId = $_SESSION['user']['id'] ?? null;
        if ($userId) {
            $this->logActivity('auth.logout', 'user', $userId, 'Pengguna keluar dari sistem');
        }

        Security::logout();
        header('Location: ' . base_url('login'));
        exit;
    }
}
