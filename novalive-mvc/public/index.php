<?php
/**
 * Front Controller
 * Satu-satunya pintu masuk ke aplikasi NOVA LIVE MVC
 */

// Muat konfigurasi & helper
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/helpers.php';

// Atur pelaporan error berdasarkan APP_DEBUG
if (defined('APP_DEBUG') && APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Autoloader untuk core, models, dan controllers
spl_autoload_register(function ($class) {
    $paths = [
        ROOT_PATH . '/app/core/' . $class . '.php',
        ROOT_PATH . '/app/models/' . $class . '.php',
        ROOT_PATH . '/app/controllers/' . $class . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Mulai sesi dengan konfigurasi hardening
Security::startSession();

// Header Keamanan HTTP
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Cek CSRF pada semua request POST (kecuali endpoint API jika ada)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $url = $_GET['url'] ?? '';
    if (!str_starts_with($url, 'api/')) {
        Security::requireCsrf();
    }
}

// Jalankan Router Aplikasi
$app = new App();
$app->run();
