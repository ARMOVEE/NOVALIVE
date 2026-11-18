<?php
/**
 * Global Helper Functions
 * Fungsi pembantu yang tersedia di seluruh layer aplikasi dan tampilan (View)
 */

/**
 * Anti-XSS HTML escape
 */
function e(?string $string): string {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

/**
 * Format angka ke mata uang Rupiah
 */
function rupiah($amount): string {
    return 'Rp' . number_format((float)$amount, 0, ',', '.');
}

/**
 * Menghasilkan URL absolut berbasis BASEURL
 */
function base_url(string $path = ''): string {
    $base = defined('BASEURL') ? BASEURL : 'http://localhost:8000';
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

/**
 * Menghasilkan elemen input hidden untuk token CSRF
 */
function csrf_field(): string {
    $token = Security::csrfToken();
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

/**
 * Menghasilkan URL aset statis
 */
function asset(string $path): string {
    return base_url('assets/' . ltrim($path, '/'));
}

/**
 * Format tanggal Indonesia (contoh: "14 Nov 2026")
 */
function tanggal(?string $date, string $format = 'd M Y'): string {
    if (empty($date)) {
        return '-';
    }

    $ts = strtotime($date);
    if ($ts === false) {
        return $date;
    }

    $bulanIndo = [
        1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
        7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
    ];

    $d = date('d', $ts);
    $m = (int)date('n', $ts);
    $y = date('Y', $ts);

    if ($format === 'd M Y') {
        return $d . ' ' . ($bulanIndo[$m] ?? date('M', $ts)) . ' ' . $y;
    }

    return date($format, $ts);
}

/**
 * Memeriksa izin RBAC pengguna aktif
 */
function can(string $permission): bool {
    return Middleware::check($permission);
}

/**
 * Mengambil parameter query string dengan nilai default
 */
function query_param(string $key, $default = null) {
    if (!isset($_GET[$key])) {
        return $default;
    }
    $val = trim((string)$_GET[$key]);
    return $val !== '' ? $val : $default;
}

/**
 * Mengambil parameter POST dengan nilai default
 */
function post_input(string $key, $default = null) {
    if (!isset($_POST[$key])) {
        return $default;
    }
    if (is_array($_POST[$key])) {
        return $_POST[$key];
    }
    $val = trim((string)$_POST[$key]);
    return $val !== '' ? $val : $default;
}

/**
 * Mengambil data umum yang dibagi ke tampilan (pengguna login, flash message)
 */
function view_shared_data(): array {
    $flash = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);

    return [
        'currentUser' => $_SESSION['user'] ?? null,
        'flash'       => $flash
    ];
}

/**
 * Mengembalikan URL foto poster event.
 * Jika file tidak ada secara fisik, mengembalikan URL atau penanda agar View dapat menampilkan placeholder huruf
 */
function poster_url(?string $photo, string $title = ''): ?string {
    if (!empty($photo)) {
        $cleanPhoto = ltrim($photo, '/');
        $fullPath = ROOT_PATH . '/public/' . $cleanPhoto;
        if (is_file($fullPath)) {
            return base_url($cleanPhoto);
        }
    }
    return null;
}
