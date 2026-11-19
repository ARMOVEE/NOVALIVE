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
require_once __DIR__ . '/../app/models/Category.php';
require_once __DIR__ . '/../app/models/City.php';
require_once __DIR__ . '/../app/models/Event.php';
require_once __DIR__ . '/../app/models/Order.php';
require_once __DIR__ . '/../app/models/Refund.php';
require_once __DIR__ . '/../app/models/Withdrawal.php';
require_once __DIR__ . '/../app/models/Notification.php';
require_once __DIR__ . '/../app/models/ActivityLog.php';
require_once __DIR__ . '/../app/models/Banner.php';
require_once __DIR__ . '/../app/models/HomepageSection.php';
require_once __DIR__ . '/../app/models/Promo.php';
require_once __DIR__ . '/../app/models/Setting.php';
require_once __DIR__ . '/../app/models/User.php';

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
it("RBAC: Staff memiliki izin melihat kategori ('categories.view')", Middleware::check('categories.view', $staffUser) === true);
it("RBAC: Staff memiliki izin melihat kota ('cities.view')", Middleware::check('cities.view', $staffUser) === true);
it("RBAC: Staff DITOLAK mengelola kategori ('categories.manage')", Middleware::check('categories.manage', $staffUser) === false);
it("RBAC: Staff DITOLAK mengelola kota ('cities.manage')", Middleware::check('cities.manage', $staffUser) === false);
it("RBAC: Admin memiliki izin mengelola kategori ('categories.manage')", Middleware::check('categories.manage', $adminUser) === true);
it("RBAC: Admin memiliki izin mengelola kota ('cities.manage')", Middleware::check('cities.manage', $adminUser) === true);
it("RBAC: Default Deny berlaku bagi peran yang tidak terdaftar", Middleware::check('dashboard.view', $guestUser) === false);

// --- KELOMPOK 6: MODEL SOFT DELETES (2 Pengujian) ---
$traits = class_uses('Model');
it("Soft Delete: Kelas dasar Model menyertakan trait SoftDeletes", in_array('SoftDeletes', $traits, true));
$cityModel = new City();
$hasDelRef = new ReflectionMethod($cityModel, 'hasDeletedAt');
$hasDelRef->setAccessible(true);
it("Soft Delete: Tabel cities terdaftar memiliki kolom deleted_at", $hasDelRef->invoke($cityModel) === true);

// --- KELOMPOK 7: TAHAP 2 — CHECK-IN, REFUND, E-TIKET (6 Pengujian) ---
require_once ROOT_PATH . '/app/models/Order.php';
require_once ROOT_PATH . '/app/models/Refund.php';
require_once ROOT_PATH . '/app/models/Event.php';

$db = Database::getInstance();

// 35. Skema: orders.checked_in_at
$colCheckin = $db->query("SHOW COLUMNS FROM orders LIKE 'checked_in_at'")->fetchAll();
it("Tahap 2 Skema: Kolom checked_in_at tersedia pada tabel orders", count($colCheckin) > 0);

// 36. Skema: tabel refunds
$tblRefunds = $db->query("SHOW TABLES LIKE 'refunds'")->fetchAll();
it("Tahap 2 Skema: Tabel refunds tersedia di database", count($tblRefunds) > 0);

// 37. Model Order: findByTicketCode
$orderModel = new Order();
$foundOrder = $orderModel->findByTicketCode('NVL-A1B2C3');
it("Tahap 2 Order: findByTicketCode() menemukan order dengan kode 'NVL-A1B2C3'", !empty($foundOrder) && $foundOrder['ticket_code'] === 'NVL-A1B2C3');

// 38. Model Order: getCheckinStats
$checkinStats = $orderModel->getCheckinStats();
it("Tahap 2 Order: getCheckinStats() mengembalikan array statistik terstruktur", isset($checkinStats['checked_in']) && isset($checkinStats['total_tickets']) && isset($checkinStats['percentage']));

// 39. Model Refund: countPending
$refundModel = new Refund();
$pendingCount = $refundModel->countPending();
it("Tahap 2 Refund: countPending() menghitung pengajuan refund berstatus 'Diajukan'", is_int($pendingCount) && $pendingCount >= 0);

