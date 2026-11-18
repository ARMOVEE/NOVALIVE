<?php
/**
 * Test Runner Otomatis (Tanpa Framework Eksternal)
 * Menguji fungsionalitas RBAC, CSRF, Helper, Konfigurasi, dan Model
 * Target Uji: Lulus: 27 | Gagal: 0
 */

// Muat komponen aplikasi
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/SoftDeletes.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Security.php';
require_once __DIR__ . '/../app/core/Middleware.php';
require_once __DIR__ . '/../app/core/Upload.php';
require_once __DIR__ . '/../app/core/Logger.php';

// Pastikan sesi aktif untuk pengujian keamanan
Security::startSession();

$totalTests = 0;
$passed = 0;
$failed = 0;

function it(string $description, bool $condition) {
    global $totalTests, $passed, $failed;
    $totalTests++;
    if ($condition) {
        $passed++;
        echo sprintf("  [\033[32mOK\033[0m] %02d. %s\n", $totalTests, $description);
    } else {
        $failed++;
        echo sprintf("  [\033[31mFAIL\033[0m] %02d. %s\n", $totalTests, $description);
    }
}

echo "=======================================================\n";
echo "           NOVA LIVE MVC — PENGUJIAN OTOMATIS          \n";
echo "=======================================================\n";

// --- KELOMPOK 1: KONFIGURASI APLIKASI (5 Pengujian) ---
it("Konfigurasi: BASEURL terdefinisi dengan benar", defined('BASEURL') && str_starts_with(BASEURL, 'http'));
it("Konfigurasi: DB_HOST terdefinisi", defined('DB_HOST') && !empty(DB_HOST));
it("Konfigurasi: DB_NAME adalah 'db_novalive'", defined('DB_NAME') && DB_NAME === 'db_novalive');
it("Konfigurasi: SESSION_IDLE_TIMEOUT diatur ke 30 menit", defined('SESSION_IDLE_TIMEOUT') && SESSION_IDLE_TIMEOUT === 30);
it("Konfigurasi: UPLOAD_MAX_BYTES diatur ke 2 MB (2.097.152 bytes)", defined('UPLOAD_MAX_BYTES') && UPLOAD_MAX_BYTES === (2 * 1024 * 1024));

// --- KELOMPOK 2: DATABASE SINGLETON (2 Pengujian) ---
$db1 = Database::getInstance();
$db2 = Database::getInstance();
it("Database: Pola Singleton mengembalikan instance PDO tunggal yang sama", $db1 === $db2 && $db1 instanceof PDO);
$charset = $db1->query("SELECT @@character_set_connection")->fetchColumn();
it("Database: Koneksi aktif menggunakan charset UTF-8 (utf8mb4)", str_contains(strtolower($charset), 'utf8'));

// --- KELOMPOK 3: HELPER FUNCTIONS (5 Pengujian) ---
$xssInput = '<script>alert("xss")</script>';
it("Helper: e() berhasil meng-escape karakter berbahaya HTML (Anti-XSS)", e($xssInput) === '&lt;script&gt;alert(&quot;xss&quot;)&lt;/script&gt;');
it("Helper: rupiah() memformat nominal uang ke format Rupiah standar", rupiah(150000) === 'Rp150.000' && rupiah(0) === 'Rp0');
it("Helper: base_url() menghasilkan URL absolut yang dinormalisasi", base_url('events/show/2') === BASEURL . '/events/show/2');
it("Helper: csrf_field() menghasilkan tag HTML input hidden berisi token", str_contains(csrf_field(), 'type="hidden"') && str_contains(csrf_field(), 'name="csrf_token"'));
it("Helper: tanggal() memformat stempel tanggal ke format bulan Indonesia", tanggal('2026-11-14') === '14 Nov 2026');

// --- KELOMPOK 4: SECURITY & CSRF (5 Pengujian) ---
$csrfToken = Security::csrfToken();
it("Security: csrfToken() menghasilkan string heksadesimal 64 karakter acak", strlen($csrfToken) === 64 && ctype_xdigit($csrfToken));
it("Security: validateCsrf() memvalidasi token yang cocok dengan timing-safe", Security::validateCsrf($csrfToken) === true);
it("Security: validateCsrf() menolak token palsu atau kosong", Security::validateCsrf('invalid_csrf_token_string_12345') === false && Security::validateCsrf('') === false);
$hashedToken = Security::hashToken('test_api_token');
it("Security: hashToken() menghasilkan representasi hash SHA-256 64 karakter", strlen($hashedToken) === 64 && ctype_xdigit($hashedToken));
it("Security: clientIp() mendeteksi dan mengembalikan alamat IP yang valid", filter_var(Security::clientIp(), FILTER_VALIDATE_IP) !== false);

// --- KELOMPOK 5: MATRIKS RBAC (9 Pengujian) ---
$adminUser = ['role' => 'admin'];
$managerUser = ['role' => 'manager'];
$staffUser = ['role' => 'staff'];
$guestUser = ['role' => 'guest'];

it("RBAC: Administrator memiliki izin akses penuh (wildcard '*')", Middleware::check('anything', $adminUser) === true);
it("RBAC: Manager memiliki izin 'events.create'", Middleware::check('events.create', $managerUser) === true);
it("RBAC: Manager memiliki izin 'events.update'", Middleware::check('events.update', $managerUser) === true);
it("RBAC: Manager memiliki izin 'events.delete'", Middleware::check('events.delete', $managerUser) === true);
it("RBAC: Manager DITOLAK memulihkan event (hanya Admin yang boleh restore)", Middleware::check('events.restore', $managerUser) === false);
it("RBAC: Staff memiliki izin melihat dashboard ('dashboard.view')", Middleware::check('dashboard.view', $staffUser) === true);
it("RBAC: Staff memiliki izin melihat event ('events.view')", Middleware::check('events.view', $staffUser) === true);
it("RBAC: Staff DITOLAK membuat event ('events.create')", Middleware::check('events.create', $staffUser) === false);
it("RBAC: Default Deny berlaku bagi peran yang tidak terdaftar", Middleware::check('dashboard.view', $guestUser) === false);

// --- KELOMPOK 6: MODEL SOFT DELETES (1 Pengujian) ---
$traits = class_uses('Model');
it("Soft Delete: Kelas dasar Model menyertakan trait SoftDeletes", in_array('SoftDeletes', $traits, true));

echo "=======================================================\n";
echo sprintf("HASIL PENGUJIAN: Lulus: %d | Gagal: %d\n", $passed, $failed);
echo "=======================================================\n";

if ($passed === 27 && $failed === 0) {
    echo "SELURUH 27 PENGUJIAN DINYATAKAN LULUS SEMPURNA!\n";
    exit(0);
} else {
    echo "TERDAPAT PENGUJIAN YANG GAGAL!\n";
    exit(1);
}
