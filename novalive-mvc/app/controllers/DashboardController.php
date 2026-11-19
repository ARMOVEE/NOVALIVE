<?php
/**
 * DashboardController
 * Menangani panel administratif penyelenggara:
 * - Ringkasan Dashboard
 * - Menu Event: Event Saya, Stok Tiket, Kategori, Kota
 * - Menu Pesanan: Pesanan Tiket
 * NOVA LIVE MVC — Tahap 1
 */

class DashboardController extends Controller {
    public function __construct() {
        $this->requireLogin();
    }

    /**
     * Helper untuk menghitung badge sidebar
     */
    protected function getSidebarCounts(): array {
        $eventModel = $this->model('Event');
        $orderModel = $this->model('Order');
        $refundModel = $this->model('Refund');
        $notifModel = $this->model('Notification');

        return [
            'events'        => $eventModel->totalEvents(),
            'orders'        => $orderModel->count(),
            'refund'        => $refundModel->countPending(),
            'notifications' => $notifModel->unreadCount()
        ];
    }

    /**
     * 1. Halaman Ringkasan Dashboard
     */
    public function index(): void {
        $this->authorize('dashboard.view');

        $eventModel = $this->model('Event');
        $orderModel = $this->model('Order');

        $totalEvents = $eventModel->totalEvents();
        $totalSold = $eventModel->totalTicketsSold();
        $totalRevenue = $orderModel->totalRevenue();
        $totalOrders = $orderModel->count();

        $recentOrders = $orderModel->recent(5);
        $topSelling = $eventModel->popular(4);
        $upcomingEvents = $eventModel->upcoming(5);

        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/index', [
            'pageTitle'      => 'NOVA LIVE — Dashboard Admin',
            'activeNav'      => 'ringkasan',
            'activeMenu'     => 'ringkasan',
            'activeTab'      => null,
            'totalEvents'    => $totalEvents,
            'totalSold'      => $totalSold,
            'totalRevenue'   => $totalRevenue,
            'totalOrders'    => $totalOrders,
            'recentOrders'   => $recentOrders,
            'topSelling'     => $topSelling,
            'upcomingEvents' => $upcomingEvents,
            'sidebarCounts'  => $sidebarCounts
        ], false);
    }

    /**
     * 2.1 Halaman Event Saya (Tab 1 dari Menu Event)
     */
    public function events(): void {
        $this->authorize('events.view');

        $page = max(1, (int)query_param('page', 1));
        $filters = [
            'search' => query_param('search', '')
        ];

        $eventModel = $this->model('Event');
        $eventsData = $eventModel->paginate($filters, $page, 15);
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/events', array_merge($eventsData, [
            'pageTitle'     => 'NOVA LIVE — Event Saya',
            'activeNav'     => 'events',
            'activeMenu'    => 'event',
            'activeTab'     => 'events',
            'filters'       => $filters,
            'sidebarCounts' => $sidebarCounts
        ]), false);
    }

    /**
     * 2.2 Halaman Stok Tiket (Tab 2 dari Menu Event)
     */
    public function stock(): void {
        $this->authorize('events.view');

        $eventModel = $this->model('Event');
        $rawEvents = $eventModel->allStock();

        $totalQuota = 0;
        $totalSold = 0;
        $lowStockCount = 0;
        $events = [];

        foreach ($rawEvents as $ev) {
            $quota = (int)($ev['quota'] ?? 0);
            $sold = (int)($ev['sold'] ?? 0);
            $remaining = max(0, $quota - $sold);
            $pctSold = $quota > 0 ? round(($sold / $quota) * 100) : 0;
            $pctRemaining = $quota > 0 ? ($remaining / $quota) : 0;

            if ($remaining === 0) {
                $stockStatus = 'habis';
                $stockLabel = 'Habis';
                $stockBadgeCls = 'b-refund';
                $lowStockCount++;
            } elseif ($pctRemaining <= 0.20) {
                $stockStatus = 'sedikit';
                $stockLabel = 'Tinggal Sedikit';
                $stockBadgeCls = 'b-wait';
                $lowStockCount++;
            } else {
                $stockStatus = 'aman';
                $stockLabel = 'Aman';
                $stockBadgeCls = 'b-live';
            }

            $totalQuota += $quota;
            $totalSold += $sold;

            $ev['remaining'] = $remaining;
            $ev['pct_sold'] = $pctSold;
            $ev['stock_status'] = $stockStatus;
            $ev['stock_label'] = $stockLabel;
            $ev['stock_badge_cls'] = $stockBadgeCls;
            $events[] = $ev;
        }

        $totalRemaining = max(0, $totalQuota - $totalSold);
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/stock', [
            'pageTitle'     => 'NOVA LIVE — Stok Tiket',
            'activeNav'     => 'stock',
            'activeMenu'    => 'event',
            'activeTab'     => 'stock',
            'events'        => $events,
            'kpi'           => [
                'totalQuota'     => $totalQuota,
                'totalSold'      => $totalSold,
                'totalRemaining' => $totalRemaining,
                'lowStockCount'  => $lowStockCount
            ],
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 2.2.1 Update Kuota Stok Tiket (POST /dashboard/events/stock/{id})
     */
    public function updateStock(int $id): void {
        $this->authorize('events.update');

        if (!validate_csrf()) {
            $this->flash('error', 'Token CSRF tidak valid. Silakan coba kembali.');
            $this->redirect('dashboard/events/stock');
        }

        $eventModel = $this->model('Event');
        $event = $eventModel->find($id);

        if (!$event) {
            $this->flash('error', 'Event tidak ditemukan.');
            $this->redirect('dashboard/events/stock');
        }

        $rawQuota = post_input('quota', null);
        if ($rawQuota === null || !is_numeric($rawQuota)) {
            $this->flash('error', 'Jumlah kuota tiket harus berupa angka bulat yang valid.');
            $this->redirect('dashboard/events/stock');
        }

        $newQuota = (int)$rawQuota;
        $sold = (int)($event['sold'] ?? 0);

        if ($newQuota < 0) {
            $this->flash('error', 'Jumlah kuota tiket tidak boleh bernilai negatif.');
            $this->redirect('dashboard/events/stock');
        }

        // VALIDASI: tidak boleh lebih kecil dari jumlah terjual
        if ($newQuota < $sold) {
            $this->flash('error', 'Total kuota tidak boleh lebih kecil dari tiket yang sudah terjual (' . number_format($sold, 0, ',', '.') . ' tiket).');
            $this->redirect('dashboard/events/stock');
        }

        $oldQuota = (int)($event['quota'] ?? 0);
        $eventModel->updateQuota($id, $newQuota);

        $this->logActivity(
            'event.quota_update',
            'event',
            $id,
            'Mengubah total kuota event "' . $event['title'] . '" dari ' . number_format($oldQuota, 0, ',', '.') . ' menjadi ' . number_format($newQuota, 0, ',', '.') . ' tiket'
        );

        $this->flash('success', 'Kuota tiket untuk event "' . $event['title'] . '" berhasil diperbarui menjadi ' . number_format($newQuota, 0, ',', '.') . ' tiket.');
        $this->redirect('dashboard/events/stock');
    }

    /**
     * 2.3 Halaman Kategori (Tab 3 dari Menu Event)
     */
    public function categories(): void {
        $this->authorize('categories.view');

        $categoryModel = $this->model('Category');
        $categories = $categoryModel->allWithCount();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/categories', [
            'pageTitle'     => 'NOVA LIVE — Kategori Event',
            'activeNav'     => 'categories',
            'activeMenu'    => 'event',
            'activeTab'     => 'categories',
            'categories'    => $categories,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 2.3.1 Simpan Kategori Baru (POST /dashboard/categories/store)
     */
    public function storeCategory(): void {
        $this->authorize('categories.manage');

        if (!validate_csrf()) {
            $this->flash('error', 'Token CSRF tidak valid. Silakan coba kembali.');
            $this->redirect('dashboard/categories');
        }

        $name = trim((string)post_input('name', ''));
        $description = trim((string)post_input('description', ''));
        $color = trim((string)post_input('color', '#23649A'));
        $icon = trim((string)post_input('icon', 'musik'));

        if ($name === '') {
            $this->flash('error', 'Nama kategori wajib diisi.');
            $this->redirect('dashboard/categories');
        }

        $categoryModel = $this->model('Category');
        $existingRaw = $categoryModel->findByNameRaw($name);
        if ($existingRaw) {
            if ($existingRaw['deleted_at'] === null) {
                $this->flash('error', 'Kategori dengan nama "' . $name . '" sudah ada. Silakan gunakan nama lain.');
                $this->redirect('dashboard/categories');
            } else {
                $newId = (int)$existingRaw['id'];
                $categoryModel->restore($newId);
                $categoryModel->update($newId, [
                    'name'        => $name,
                    'description' => $description !== '' ? $description : null,
                    'color'       => $color !== '' ? $color : '#23649A',
                    'icon'        => $icon !== '' ? $icon : 'musik'
                ]);
                $this->logActivity('category.create', 'category', $newId, 'Menambahkan kembali kategori "' . $name . '"');
                $this->flash('success', 'Kategori "' . $name . '" berhasil ditambahkan.');
                $this->redirect('dashboard/categories');
            }
        }

        $newId = $categoryModel->create([
            'name'        => $name,
            'description' => $description !== '' ? $description : null,
            'color'       => $color !== '' ? $color : '#23649A',
            'icon'        => $icon !== '' ? $icon : 'musik'
        ]);

        $this->logActivity('category.create', 'category', $newId, 'Menambahkan kategori baru "' . $name . '"');
        $this->flash('success', 'Kategori "' . $name . '" berhasil ditambahkan.');
        $this->redirect('dashboard/categories');
    }

    /**
     * 2.3.2 Perbarui Kategori (POST /dashboard/categories/update/{id})
     */
    public function updateCategory(int $id): void {
        $this->authorize('categories.manage');

        if (!validate_csrf()) {
            $this->flash('error', 'Token CSRF tidak valid. Silakan coba kembali.');
            $this->redirect('dashboard/categories');
        }

        $categoryModel = $this->model('Category');
        $category = $categoryModel->find($id);

        if (!$category) {
            $this->flash('error', 'Kategori tidak ditemukan.');
            $this->redirect('dashboard/categories');
        }

        $name = trim((string)post_input('name', ''));
        $description = trim((string)post_input('description', ''));
        $color = trim((string)post_input('color', '#23649A'));
        $icon = trim((string)post_input('icon', 'musik'));

        if ($name === '') {
            $this->flash('error', 'Nama kategori tidak boleh kosong.');
            $this->redirect('dashboard/categories');
        }

        if ($categoryModel->findByName($name, $id)) {
            $this->flash('error', 'Nama kategori "' . $name . '" sudah digunakan oleh kategori lain.');
            $this->redirect('dashboard/categories');
        }

        $categoryModel->update($id, [
            'name'        => $name,
            'description' => $description !== '' ? $description : null,
            'color'       => $color !== '' ? $color : '#23649A',
            'icon'        => $icon !== '' ? $icon : 'musik'
        ]);

        $this->logActivity('category.update', 'category', $id, 'Memperbarui kategori "' . $name . '"');
        $this->flash('success', 'Kategori "' . $name . '" berhasil diperbarui.');
        $this->redirect('dashboard/categories');
    }

    /**
     * 2.3.3 Hapus Kategori (Soft Delete) (POST/GET /dashboard/categories/delete/{id})
     */
    public function deleteCategory(int $id): void {
        $this->authorize('categories.manage');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !validate_csrf()) {
            $this->flash('error', 'Token CSRF tidak valid. Silakan coba kembali.');
            $this->redirect('dashboard/categories');
        }

        $categoryModel = $this->model('Category');
        $category = $categoryModel->find($id);

        if (!$category) {
            $this->flash('error', 'Kategori tidak ditemukan.');
            $this->redirect('dashboard/categories');
        }

        // TOLAK HAPUS jika masih dipakai oleh event aktif
        $activeEvents = $categoryModel->eventCount($id);
        if ($activeEvents > 0) {
            $this->flash('error', 'Kategori masih dipakai ' . $activeEvents . ' event. Pindahkan atau hapus event terkait terlebih dahulu sebelum menghapus kategori ini.');
            $this->redirect('dashboard/categories');
        }

        $categoryModel->delete($id);

        $this->logActivity('category.delete', 'category', $id, 'Menghapus kategori "' . $category['name'] . '" (soft delete)');
        $this->flash('success', 'Kategori "' . $category['name'] . '" berhasil dihapus.');
        $this->redirect('dashboard/categories');
    }

    /**
     * 2.4 Halaman Kota & Landmark (Tab 4 dari Menu Event)
     */
    public function cities(): void {
        $this->authorize('cities.view');

        $cityModel = $this->model('City');
        $cities = $cityModel->allWithCount();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/cities', [
            'pageTitle'     => 'NOVA LIVE — Kota & Landmark',
            'activeNav'     => 'cities',
            'activeMenu'    => 'event',
            'activeTab'     => 'cities',
            'cities'        => $cities,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 2.4.1 Simpan Kota Baru (POST /dashboard/cities/store)
     */
    public function storeCity(): void {
        $this->authorize('cities.manage');

        if (!validate_csrf()) {
            $this->flash('error', 'Token CSRF tidak valid. Silakan coba kembali.');
            $this->redirect('dashboard/cities');
        }

        $name = trim((string)post_input('name', ''));
        $slug = trim((string)post_input('slug', ''));
        $landmark = trim((string)post_input('landmark', ''));
        $accent = trim((string)post_input('accent', '#23649A'));
        $searchAlias = trim((string)post_input('search_alias', ''));

        if ($name === '') {
            $this->flash('error', 'Nama kota wajib diisi.');
            $this->redirect('dashboard/cities');
        }

        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
            $slug = trim($slug, '-');
        }

        $cityModel = $this->model('City');
        $existingRaw = $cityModel->findByNameRaw($name);
        if ($existingRaw) {
            if ($existingRaw['deleted_at'] === null) {
                $this->flash('error', 'Kota dengan nama "' . $name . '" sudah terdaftar.');
                $this->redirect('dashboard/cities');
            } else {
                $newId = (int)$existingRaw['id'];
                $cityModel->restore($newId);
                $cityModel->update($newId, [
                    'name'         => $name,
                    'slug'         => $slug,
                    'landmark'     => $landmark !== '' ? $landmark : null,
                    'accent'       => $accent !== '' ? $accent : '#23649A',
                    'search_alias' => $searchAlias !== '' ? $searchAlias : null
                ]);
                $this->logActivity('city.create', 'city', $newId, 'Menambahkan kembali kota "' . $name . '"');
                $this->flash('success', 'Kota "' . $name . '" berhasil ditambahkan.');
                $this->redirect('dashboard/cities');
            }
        }

        if ($cityModel->findBySlug($slug)) {
            $slug .= '-' . time();
        }

        $newId = $cityModel->create([
            'name'         => $name,
            'slug'         => $slug,
            'landmark'     => $landmark !== '' ? $landmark : null,
            'accent'       => $accent !== '' ? $accent : '#23649A',
            'search_alias' => $searchAlias !== '' ? $searchAlias : null
        ]);

        $this->logActivity('city.create', 'city', $newId, 'Menambahkan kota baru "' . $name . '"');
        $this->flash('success', 'Kota "' . $name . '" berhasil ditambahkan.');
        $this->redirect('dashboard/cities');
    }

    /**
     * 2.4.2 Perbarui Kota (POST /dashboard/cities/update/{id})
     */
    public function updateCity(int $id): void {
        $this->authorize('cities.manage');

        if (!validate_csrf()) {
            $this->flash('error', 'Token CSRF tidak valid. Silakan coba kembali.');
            $this->redirect('dashboard/cities');
        }

        $cityModel = $this->model('City');
        $city = $cityModel->find($id);

        if (!$city) {
            $this->flash('error', 'Kota tidak ditemukan.');
            $this->redirect('dashboard/cities');
        }

        $name = trim((string)post_input('name', ''));
        $slug = trim((string)post_input('slug', ''));
        $landmark = trim((string)post_input('landmark', ''));
        $accent = trim((string)post_input('accent', '#23649A'));
        $searchAlias = trim((string)post_input('search_alias', ''));

        if ($name === '') {
            $this->flash('error', 'Nama kota tidak boleh kosong.');
            $this->redirect('dashboard/cities');
        }

        if ($slug === '') {
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', $name));
            $slug = trim($slug, '-');
        }

        if ($cityModel->findByName($name, $id)) {
            $this->flash('error', 'Nama kota "' . $name . '" sudah terdaftar.');
            $this->redirect('dashboard/cities');
        }

        if ($cityModel->findBySlug($slug, $id)) {
            $this->flash('error', 'Slug "' . $slug . '" sudah digunakan oleh kota lain.');
            $this->redirect('dashboard/cities');
        }

        $cityModel->update($id, [
            'name'         => $name,
            'slug'         => $slug,
            'landmark'     => $landmark !== '' ? $landmark : null,
            'accent'       => $accent !== '' ? $accent : '#23649A',
            'search_alias' => $searchAlias !== '' ? $searchAlias : null
        ]);

        $this->logActivity('city.update', 'city', $id, 'Memperbarui data kota "' . $name . '"');
        $this->flash('success', 'Kota "' . $name . '" berhasil diperbarui.');
        $this->redirect('dashboard/cities');
    }

    /**
     * 2.4.3 Hapus Kota (Soft Delete) (POST/GET /dashboard/cities/delete/{id})
     */
    public function deleteCity(int $id): void {
        $this->authorize('cities.manage');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !validate_csrf()) {
            $this->flash('error', 'Token CSRF tidak valid. Silakan coba kembali.');
            $this->redirect('dashboard/cities');
        }

        $cityModel = $this->model('City');
        $city = $cityModel->find($id);

        if (!$city) {
            $this->flash('error', 'Kota tidak ditemukan.');
            $this->redirect('dashboard/cities');
        }

        // TOLAK HAPUS jika masih memiliki event aktif
        $activeEvents = $cityModel->eventCount($id);
        if ($activeEvents > 0) {
            $this->flash('error', 'Kota "' . $city['name'] . '" masih dipakai ' . $activeEvents . ' event aktif. Tidak dapat menghapus kota yang masih memiliki event.');
            $this->redirect('dashboard/cities');
        }

        $cityModel->delete($id);

        $this->logActivity('city.delete', 'city', $id, 'Menghapus kota "' . $city['name'] . '" (soft delete)');
        $this->flash('success', 'Kota "' . $city['name'] . '" berhasil dihapus.');
        $this->redirect('dashboard/cities');
    }

    /**
     * 3. Halaman Manajemen Pesanan Tiket (Menu Pesanan)
     */
    public function orders(): void {
        $this->authorize('orders.view');

        $page = max(1, (int)query_param('page', 1));
        $filters = [
            'status' => query_param('status', ''),
            'search' => query_param('search', '')
        ];

        $orderModel = $this->model('Order');
        $statusCounts = $orderModel->countsByStatus();
        $ordersData = $orderModel->paginate($filters, $page, 15);
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/orders', array_merge($ordersData, [
            'pageTitle'     => 'NOVA LIVE — Pesanan Tiket',
            'activeNav'     => 'orders',
            'activeMenu'    => 'pesanan',
            'activeTab'     => 'orders',
            'statusCounts'  => $statusCounts,
            'filters'       => $filters,
            'sidebarCounts' => $sidebarCounts
        ]), false);
    }

    /**
     * 3.1 Sub-tab Check-in Tiket (Menu Pesanan)
     */
    public function checkin(): void {
        $this->authorize('orders.view');

        $eventModel = $this->model('Event');
        $orderModel = $this->model('Order');

        $events = $eventModel->all();
        $selectedEventId = (int)query_param('event_id', 0);
        $checkinStats = $orderModel->getCheckinStats($selectedEventId);
        $todayCheckins = $orderModel->getTodayCheckins($selectedEventId);
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/checkin', [
            'pageTitle'       => 'NOVA LIVE — Check-in Tiket',
            'activeNav'       => 'orders',
            'activeMenu'      => 'pesanan',
            'activeTab'       => 'checkin',
            'events'          => $events,
            'selectedEventId' => $selectedEventId,
            'checkinStats'    => $checkinStats,
            'todayCheckins'   => $todayCheckins,
            'sidebarCounts'   => $sidebarCounts
        ], false);
    }

    /**
     * Memproses Validasi Check-in Tiket (POST)
     */
    public function processCheckin(): void {
        $this->authorize('orders.view');
        $this->validateCsrf();

        $code = trim(post_data('ticket_code', ''));
        $eventId = (int)post_data('event_id', 0);

        if ($code === '') {
            $this->flash('error', 'Kode tiket tidak boleh kosong.');
            $this->redirect('dashboard/orders/checkin' . ($eventId > 0 ? '?event_id=' . $eventId : ''));
        }

        $orderModel = $this->model('Order');
        $order = $orderModel->findByTicketCode($code);

        if (!$order) {
            $this->flash('error', "Tiket dengan kode '{$code}' tidak ditemukan di sistem.");
            $this->redirect('dashboard/orders/checkin' . ($eventId > 0 ? '?event_id=' . $eventId : ''));
        }

        if (in_array($order['status'], ['Menunggu', 'Dibatalkan'], true)) {
            $this->flash('error', "Tiket tidak dapat di-check-in karena status pesanan: {$order['status']}.");
            $this->redirect('dashboard/orders/checkin' . ($eventId > 0 ? '?event_id=' . $eventId : ''));
        }

        if (!empty($order['checked_in_at'])) {
            $prevTime = date('d M Y, H:i', strtotime($order['checked_in_at'])) . ' WIB';
            $this->flash('error', "Tiket '{$code}' SUDAH PERNAH CHECK-IN pada {$prevTime} ({$order['buyer_name']}).");
            $this->redirect('dashboard/orders/checkin' . ($eventId > 0 ? '?event_id=' . $eventId : ''));
        }

        // Sukses check-in
        $orderModel->markCheckedIn((int)$order['id']);
        $this->logActivity('order.checkin', 'order', (int)$order['id'], "Check-in tiket {$code} untuk {$order['buyer_name']} ({$order['event_title']})");

        $this->flash('success', "Check-in BERHASIL! Selamat datang, {$order['buyer_name']} ({$order['qty']} tiket · {$order['event_title']}).");
        $this->redirect('dashboard/orders/checkin' . ($eventId > 0 ? '?event_id=' . $eventId : ''));
    }

    /**
     * 3.2 Sub-tab Refund / Pengembalian Dana (Menu Pesanan)
     */
    public function refunds(): void {
        $this->authorize('orders.view');

        $refundModel = $this->model('Refund');
        $status = query_param('status', '');
        $search = query_param('search', '');

        $filters = [];
        if ($status !== '' && $status !== 'Semua') {
            $filters['status'] = $status;
        }
        if ($search !== '') {
            $filters['search'] = $search;
        }

        $refunds = $refundModel->allFiltered($filters);
        $statusCounts = $refundModel->countsByStatus();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/refunds', [
            'pageTitle'     => 'NOVA LIVE — Refund & Pengembalian Dana',
            'activeNav'     => 'orders',
            'activeMenu'    => 'pesanan',
            'activeTab'     => 'refund',
            'refunds'       => $refunds,
            'statusCounts'  => $statusCounts,
            'currentStatus' => $status ?: 'Semua',
            'search'        => $search,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Menyetujui Refund (POST)
     */
    public function approveRefund(string $id): void {
        $this->authorize('orders.view');
        $this->validateCsrf();

        $refundId = (int)$id;
        $refundModel = $this->model('Refund');
        $refund = $refundModel->find($refundId);

        if (!$refund || $refund['status'] !== 'Diajukan') {
            $this->flash('error', 'Permohonan refund tidak ditemukan atau sudah diproses.');
            $this->redirect('dashboard/orders/refunds');
        }

        $userId = (int)($_SESSION['user']['id'] ?? 1);
        $success = $refundModel->approve($refundId, $userId);

        if ($success) {
            $this->logActivity('refund.approve', 'refund', $refundId, "Menyetujui refund #{$refundId} invoice {$refund['invoice']} senilai " . rupiah($refund['amount']));
            $this->flash('success', "Refund #{$refundId} untuk pesanan {$refund['invoice']} berhasil disetujui. Kuota {$refund['qty']} tiket telah dikembalikan ke event.");
        } else {
            $this->flash('error', 'Gagal memproses persetujuan refund.');
        }

        $this->redirect('dashboard/orders/refunds');
    }

    /**
     * Menolak Refund (POST)
     */
    public function rejectRefund(string $id): void {
        $this->authorize('orders.view');
        $this->validateCsrf();

        $refundId = (int)$id;
        $reason = trim(post_data('reject_reason', 'Tidak memenuhi syarat pengembalian dana'));
        $refundModel = $this->model('Refund');
        $refund = $refundModel->find($refundId);

        if (!$refund || $refund['status'] !== 'Diajukan') {
            $this->flash('error', 'Permohonan refund tidak ditemukan atau sudah diproses.');
            $this->redirect('dashboard/orders/refunds');
        }

        $userId = (int)($_SESSION['user']['id'] ?? 1);
        $success = $refundModel->reject($refundId, $userId, $reason);

        if ($success) {
            $this->logActivity('refund.reject', 'refund', $refundId, "Menolak refund #{$refundId} invoice {$refund['invoice']}. Alasan: {$reason}");
            $this->flash('success', "Permohonan refund #{$refundId} berhasil ditolak.");
        } else {
            $this->flash('error', 'Gagal memproses penolakan refund.');
        }

        $this->redirect('dashboard/orders/refunds');
    }

    /**
     * Mengajukan Refund Baru dari Detail Pesanan (POST)
     */
    public function createRefund(): void {
        $this->authorize('orders.view');
        $this->validateCsrf();

        $orderId = (int)post_data('order_id', 0);
        $reason = trim(post_data('reason', 'Permohonan pembatalan dari pembeli'));

        $orderModel = $this->model('Order');
        $order = $orderModel->find($orderId);

        if (!$order) {
            $this->flash('error', 'Data pesanan tidak ditemukan.');
            $this->redirect('dashboard/orders');
        }

        if (!in_array($order['status'], ['Terkirim', 'Selesai'], true)) {
            $this->flash('error', "Refund hanya dapat diajukan untuk pesanan berstatus Terkirim atau Selesai (status saat ini: {$order['status']}).");
            $this->redirect('dashboard/orders');
        }

        if (!empty($order['checked_in_at'])) {
            $this->flash('error', 'Tiket pesanan ini sudah di-check-in, tidak dapat mengajukan refund.');
            $this->redirect('dashboard/orders');
        }

        $refundModel = $this->model('Refund');
        $existing = $refundModel->findByOrderId($orderId);
        if ($existing) {
            $this->flash('error', "Pesanan {$order['invoice']} sudah memiliki permohonan refund sebelumnya (Status: {$existing['status']}).");
            $this->redirect('dashboard/orders');
        }

        $refundId = $refundModel->createRefund($orderId, $reason, (float)$order['total_price']);
        $this->logActivity('refund.create', 'refund', $refundId, "Mengajukan refund baru untuk pesanan {$order['invoice']} senilai " . rupiah($order['total_price']));

        $this->flash('success', "Permohonan refund untuk pesanan {$order['invoice']} berhasil diajukan.");
        $this->redirect('dashboard/orders/refunds');
    }

    /**
     * 3.3 Sub-tab E-Tiket / Dokumen (Menu Pesanan & Topbar Dokumen)
     */
    public function tickets(): void {
        $this->authorize('orders.view');

        $page = max(1, (int)query_param('page', 1));
        $filters = [
            'search'   => query_param('search', ''),
            'event_id' => query_param('event_id', '')
        ];

        $orderModel = $this->model('Order');
        $eventModel = $this->model('Event');

        $ticketsData = $orderModel->tickets($filters, $page, 15);
        $events = $eventModel->all();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/tickets', array_merge($ticketsData, [
            'pageTitle'     => 'NOVA LIVE — Pratinjau E-Tiket',
            'activeNav'     => 'tickets',
            'activeMenu'    => 'pesanan',
            'activeTab'     => 'etiket',
            'events'        => $events,
            'filters'       => $filters,
            'sidebarCounts' => $sidebarCounts
        ]), false);
    }

    /**
     * Halaman Pratinjau / Siap Cetak E-Tiket
     */
    public function showTicket(string $id): void {
        $this->authorize('orders.view');

        $orderModel = $this->model('Order');
        $order = $orderModel->find((int)$id);

        if (!$order || empty($order['ticket_code'])) {
            self::notFound('E-Tiket tidak ditemukan atau belum diterbitkan.');
        }

        $this->view('dashboard/ticket_detail', [
            'pageTitle' => 'NOVA LIVE — E-Tiket Resmi ' . ($order['ticket_code'] ?? ''),
            'order'     => $order
        ], false);
    }

    /**
     * Kirim Ulang E-Tiket (POST)
     */
    public function resendTicket(string $id): void {
        $this->authorize('orders.view');
        $this->validateCsrf();

        $orderId = (int)$id;
        $orderModel = $this->model('Order');
        $order = $orderModel->find($orderId);

        if (!$order || empty($order['ticket_code'])) {
            $this->flash('error', 'Pesanan tidak ditemukan atau tidak memiliki kode e-tiket.');
            $this->redirect('dashboard/orders/tickets');
        }

        // Tandai status Terkirim dan log ke activity_logs
        $orderModel->updateStatus($orderId, 'Terkirim');
        $this->logActivity('order.resend_ticket', 'order', $orderId, "Mengirim ulang e-tiket {$order['ticket_code']} ke pembeli {$order['buyer_email']}");

        $this->flash('success', "E-tiket {$order['ticket_code']} untuk {$order['buyer_name']} berhasil dikirim ulang.");
        $this->redirect('dashboard/orders/tickets');
    }

    // =========================================================================
    // TAHAP 3: KEUANGAN & LAPORAN
    // =========================================================================

    /**
     * Halaman Keuangan (/dashboard/finance)
     */
    public function finance(): void {
        $this->authorize('dashboard.view');

        $withdrawalModel = $this->model('Withdrawal');
        $orderModel = $this->model('Order');

        $grossRevenue = $withdrawalModel->getGrossRevenue();
        $approvedRefunds = $withdrawalModel->getApprovedRefunds();
        $serviceFee = $withdrawalModel->getServiceFee();
        $totalWithdrawn = $withdrawalModel->getTotalWithdrawn();
        $totalSuccessful = $withdrawalModel->getTotalSuccessful();
        $totalPending = $withdrawalModel->getTotalPending();
        $availableBalance = $withdrawalModel->getAvailableBalance();

        $monthlyRevenue = $orderModel->getMonthlyRevenue(6);
        $revenuePerEvent = $orderModel->getRevenuePerEvent();
        $withdrawals = $withdrawalModel->allWithUser();

        // Export CSV jika diminta
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="laporan-pendapatan-event-' . date('Ymd-His') . '.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['ID Event', 'Kode', 'Nama Event', 'Harga Tiket (Rp)', 'Kuota', 'Tiket Terjual', 'Pendapatan Kotor (Rp)', 'Refund (Rp)', 'Pendapatan Bersih (Rp)']);
            foreach ($revenuePerEvent as $row) {
                fputcsv($out, [
                    $row['id'],
                    $row['code'],
                    $row['title'],
                    $row['price'],
                    $row['quota'],
                    $row['sold'],
                    $row['gross_revenue'],
                    $row['refund_amount'],
                    $row['net_revenue']
                ]);
            }
            fclose($out);
            exit;
        }

        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/finance', [
            'pageTitle'        => 'NOVA LIVE — Keuangan',
            'activeNav'        => 'finance',
            'activeMenu'       => 'keuangan',
            'activeTab'        => 'finance',
            'grossRevenue'     => $grossRevenue,
            'approvedRefunds'  => $approvedRefunds,
            'serviceFee'       => $serviceFee,
            'totalWithdrawn'   => $totalWithdrawn,
            'totalSuccessful'  => $totalSuccessful,
            'totalPending'     => $totalPending,
            'availableBalance' => $availableBalance,
            'monthlyRevenue'   => $monthlyRevenue,
            'revenuePerEvent'  => $revenuePerEvent,
            'withdrawals'      => $withdrawals,
            'sidebarCounts'    => $sidebarCounts
        ], false);
    }

    /**
     * Memproses Pengajuan Penarikan Dana (POST)
     */
    public function withdraw(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $withdrawalModel = $this->model('Withdrawal');
        $availableBalance = $withdrawalModel->getAvailableBalance();

        $rawAmount = post_data('amount', 0);
        $amount = (float)str_replace(['Rp', '.', ' '], ['', '', ''], (string)$rawAmount);
        $bankName = trim(post_data('bank_name', ''));
        $accountNo = trim(post_data('account_no', ''));
        $accountName = trim(post_data('account_name', ''));
        $note = trim(post_data('note', ''));

        if ($amount <= 0) {
            $this->flash('error', 'Nominal penarikan harus lebih dari Rp 0.');
            $this->redirect('dashboard/finance');
        }

        if ($amount > $availableBalance) {
            $this->flash('error', 'Nominal penarikan (' . rupiah($amount) . ') melebihi saldo tersedia yang dapat ditarik (' . rupiah($availableBalance) . ').');
            $this->redirect('dashboard/finance');
        }

        if (empty($bankName) || empty($accountNo) || empty($accountName)) {
            $this->flash('error', 'Nama bank, nomor rekening, dan nama pemilik rekening wajib diisi lengkap.');
            $this->redirect('dashboard/finance');
        }

        $userId = $_SESSION['user']['id'] ?? null;
        $wId = $withdrawalModel->requestWithdraw($amount, $bankName, $accountNo, $accountName, $userId, $note);

        // Catat jejak audit ke activity_logs
        $this->logActivity('withdrawal.request', 'withdrawals', $wId, "Mengajukan penarikan dana sebesar " . rupiah($amount) . " ke rekening {$bankName} {$accountNo} a.n. {$accountName}");

        // Buat notifikasi otomatis
        $notifModel = $this->model('Notification');
        $notifModel->createNotification(
            'system',
            'Pengajuan Penarikan Dana Baru',
            "Pengajuan penarikan dana sebesar " . rupiah($amount) . " ke rekening {$bankName} ({$accountNo}) sedang diproses.",
            '/dashboard/finance',
            $userId
        );

        $this->flash('success', 'Pengajuan penarikan dana sebesar ' . rupiah($amount) . ' berhasil dikirim dan sedang diproses admin.');
        $this->redirect('dashboard/finance');
    }

    /**
     * Update Status Penarikan Dana (POST — Admin Only)
     */
    public function updateWithdrawalStatus(string $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        // Verifikasi peran admin
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            $this->flash('error', 'Hanya administrator yang memiliki wewenang mengubah status pencairan dana.');
            $this->redirect('dashboard/finance');
        }

        $wId = (int)$id;
        $status = trim(post_data('status', ''));
        $note = trim(post_data('note', ''));

        if (!in_array($status, ['Diproses', 'Berhasil', 'Ditolak'], true)) {
            $this->flash('error', 'Status penarikan dana tidak valid.');
            $this->redirect('dashboard/finance');
        }

        $withdrawalModel = $this->model('Withdrawal');
        $withdrawal = $withdrawalModel->find($wId);

        if (!$withdrawal) {
            $this->flash('error', 'Data penarikan dana tidak ditemukan.');
            $this->redirect('dashboard/finance');
        }

        $withdrawalModel->updateStatus($wId, $status, $note ?: null);

        $this->logActivity('withdrawal.update_status', 'withdrawals', $wId, "Mengubah status penarikan dana #{$wId} menjadi {$status}");

        $notifModel = $this->model('Notification');
        $notifModel->createNotification(
            'system',
            'Status Penarikan Dana Diperbarui',
            "Pencairan dana #{$wId} (" . rupiah($withdrawal['amount']) . ") status telah diubah menjadi: {$status}." . ($note ? " Catatan: {$note}" : ''),
            '/dashboard/finance',
            $withdrawal['created_by'] ?? null
        );

        $this->flash('success', "Status penarikan dana #{$wId} berhasil diubah menjadi {$status}.");
        $this->redirect('dashboard/finance');
    }

    /**
     * Halaman Analitik & Laporan (/dashboard/reports/analytics)
     */
    public function analytics(): void {
        $this->authorize('dashboard.view');

        $orderModel = $this->model('Order');

        $startDate = trim($_GET['start_date'] ?? '');
        $endDate = trim($_GET['end_date'] ?? '');

        $categorySales = $orderModel->getSalesByCategory($startDate, $endDate);
        $citySales = $orderModel->getSalesByCity($startDate, $endDate);
        $occupancyRates = $orderModel->getEventOccupancyRates();
        $statusBreakdown = $orderModel->getOrderStatusBreakdown($startDate, $endDate);
        $monthlyRevenue = $orderModel->getMonthlyRevenue(6);

        // Export CSV jika diminta
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename="laporan-analitik-penjualan-' . date('Ymd-His') . '.csv"');
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Tipe Laporan', 'Nama Entitas', 'Tiket Terjual', 'Total Pendapatan (Rp)']);
            foreach ($categorySales as $c) {
                fputcsv($out, ['Kategori', $c['name'], $c['tickets_sold'], $c['total_revenue']]);
            }
            foreach ($citySales as $ci) {
                fputcsv($out, ['Kota', $ci['name'], $ci['tickets_sold'], $ci['total_revenue']]);
            }
            fclose($out);
            exit;
        }

        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/analytics', [
            'pageTitle'       => 'NOVA LIVE — Analitik & Laporan',
            'activeNav'       => 'analytics',
            'activeMenu'      => 'laporan',
            'activeTab'       => 'analytics',
            'categorySales'   => $categorySales,
            'citySales'       => $citySales,
            'occupancyRates'  => $occupancyRates,
            'statusBreakdown' => $statusBreakdown,
            'monthlyRevenue'  => $monthlyRevenue,
            'startDate'       => $startDate,
            'endDate'         => $endDate,
            'sidebarCounts'   => $sidebarCounts
        ], false);
    }

    /**
     * Halaman Log Aktivitas (/dashboard/reports/logs)
     */
    public function activityLogs(): void {
        $this->authorize('dashboard.view');

        $page = max(1, (int)($_GET['page'] ?? 1));
        $filters = [
            'action'     => trim($_GET['action'] ?? ''),
            'user_id'    => trim($_GET['user_id'] ?? ''),
            'start_date' => trim($_GET['start_date'] ?? ''),
            'end_date'   => trim($_GET['end_date'] ?? ''),
            'search'     => trim($_GET['q'] ?? $_GET['search'] ?? '')
        ];

        $logModel = $this->model('ActivityLog');
        $logsData = $logModel->paginate($page, 20, $filters);
        $distinctActions = $logModel->getDistinctActions();

        $userModel = $this->model('User');
        $users = $userModel->all();

        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/logs', [
            'pageTitle'       => 'NOVA LIVE — Log Aktivitas Admin',
            'activeNav'       => 'logs',
            'activeMenu'      => 'laporan',
            'activeTab'       => 'logs',
            'logsData'        => $logsData,
            'distinctActions' => $distinctActions,
            'users'           => $users,
            'filters'         => $filters,
            'sidebarCounts'   => $sidebarCounts
        ], false);
    }

    /**
     * Halaman Notifikasi (/dashboard/reports/notifications)
     */
    public function notifications(): void {
        $this->authorize('dashboard.view');

        $notifModel = $this->model('Notification');
        // Sinkronkan peringatan stok menipis otomatis (<= 20% kuota)
        $notifModel->checkAndCreateStockAlerts();

        $notifications = $notifModel->all();
        $unreadCount = $notifModel->unreadCount();

        $eventModel = $this->model('Event');
        $events = $eventModel->all();

        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/notifications', [
            'pageTitle'     => 'NOVA LIVE — Notifikasi ke Pembeli & Sistem',
            'activeNav'     => 'notifications',
            'activeMenu'    => 'laporan',
            'activeTab'     => 'notifications',
            'notifications' => $notifications,
            'unreadCount'   => $unreadCount,
            'events'        => $events,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Tandai Satu Notifikasi Dibaca (POST)
     */
    public function markNotificationRead(string $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $notifId = (int)$id;
        $notifModel = $this->model('Notification');
        $notifModel->markAsRead($notifId);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->json(['success' => true]);
        }

        $this->flash('success', 'Notifikasi berhasil ditandai sebagai dibaca.');
        $this->redirect('dashboard/reports/notifications');
    }

    /**
     * Tandai Semua Notifikasi Dibaca (POST)
     */
    public function markAllNotificationsRead(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $notifModel = $this->model('Notification');
        $notifModel->markAllAsRead();

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->json(['success' => true]);
        }

        $this->flash('success', 'Semua notifikasi berhasil ditandai sebagai dibaca.');
        $this->redirect('dashboard/reports/notifications');
    }

    /**
     * Kirim Pengumuman ke Pembeli (POST)
     */
    public function createNotification(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $title = trim(post_data('title', ''));
        $message = trim(post_data('message', ''));
        $target = post_data('target', 'all');

        if (empty($title) || empty($message)) {
            $this->flash('error', 'Judul dan isi pesan pengumuman wajib diisi.');
            $this->redirect('dashboard/reports/notifications');
        }

        $notifModel = $this->model('Notification');
        $link = ($target !== 'all' && is_numeric($target)) ? "/dashboard/events" : '/dashboard/events';
        $notifId = $notifModel->createNotification('broadcast', $title, $message, $link);

        $this->logActivity('notification.broadcast', 'notifications', $notifId, "Menyiarkan pengumuman pembeli: {$title}");

        $this->flash('success', "Pengumuman '{$title}' berhasil disiarkan.");
        $this->redirect('dashboard/reports/notifications');
    }
}
