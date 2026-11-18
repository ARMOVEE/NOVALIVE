<?php
/**
 * Security Helper & Session Hardening
 * Mengelola keamanan sesi, proteksi CSRF, abort HTTP error, dan utilitas token
 */

class Security {
    /**
     * Memulai sesi dengan konfigurasi hardening dan pemeriksaan idle timeout
     */
    public static function startSession(): void {
        if (session_status() === PHP_SESSION_NONE) {
            $isHttps = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

            session_set_cookie_params([
                'lifetime' => 0,
                'path'     => '/',
                'domain'   => '',
                'secure'   => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');

            session_start();
        }

        // Cek idle timeout
        if (isset($_SESSION['user']) && isset($_SESSION['last_activity'])) {
            $idleTimeoutSeconds = SESSION_IDLE_TIMEOUT * 60;
            if (time() - $_SESSION['last_activity'] > $idleTimeoutSeconds) {
                self::logout();
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['flash']['error'] = 'Sesi Anda telah berakhir karena tidak ada aktivitas. Silakan login kembali.';
                header('Location: ' . base_url('login'));
                exit;
            }
        }

        $_SESSION['last_activity'] = time();
    }

    /**
     * Menghasilkan atau mengambil token CSRF (64 karakter hex)
     */
    public static function csrfToken(): string {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Memvalidasi token CSRF dengan perbandingan timing-safe
     */
    public static function validateCsrf(?string $token): bool {
        if (empty($token) || empty($_SESSION['csrf_token'])) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    /**
     * Memeriksa keberadaan token CSRF pada request POST (kecuali API)
     */
    public static function requireCsrf(): void {
        $token = $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        if (!self::validateCsrf($token)) {
            self::abort(419, 'Sesi CSRF telah kedaluwarsa atau tidak sah. Silakan muat ulang halaman dan coba lagi.');
        }
    }

    /**
     * Menyimpan data login pengguna ke sesi dengan regenerasi ID sesi
     */
    public static function loginUser(array $user): void {
        session_regenerate_id(true);
        unset($user['password']); // Hapus hash password dari memori sesi
        $_SESSION['user'] = $user;
        $_SESSION['last_activity'] = time();
    }

    /**
     * Mengeluarkan pengguna dan membersihkan data sesi
     */
    public static function logout(): void {
        $_SESSION['user'] = null;
        unset($_SESSION['user']);
        
        // Hapus cookie sesi
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }

    /**
     * Menghentikan eksekusi dan merender halaman error berlayout (403/404/419/500)
     */
    public static function abort(int $code = 404, string $message = ''): void {
        http_response_code($code);

        $titles = [
            400 => 'Permintaan Tidak Valid',
            403 => 'Akses Ditolak',
            404 => 'Halaman Tidak Ditemukan',
            419 => 'Sesi Berakhir (CSRF)',
            500 => 'Kesalahan Server Internal'
        ];

        $defaultMessages = [
            400 => 'Permintaan yang Anda kirimkan tidak dapat diproses oleh server.',
            403 => 'Anda tidak memiliki hak akses yang cukup untuk membuka halaman atau aksi ini.',
            404 => 'Halaman atau data yang Anda cari tidak ditemukan atau telah dipindahkan.',
            419 => 'Sesi formulir telah kedaluwarsa demi keamanan. Silakan muat ulang halaman.',
            500 => 'Terjadi kesalahan internal pada server kami. Silakan coba beberapa saat lagi.'
        ];

        $title = $titles[$code] ?? "Error $code";
        $msg = !empty($message) ? $message : ($defaultMessages[$code] ?? 'Terjadi kesalahan.');

        $errorViewFile = ROOT_PATH . '/app/views/errors/error.php';
        $headerFile = ROOT_PATH . '/app/views/layouts/header.php';
        $footerFile = ROOT_PATH . '/app/views/layouts/footer.php';

        $data = [
            'code'        => $code,
            'title'       => $title,
            'message'     => $msg,
            'pageTitle'   => "$code — $title | NOVA LIVE"
        ];

        extract($data);

        if (file_exists($errorViewFile)) {
            if (file_exists($headerFile)) {
                require $headerFile;
            }
            require $errorViewFile;
            if (file_exists($footerFile)) {
                require $footerFile;
            }
        } else {
            // Fallback sederhana jika file view error belum selesai dibuat
            echo "<!DOCTYPE html><html lang='id'><head><meta charset='utf-8'><title>$code — $title</title>";
            echo "<style>body{font-family:sans-serif;text-align:center;padding:80px 20px;background:#F5F1E6;color:#0E3254;}";
            echo "h1{font-size:72px;margin:0;color:#0E3254;}p{font-size:18px;max-width:500px;margin:16px auto;line-height:1.6;}";
            echo "a{display:inline-block;margin-top:20px;padding:10px 22px;background:#0E3254;color:#fff;text-decoration:none;border-radius:8px;font-weight:700;}</style></head>";
            echo "<body><h1>$code</h1><h2>$title</h2><p>" . htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') . "</p>";
            echo "<a href='" . base_url() . "'>Kembali ke Beranda</a></body></html>";
        }

        exit;
    }

    /**
     * Hash token SHA-256 untuk API token
     */
    public static function hashToken(string $token): string {
        return hash('sha256', $token);
    }

    /**
     * Mengambil dan membersihkan IP address klien
     */
    public static function clientIp(): string {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';
    }
}