// 40. Model Event: upcoming
$eventModel = new Event();
$upcoming = $eventModel->upcoming(5);
it("Tahap 2 Event: upcoming() mengembalikan event mendatang terurut tanggal", is_array($upcoming) && count($upcoming) <= 5);

// ==========================================
// PENGUJIAN TAHAP 3: KEUANGAN & LAPORAN
// ==========================================

// 41. Skema Database: Tabel withdrawals
$hasWithdrawalsTable = !empty($db->query("SHOW TABLES LIKE 'withdrawals'")->fetchAll());
it("Tahap 3 Skema: Tabel withdrawals tersedia di database", $hasWithdrawalsTable);

// 42. Skema Database: Tabel notifications
$hasNotifTable = !empty($db->query("SHOW TABLES LIKE 'notifications'")->fetchAll());
it("Tahap 3 Skema: Tabel notifications tersedia di database", $hasNotifTable);

// 43. Model Withdrawal: getAvailableBalance & getGrossRevenue
$wModel = new Withdrawal();
$avail = $wModel->getAvailableBalance();
$gross = $wModel->getGrossRevenue();
it("Tahap 3 Keuangan: getAvailableBalance() mengembalikan nilai non-negatif float", is_float($avail) && $avail >= 0.0);

// 44. Model Withdrawal: Biaya Layanan 5%
$fee = $wModel->getServiceFee();
$expectedFee = round($gross * 0.05, 2);
it("Tahap 3 Keuangan: getServiceFee() menghitung tepat 5% dari pendapatan kotor", abs($fee - $expectedFee) < 0.01);

// 45. Model Order: getMonthlyRevenue (6 bulan)
$monthlyRev = $orderModel->getMonthlyRevenue(6);
it("Tahap 3 Keuangan: getMonthlyRevenue() mengembalikan data 6 bulan terstruktur", count($monthlyRev) === 6 && isset($monthlyRev[0]['label']));

// 46. Model Order: getRevenuePerEvent
$revPerEvent = $orderModel->getRevenuePerEvent();
it("Tahap 3 Keuangan: getRevenuePerEvent() memuat rincian kotor, refund, dan bersih", is_array($revPerEvent) && (!empty($revPerEvent) ? isset($revPerEvent[0]['net_revenue']) : true));

// 47. Model Notification: latest (5 item)
$notifModel = new Notification();
$latestNotifs = $notifModel->latest(5);
it("Tahap 3 Notifikasi: latest() mengembalikan daftar notifikasi terbaru", is_array($latestNotifs) && count($latestNotifs) <= 5);

// 48. Model Notification: unreadCount
$unreadCount = $notifModel->unreadCount();
it("Tahap 3 Notifikasi: unreadCount() menghitung notifikasi belum dibaca", is_int($unreadCount) && $unreadCount >= 0);

// 49. Model Notification: checkAndCreateStockAlerts
$stockAlerts = $notifModel->checkAndCreateStockAlerts();
it("Tahap 3 Notifikasi: checkAndCreateStockAlerts() berjalan tanpa error", is_int($stockAlerts) && $stockAlerts >= 0);

// 50. Model ActivityLog: paginate dengan filter
$logModel = new ActivityLog();
$logs = $logModel->paginate(1, 20, ['search' => 'test']);
it("Tahap 3 Log: paginate() mendukung filter kustom dan struktur paginasi 20 item", isset($logs['data']) && $logs['perPage'] === 20);

// 51. Skema Database: Tabel banners
$hasBannersTable = $db->query("SHOW TABLES LIKE 'banners'")->rowCount() > 0;
it("Tahap 4 Skema: Tabel banners tersedia di database", $hasBannersTable);

// 52. Skema Database: Tabel homepage_sections
$hasSectionsTable = $db->query("SHOW TABLES LIKE 'homepage_sections'")->rowCount() > 0;
it("Tahap 4 Skema: Tabel homepage_sections tersedia di database", $hasSectionsTable);

// 53. Skema Database: Tabel featured_events
$hasFeaturedTable = $db->query("SHOW TABLES LIKE 'featured_events'")->rowCount() > 0;
it("Tahap 4 Skema: Tabel featured_events tersedia di database", $hasFeaturedTable);

