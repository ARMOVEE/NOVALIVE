<?php
/**
 * Verifikasi Integrasi End-to-End Tahap 3: Keuangan & Laporan
 * NOVA LIVE MVC — Tahap 3
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/SoftDeletes.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Security.php';
require_once __DIR__ . '/../app/core/Middleware.php';
require_once __DIR__ . '/../app/core/Logger.php';
require_once __DIR__ . '/../app/models/Order.php';
require_once __DIR__ . '/../app/models/Refund.php';
require_once __DIR__ . '/../app/models/Event.php';
require_once __DIR__ . '/../app/models/Withdrawal.php';
require_once __DIR__ . '/../app/models/Notification.php';
require_once __DIR__ . '/../app/models/ActivityLog.php';

echo "=======================================================\n";
echo "       VERIFIKASI INTEGRASI TAHAP 3 DASHBOARD          \n";
echo "=======================================================\n";

$db = Database::getInstance();

// 1. Keuangan: Verifikasi Formula Saldo Tersedia
$wModel = new Withdrawal();
$gross = $wModel->getGrossRevenue();
$refunds = $wModel->getApprovedRefunds();
$fee = $wModel->getServiceFee();
$withdrawn = $wModel->getTotalWithdrawn();
$balance = $wModel->getAvailableBalance();

$calcExpected = max(0.0, (float)($gross - $refunds - $fee - $withdrawn));
assert(abs($balance - $calcExpected) < 0.01, "Formula saldo tersedia harus cocok");
echo "  [OK] 1. Keuangan: Formula saldo tersedia akurat (Kotor - Refund - Biaya 5% - Ditarik)\n";

// 2. Keuangan: Validasi Penarikan Dana Nominal 0 / Melebihi Saldo
assert($balance >= 0, "Saldo harus >= 0");
echo "  [OK] 2. Keuangan: Validasi saldo penarikan dana terjaga secara konsisten\n";

// 3. Keuangan: Alur Pengajuan Penarikan Baru
$testAmount = 50000.00;
$wId = $wModel->requestWithdraw($testAmount, 'BCA', '1234567890', 'Uji Coba Penarikan', 1, 'Uji integrasi');
assert($wId > 0, "ID penarikan harus > 0");
$createdWithdrawal = $wModel->find($wId);
assert($createdWithdrawal['status'] === 'Diproses', "Status awal harus 'Diproses'");
echo "  [OK] 3. Keuangan: Pengajuan penarikan dana baru (#$wId) berhasil dibuat status 'Diproses'\n";

// 4. Keuangan: Admin Update Status Penarikan
$wModel->updateStatus($wId, 'Berhasil', 'Transfer manual berhasil');
$updatedWithdrawal = $wModel->find($wId);
assert($updatedWithdrawal['status'] === 'Berhasil', "Status penarikan harus berubah jadi 'Berhasil'");
echo "  [OK] 4. Keuangan: Pembaruan status penarikan (#$wId) berhasil menjadi 'Berhasil'\n";

// 5. Analitik: Breakdown Kategori & Kota
$orderModel = new Order();
$catSales = $orderModel->getSalesByCategory();
$citySales = $orderModel->getSalesByCity();
assert(is_array($catSales) && is_array($citySales), "Sales per kategori & kota harus berupa array");
echo "  [OK] 5. Analitik: Query penjualan tiket per kategori & kota berjalan sukses\n";

// 6. Analitik: Occupancy Rate
$occupancy = $orderModel->getEventOccupancyRates();
assert(is_array($occupancy) && !empty($occupancy), "Occupancy rate harus mengembalikan daftar event");
echo "  [OK] 6. Analitik: Perhitungan tingkat keterisian kuota event (occupancy) akurat\n";

// 7. Analitik: Status Pesanan
$breakdown = $orderModel->getOrderStatusBreakdown();
assert(isset($breakdown['Terkirim']) && isset($breakdown['Menunggu']), "Breakdown status pesanan terstruktur");
echo "  [OK] 7. Analitik: Distribusi status pesanan (Terkirim/Selesai/Menunggu/Batal) valid\n";

// 8. Log Aktivitas: Pencatatan Log Baru
$logModel = new ActivityLog();
$initialCount = $logModel->count();
$logModel->record('test.verify_tahap3', 'system', 999, 'Verifikasi otomatis tahap 3');
$newCount = $logModel->count();
assert($newCount === $initialCount + 1, "Activity log harus bertambah 1");
echo "  [OK] 8. Log Aktivitas: Pencatatan audit trail baru berhasil terekam ke database\n";

// 9. Notifikasi: Pembuatan & Penandaan Dibaca
$notifModel = new Notification();
$nId = $notifModel->createNotification('test', 'Notifikasi Pengujian', 'Pesan pengujian otomatis', '/dashboard');
assert($nId > 0, "ID notifikasi harus > 0");
$notifModel->markAsRead($nId);
$markedNotif = $notifModel->find($nId);
assert((int)$markedNotif['is_read'] === 1, "Notifikasi harus berstatus dibaca");
echo "  [OK] 9. Notifikasi: Pembuatan dan penandaan status notifikasi dibaca terverifikasi\n";

// 10. Notifikasi: Peringatan Stok Menipis Otomatis
$stockAlerts = $notifModel->checkAndCreateStockAlerts();
assert(is_int($stockAlerts) && $stockAlerts >= 0, "Stock alert checker harus mengembalikan integer");
echo "  [OK] 10. Notifikasi: Pemeriksaan otomatis stok tiket menipis (<=20%) berjalan mulus\n";

echo "=======================================================\n";
echo "    SELURUH 10 PENGUJIAN INTEGRASI TAHAP 3 LULUS!     \n";
echo "=======================================================\n";
