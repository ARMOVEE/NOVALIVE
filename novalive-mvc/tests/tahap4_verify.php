<?php
/**
 * Verifikasi Integrasi End-to-End Tahap 4: Homepage (Banner & Tata Letak)
 * NOVA LIVE MVC — Tahap 4
 */

require_once __DIR__ . '/../app/config/config.php';
require_once __DIR__ . '/../app/core/helpers.php';
require_once __DIR__ . '/../app/core/SoftDeletes.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/core/Model.php';
require_once __DIR__ . '/../app/core/Controller.php';
require_once __DIR__ . '/../app/core/Security.php';
require_once __DIR__ . '/../app/core/Upload.php';
require_once __DIR__ . '/../app/models/Banner.php';
require_once __DIR__ . '/../app/models/HomepageSection.php';
require_once __DIR__ . '/../app/models/Event.php';
require_once __DIR__ . '/../app/models/ActivityLog.php';

echo "=======================================================\n";
echo "       VERIFIKASI INTEGRASI TAHAP 4 DASHBOARD          \n";
echo "=======================================================\n";

$db = Database::getInstance();
$bannerModel = new Banner();
$secModel = new HomepageSection();
$logModel = new ActivityLog();

$passed = 0;
$total = 10;

function check(int $num, string $title, bool $condition) {
    global $passed;
    if ($condition) {
        $passed++;
        echo "  [OK] {$num}. {$title}\n";
    } else {
        echo "  [GAGAL] {$num}. {$title}\n";
    }
}

// 1. Banner: Seed 3 slide aktif tanpa gambar sesuai spesifikasi
$allBanners = $bannerModel->allBanners();
check(1, "Banner: 3 slide demo tersedia di database", count($allBanners) >= 3);

// 2. Banner: Create slide baru
$nextOrder = $bannerModel->getNextSortOrder();
$newBannerId = $bannerModel->create([
    'title'      => 'Promo Uji Coba Integrasi',
    'subtitle'   => 'Deskripsi uji coba banner otomatis',
    'link_url'   => '/events',
    'cta_text'   => 'Cek Promo',
    'bg_color'   => '#23649A',
    'sort_order' => $nextOrder,
    'is_active'  => 1
]);
check(2, "Banner: Berhasil menambahkan slide baru (#{$newBannerId})", $newBannerId > 0);

// 3. Banner: Update slide
$updated = $bannerModel->update($newBannerId, [
    'title'    => 'Promo Uji Coba Diperbarui',
    'cta_text' => 'Beli Sekarang'
]);
$fetched = $bannerModel->find($newBannerId);
check(3, "Banner: Berhasil memperbarui data slide (#{$newBannerId})", $updated && $fetched['title'] === 'Promo Uji Coba Diperbarui');

// 4. Banner: Toggle status aktif
$bannerModel->toggleActive($newBannerId);
$toggled = $bannerModel->find($newBannerId);
$inactiveOk = (int)$toggled['is_active'] === 0;
$bannerModel->toggleActive($newBannerId);
$toggledBack = $bannerModel->find($newBannerId);
$activeOk = (int)$toggledBack['is_active'] === 1;
check(4, "Banner: Toggle status aktif/nonaktif slide berfungsi sempurna", $inactiveOk && $activeOk);

// 5. Banner: Reorder urutan naik/turun
$reordered = $bannerModel->reorder($newBannerId, 'up');
check(5, "Banner: Penukaran urutan slide naik (reorder) berhasil", $reordered);

// 6. Banner: Penghapusan slide dan pembersihan file
// Buat dummy file di uploads/banners
$dummyFile = ROOT_PATH . '/public/uploads/banners/dummy_test_banner.jpg';
file_put_contents($dummyFile, 'dummy content');
$dummyBannerId = $bannerModel->create([
    'title'      => 'Banner dengan Gambar Dummy',
    'image'      => 'uploads/banners/dummy_test_banner.jpg',
    'sort_order' => 999,
    'is_active'  => 1
]);
$delOk = $bannerModel->deleteSlide($dummyBannerId);
$fileDeleted = !file_exists($dummyFile);
// Bersihkan juga test banner sebelumnya
$bannerModel->deleteSlide($newBannerId);
check(6, "Banner: Penghapusan slide menghapus row DB dan berkas fisik dari server", $delOk && $fileDeleted);

// 7. Homepage Section: Memuat seluruh konfigurasi default 4 section
$sections = $secModel->allSections();
$keys = array_keys($sections);
check(7, "Tata Letak: 4 section ('event_seru', 'kategori', 'event_populer', 'jelajahi_kota') terkonfigurasi", 
    in_array('event_seru', $keys) && in_array('kategori', $keys) && in_array('event_populer', $keys) && in_array('jelajahi_kota', $keys)
);

// 8. Homepage Section: Update judul dan visibilitas
$secModel->updateSection('event_seru', [
    'title'      => 'Pilihan Konser & Festival Seru',
    'is_visible' => 1,
    'item_limit' => 12
]);
$seruCheck = $secModel->findByKey('event_seru');
$titleOk = $seruCheck['title'] === 'Pilihan Konser & Festival Seru' && (int)$seruCheck['item_limit'] === 12;
// Kembalikan ke judul default
$secModel->updateSection('event_seru', [
    'title'      => 'Event Seru Untukmu',
    'is_visible' => 1,
    'item_limit' => 10
]);
check(8, "Tata Letak: Pengaturan judul, visibilitas, dan batas item section tersimpan", $titleOk);

// 9. Kurasi Event: Set dan Get featured events untuk section Seru & Populer
$testSeruEvents = [1, 2, 4, 3];
$testPopEvents = [3, 1];
$secModel->setFeaturedEvents('event_seru', $testSeruEvents);
$secModel->setFeaturedEvents('event_populer', $testPopEvents);
$curatedSeru = $secModel->getFeaturedEventIds('event_seru');
$curatedPop = $secModel->getFeaturedEventIds('event_populer');
check(9, "Kurasi Event: Penyimpanan urutan event terpilih (featured_events) akurat", 
    $curatedSeru === $testSeruEvents && $curatedPop === $testPopEvents
);

// 10. Audit Trail: Pencatatan activity log untuk aksi homepage
$logModel->record('banner.test_action', 'banner', 1, 'Uji coba pencatatan audit log homepage');
$latestLog = $db->query("SELECT * FROM activity_logs WHERE action = 'banner.test_action' ORDER BY id DESC LIMIT 1")->fetch(PDO::FETCH_ASSOC);
check(10, "Log Aktivitas: Audit trail merekam aksi kelola banner & tata letak", !empty($latestLog));

echo "=======================================================\n";
if ($passed === $total) {
    echo "    SELURUH 10 PENGUJIAN INTEGRASI TAHAP 4 LULUS!     \n";
} else {
    $failedCount = $total - $passed;
    echo "    TERDAPAT {$failedCount} PENGUJIAN YANG GAGAL! \n";
}
echo "=======================================================\n";
