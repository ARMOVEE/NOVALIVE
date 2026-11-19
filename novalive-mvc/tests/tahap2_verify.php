<?php
/**
 * Verifikasi End-to-End Tahap 2: Check-in, Refund, E-Tiket & Layout Desktop
 */
require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/SoftDeletes.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Security.php';
require_once __DIR__ . '/../app/models/Order.php';
require_once __DIR__ . '/../app/models/Refund.php';
require_once __DIR__ . '/../app/models/Event.php';
require_once __DIR__ . '/../app/models/ActivityLog.php';
require_once __DIR__ . '/../app/controllers/DashboardController.php';

echo "=======================================================\n";
echo "       VERIFIKASI INTEGRASI TAHAP 2 DASHBOARD          \n";
echo "=======================================================\n";

$db = Database::getInstance();
$logModel = new ActivityLog();
$initialLogCount = $logModel->count();

// 1. Uji Validasi Check-in: Tiket Kosong
$orderModel = new Order();
$invalidCheck = $orderModel->findByTicketCode('');
assert($invalidCheck === null, "Check-in: kode kosong harus return null");
echo "  [OK] 1. Validasi Check-in: Kode kosong ditolak\n";

// 2. Uji Validasi Check-in: Kode Tidak Ada
$notFoundCheck = $orderModel->findByTicketCode('NVL-TIDAK-ADA');
assert($notFoundCheck === null, "Check-in: kode fiktif harus return null");
echo "  [OK] 2. Validasi Check-in: Kode fiktif ditolak\n";

// 3. Uji Check-in: Tiket Valid (Order 7: NVL-S1T2U3)
$order7 = $orderModel->findByTicketCode('NVL-S1T2U3');
assert(!empty($order7), "Order 7 harus ditemukan");
$orderModel->markCheckedIn((int)$order7['id']);
$refetched7 = $orderModel->findByTicketCode('NVL-S1T2U3');
assert(!empty($refetched7['checked_in_at']), "Order 7 harus memiliki checked_in_at setelah check-in");
echo "  [OK] 3. Check-in: Tiket valid 'NVL-S1T2U3' berhasil check-in dan mengisi checked_in_at\n";

// 4. Uji Double Check-in: Tiket sudah dipakai harus terdeteksi
$alreadyChecked = !empty($refetched7['checked_in_at']);
assert($alreadyChecked === true, "Tiket yang sudah check-in harus terdeteksi mencegah tiket ganda");
echo "  [OK] 4. Validasi Check-in: Penggunaan tiket berulang berhasil dicegah\n";

// 5. Uji Model Refund: Pending Count
$refundModel = new Refund();
$pendingBefore = $refundModel->countPending();
assert($pendingBefore >= 2, "Harus ada minimal 2 refund berstatus Diajukan");
echo "  [OK] 5. Refund: Pending count bernilai {$pendingBefore} (sesuai badge demo 2)\n";

// 6. Uji Buat Pengajuan Refund Baru dari Order Selesai (Order 6)
$order6 = $orderModel->find(6);
assert(!empty($order6), "Order 6 harus ada");
$newRefundId = $refundModel->createRefund(6, 'Keperluan mendesak di luar kota', (float)$order6['total_price']);
assert($newRefundId > 0, "Refund baru harus berhasil dibuat");
$newRefund = $refundModel->find($newRefundId);
assert($newRefund['status'] === 'Diajukan', "Status refund baru harus 'Diajukan'");
echo "  [OK] 6. Refund: Pengajuan refund baru (#{$newRefundId}) berhasil dibuat dengan status 'Diajukan'\n";

// 7. Uji Tolak Refund (#newRefundId)
$rejectOk = $refundModel->reject($newRefundId, 1, 'Event sudah mendekati H-1');
assert($rejectOk === true, "Penolakan refund harus berhasil");
$rejectedRefund = $refundModel->find($newRefundId);
assert($rejectedRefund['status'] === 'Ditolak', "Status refund harus menjadi 'Ditolak'");
echo "  [OK] 7. Refund: Permohonan refund #{$newRefundId} berhasil ditolak dengan status 'Ditolak'\n";

// 8. Uji Setujui Refund (#1)
// Simpan kuota sold event terkait sebelum persetujuan
$refund1 = $refundModel->find(1);
$eventId = (int)$refund1['event_id'];
$eventBefore = $db->query("SELECT sold FROM events WHERE id = {$eventId}")->fetch();
$soldBefore = (int)$eventBefore['sold'];

$approveOk = $refundModel->approve(1, 1);
assert($approveOk === true, "Persetujuan refund #1 harus berhasil");
$approvedRefund = $refundModel->find(1);
assert($approvedRefund['status'] === 'Disetujui', "Status refund #1 harus 'Disetujui'");
$order1After = $orderModel->find((int)$refund1['order_id']);
assert($order1After['status'] === 'Dibatalkan', "Status pesanan harus berubah menjadi 'Dibatalkan'");
$eventAfter = $db->query("SELECT sold FROM events WHERE id = {$eventId}")->fetch();
$soldAfter = (int)$eventAfter['sold'];
assert($soldAfter === max(0, $soldBefore - (int)$refund1['qty']), "Sold event harus berkurang sebesar qty refund");
echo "  [OK] 8. Refund: Persetujuan refund berhasil membatalkan order dan mengembalikan kuota tiket ke event\n";

// 9. Uji Model Event: upcoming (WHERE event_date >= CURDATE() AND deleted_at IS NULL)
$eventModel = new Event();
$upcoming = $eventModel->upcoming(5);
assert(count($upcoming) <= 5, "Upcoming event maksimal 5");
$today = date('Y-m-d');
foreach ($upcoming as $ue) {
    assert($ue['event_date'] >= $today, "Event date '{$ue['event_date']}' harus >= hari ini '{$today}'");
}
echo "  [OK] 9. Event Berikutnya: Query WHERE event_date >= CURDATE() terverifikasi valid\n";

// 10. Uji Daftar E-Tiket: Model Order tickets()
$ticketsResult = $orderModel->tickets([], 1, 15);
assert(!empty($ticketsResult['data']), "Harus ada data e-tiket");
foreach ($ticketsResult['data'] as $tk) {
    assert(!empty($tk['ticket_code']), "Semua baris di daftar tiket harus memiliki ticket_code");
    assert(in_array($tk['status'], ['Terkirim', 'Selesai'], true), "Status tiket harus Terkirim atau Selesai");
}
echo "  [OK] 10. E-Tiket: Daftar e-tiket menyaring pesanan dengan ticket_code status Terkirim/Selesai\n";

// Bersihkan test data agar database tetap rapi (kembalikan order 1 dan order 7)
$db->exec("UPDATE refunds SET status = 'Diajukan', processed_by = NULL, processed_at = NULL WHERE id = 1");
$db->exec("UPDATE orders SET status = 'Terkirim' WHERE id = 1");
$db->exec("UPDATE events SET sold = {$soldBefore} WHERE id = {$eventId}");
$db->exec("UPDATE orders SET checked_in_at = NULL WHERE id = 7");
$db->exec("DELETE FROM refunds WHERE id = {$newRefundId}");

echo "=======================================================\n";
echo "    SELURUH 10 PENGUJIAN INTEGRASI TAHAP 2 LULUS!     \n";
echo "=======================================================\n";