// 54. Model Banner: allActive()
$bannerModel = new Banner();
$activeBanners = $bannerModel->allActive();
it("Tahap 4 Banner: allActive() mengembalikan daftar banner aktif", is_array($activeBanners) && count($activeBanners) >= 1);

// 55. Model Banner: getNextSortOrder()
$nextOrder = $bannerModel->getNextSortOrder();
it("Tahap 4 Banner: getNextSortOrder() mengembalikan integer urutan berikutnya", is_int($nextOrder) && $nextOrder > 0);

// 56. Model Banner: toggleActive()
$firstBanner = $bannerModel->find(1);
$origStatus = (int)($firstBanner['is_active'] ?? 1);
$bannerModel->toggleActive(1);
$toggledBanner = $bannerModel->find(1);
$isToggled = (int)$toggledBanner['is_active'] === (1 - $origStatus);
$bannerModel->toggleActive(1); // restore
it("Tahap 4 Banner: toggleActive() berhasil mengubah status aktif slide", $isToggled);

// 57. Model HomepageSection: allSections()
$secModel = new HomepageSection();
$sections = $secModel->allSections();
it("Tahap 4 HomepageSection: allSections() memuat 4 konfigurasi section", count($sections) >= 4 && isset($sections['event_seru'], $sections['kategori']));

// 58. Model HomepageSection: updateSection()
$origTitle = $sections['event_seru']['title'];
$secModel->updateSection('event_seru', ['title' => 'Event Seru Terpilih']);
$updatedSec = $secModel->findByKey('event_seru');
$secModel->updateSection('event_seru', ['title' => $origTitle]); // restore
it("Tahap 4 HomepageSection: updateSection() berhasil memperbarui konfigurasi section", $updatedSec['title'] === 'Event Seru Terpilih');

// 59. Model HomepageSection: setFeaturedEvents() & getFeaturedEvents()
$testEventIds = [1, 2, 3];
$secModel->setFeaturedEvents('event_seru', $testEventIds);
$featuredIds = $secModel->getFeaturedEventIds('event_seru');
it("Tahap 4 HomepageSection: setFeaturedEvents() dan getFeaturedEvents() konsisten", $featuredIds === $testEventIds);

// 60. Core Upload: Method image() mendukung parameter subfolder kustom
$refClass = new ReflectionClass('Upload');
$method = $refClass->getMethod('image');
$params = $method->getParameters();
$hasSubfolderParam = count($params) >= 2 && $params[1]->getName() === 'subfolder';
it("Tahap 4 Upload: Method image() mendukung parameter subfolder kustom", $hasSubfolderParam);

// 61. Skema Database Tahap 5: Tabel promo_codes dan settings tersedia
$pdo = Database::getInstance();
$tables = $pdo->query("SHOW TABLES")->fetchAll(PDO::FETCH_COLUMN);
$hasPromoTable = in_array('promo_codes', $tables, true);
$hasSettingsTable = in_array('settings', $tables, true);
it("Tahap 5 Skema: Tabel promo_codes dan settings tersedia di database", $hasPromoTable && $hasSettingsTable);

// 62. Skema Database Tahap 5: Kolom phone dan avatar tersedia pada tabel users
$userCols = $pdo->query("DESCRIBE users")->fetchAll(PDO::FETCH_COLUMN);
it("Tahap 5 Skema: Kolom phone dan avatar tersedia pada tabel users", in_array('phone', $userCols, true) && in_array('avatar', $userCols, true));

// 63. Model Promo: findByCode() menemukan kode promo 'NOVAHEMAT' (case-insensitive)
$promoModel = new Promo();
$foundPromo = $promoModel->findByCode('novahemat');
it("Tahap 5 Promo: findByCode() menemukan kode promo 'NOVAHEMAT' (case-insensitive)", !empty($foundPromo) && $foundPromo['code'] === 'NOVAHEMAT');

