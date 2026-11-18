<?php
/**
 * Konfigurasi Aplikasi NOVA LIVE MVC
 * Memuat variabel lingkungan dari file .env dan mendefinisikan konstanta global
 */

define('ROOT_PATH', realpath(__DIR__ . '/../../'));

// Loader file .env sederhana tanpa dependency pihak ketiga
if (file_exists(ROOT_PATH . '/.env')) {
    $lines = file(ROOT_PATH . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $key = trim($parts[0]);
            $val = trim($parts[1]);
            // Hapus tanda kutip jika ada
            if ((str_starts_with($val, '"') && str_ends_with($val, '"')) ||
                (str_starts_with($val, "'") && str_ends_with($val, "'"))) {
                $val = substr($val, 1, -1);
            }
            $_ENV[$key] = $val;
            putenv("$key=$val");
        }
    }
}

// Konstanta Aplikasi
define('APP_NAME', $_ENV['APP_NAME'] ?? 'NOVA LIVE');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
define('APP_DEBUG', filter_var($_ENV['APP_DEBUG'] ?? true, FILTER_VALIDATE_BOOLEAN));
define('BASEURL', rtrim($_ENV['BASE_URL'] ?? 'http://localhost:8000', '/'));

// Konstanta Database
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_PORT', (int)($_ENV['DB_PORT'] ?? 3306));
define('DB_NAME', $_ENV['DB_NAME'] ?? 'db_novalive');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// Konstanta Sesi & Keamanan
define('SESSION_IDLE_TIMEOUT', (int)($_ENV['SESSION_IDLE_TIMEOUT'] ?? 30)); // Dalam menit
define('UPLOAD_MAX_BYTES', (int)($_ENV['UPLOAD_MAX_MB'] ?? 2) * 1024 * 1024); // Default 2 MB

// Konstanta API
define('API_ALLOWED_ORIGIN', $_ENV['API_ALLOWED_ORIGIN'] ?? '*');
define('API_DEFAULT_LIMIT', (int)($_ENV['API_DEFAULT_LIMIT'] ?? 10));