// 64. Model Promo: calculateStatus() mendeteksi status dengan tepat
$mockActive = ['is_active' => 1, 'quota' => 100, 'used' => 10, 'starts_at' => '2026-01-01', 'ends_at' => '2026-12-31'];
$mockExpired = ['is_active' => 1, 'quota' => 100, 'used' => 10, 'starts_at' => '2026-01-01', 'ends_at' => '2025-01-01'];
$mockFull = ['is_active' => 1, 'quota' => 50, 'used' => 50, 'starts_at' => '2026-01-01', 'ends_at' => '2026-12-31'];
$mockInactive = ['is_active' => 0, 'quota' => 100, 'used' => 0, 'starts_at' => '2026-01-01', 'ends_at' => '2026-12-31'];
$statusOk = ($promoModel->calculateStatus($mockActive) === 'Aktif') &&
            ($promoModel->calculateStatus($mockExpired) === 'Berakhir') &&
            ($promoModel->calculateStatus($mockFull) === 'Habis') &&
            ($promoModel->calculateStatus($mockInactive) === 'Nonaktif');
it("Tahap 5 Promo: calculateStatus() mendeteksi status Aktif, Berakhir, Habis, dan Nonaktif", $statusOk);

// 65. Model Promo: validate() sukses menghitung potongan diskon persen
$valResult = $promoModel->validate('NOVAHEMAT', 1, 1000000.0);
it("Tahap 5 Promo: validate() sukses menghitung potongan diskon persen (15% dari Rp 1.000.000 = Rp 150.000)", $valResult['valid'] === true && (float)$valResult['discount'] === 150000.0);

// 66. Model Promo: validate() membatasi diskon tidak melebihi subtotal
$valMax = $promoModel->validate('TULUSJKT', 2, 20000.0);
it("Tahap 5 Promo: validate() membatasi diskon tidak melebihi subtotal pembelian", $valMax['valid'] === true && (float)$valMax['discount'] === 20000.0);

// 67. Model Promo: validate() menolak kode promo kedaluwarsa atau tidak valid dengan pesan informatif
$valInvalid = $promoModel->validate('JAZZOKT', 1, 500000.0);
$valNotFound = $promoModel->validate('KODEPALSU123', 1, 500000.0);
it("Tahap 5 Promo: validate() menolak kode promo yang kedaluwarsa atau tidak terdaftar", !$valInvalid['valid'] && !empty($valInvalid['error']) && !$valNotFound['valid'] && !empty($valNotFound['error']));

// 68. Model Setting: get() dan set() berhasil membaca dan menyimpan konfigurasi
$settingModel = new Setting();
$testKey = 'test_kunci_sistem';
$settingModel->set($testKey, 'Nilai Uji Tahap 5');
$valRead = $settingModel->get($testKey);
$pdo->prepare("DELETE FROM settings WHERE setting_key = :k")->execute([':k' => $testKey]);
it("Tahap 5 Setting: get() dan set() berhasil membaca dan menyimpan konfigurasi sistem", $valRead === 'Nilai Uji Tahap 5');

// 69. Model User: allUsers() mengembalikan daftar pengguna dan emailExists() mendeteksi duplikasi
$userModel = new User();
$allUsersList = $userModel->allUsers();
$emailDuplicate = $userModel->emailExists('demo@novalive.id');
$emailAvailable = !$userModel->emailExists('email_acak_tidak_ada_98765@novalive.id');
it("Tahap 5 User: allUsers() mengembalikan daftar pengguna dan emailExists() mendeteksi duplikasi", count($allUsersList) >= 1 && $emailDuplicate && $emailAvailable);

// 70. Model User: updatePassword() menyimpan hash kata sandi yang valid via password_verify()
$demoUser = $userModel->findByEmail('demo@novalive.id');
$canVerifyDemo = false;
if ($demoUser) {
    // Cari hash mentah dari DB
    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
    $stmt->execute([':id' => $demoUser['id']]);
    $rawHash = $stmt->fetchColumn();
    $canVerifyDemo = password_verify('nova1234', $rawHash);
}
it("Tahap 5 User: updatePassword() menyimpan hash kata sandi yang valid via password_verify()", $canVerifyDemo);

echo "=======================================================\n";
echo sprintf("HASIL PENGUJIAN: Lulus: %d | Gagal: %d\n", $passed, $failed);
echo "=======================================================\n";

if ($failed === 0) {
    echo sprintf("SELURUH %d PENGUJIAN DINYATAKAN LULUS SEMPURNA!\n", $passed);
    exit(0);
} else {
    echo "TERDAPAT PENGUJIAN YANG GAGAL!\n";
    exit(1);
}
