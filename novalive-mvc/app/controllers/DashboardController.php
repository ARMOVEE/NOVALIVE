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

    /* =========================================================================
     * TAHAP 4: HOMEPAGE (BANNER & TAMPILAN HOMEPAGE)
     * ========================================================================= */

    /**
     * Halaman Pengelolaan Slide Banner Homepage
     */
    public function banners(): void {
        $this->authorize('dashboard.view');

        $bannerModel = $this->model('Banner');
        $banners = $bannerModel->allBanners();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/banners', [
            'pageTitle'     => 'NOVA LIVE — Banner Homepage',
            'activeNav'     => 'banners',
            'activeMenu'    => 'homepage',
            'activeTab'     => 'banners',
            'banners'       => $banners,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Tambah Slide Banner Baru (POST)
     */
    public function storeBanner(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $title = trim((string)post_data('title', ''));
        $subtitle = trim((string)post_data('subtitle', ''));
        $linkUrl = trim((string)post_data('link_url', ''));
        $ctaText = trim((string)post_data('cta_text', 'Lihat Event'));
        $bgColor = trim((string)post_data('bg_color', '#15497A'));
        $startDate = post_data('start_date', '') ?: null;
        $endDate = post_data('end_date', '') ?: null;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $imagePath = null;
        if (!empty($_FILES['image']['name'])) {
            try {
                $imagePath = Upload::image('image', 'banners');
            } catch (\Exception $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect('dashboard/homepage/banners');
            }
        }

        $bannerModel = $this->model('Banner');
        $sortOrder = $bannerModel->getNextSortOrder();

        $newId = $bannerModel->create([
            'title'      => $title !== '' ? $title : null,
            'subtitle'   => $subtitle !== '' ? $subtitle : null,
            'image'      => $imagePath,
            'link_url'   => $linkUrl !== '' ? $linkUrl : null,
            'cta_text'   => $ctaText !== '' ? $ctaText : 'Lihat Event',
            'bg_color'   => $bgColor !== '' ? $bgColor : '#15497A',
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'sort_order' => $sortOrder,
            'is_active'  => $isActive
        ]);

        $this->logActivity('banner.create', 'banner', $newId, 'Menambahkan slide banner baru: "' . ($title ?: 'Slide #' . $newId) . '"');

        $this->flash('success', 'Slide banner baru berhasil ditambahkan.');
        $this->redirect('dashboard/homepage/banners');
    }

    /**
     * Perbarui Slide Banner (POST)
     */
    public function updateBanner(int $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $bannerModel = $this->model('Banner');
        $banner = $bannerModel->find($id);

        if (!$banner) {
            $this->flash('error', 'Slide banner tidak ditemukan.');
            $this->redirect('dashboard/homepage/banners');
        }

        $title = trim((string)post_data('title', ''));
        $subtitle = trim((string)post_data('subtitle', ''));
        $linkUrl = trim((string)post_data('link_url', ''));
        $ctaText = trim((string)post_data('cta_text', 'Lihat Event'));
        $bgColor = trim((string)post_data('bg_color', '#15497A'));
        $startDate = post_data('start_date', '') ?: null;
        $endDate = post_data('end_date', '') ?: null;
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        $imagePath = $banner['image'];
        if (!empty($_FILES['image']['name'])) {
            try {
                $newImage = Upload::image('image', 'banners');
                if ($newImage) {
                    Upload::delete($banner['image']);
                    $imagePath = $newImage;
                }
            } catch (\Exception $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect('dashboard/homepage/banners');
            }
        }

        $bannerModel->update($id, [
            'title'      => $title !== '' ? $title : null,
            'subtitle'   => $subtitle !== '' ? $subtitle : null,
            'image'      => $imagePath,
            'link_url'   => $linkUrl !== '' ? $linkUrl : null,
            'cta_text'   => $ctaText !== '' ? $ctaText : 'Lihat Event',
            'bg_color'   => $bgColor !== '' ? $bgColor : '#15497A',
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'is_active'  => $isActive
        ]);

        $this->logActivity('banner.update', 'banner', $id, 'Memperbarui slide banner #' . $id . ': "' . ($title ?: 'Slide #' . $id) . '"');

        $this->flash('success', 'Slide banner "' . ($title ?: 'Slide #' . $id) . '" berhasil diperbarui.');
        $this->redirect('dashboard/homepage/banners');
    }

    /**
     * Ubah Status Aktif / Nonaktif Slide Banner (POST)
     */
    public function toggleBanner(int $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $bannerModel = $this->model('Banner');
        $banner = $bannerModel->find($id);

        if (!$banner) {
            $this->flash('error', 'Slide banner tidak ditemukan.');
            $this->redirect('dashboard/homepage/banners');
        }

        $bannerModel->toggleActive($id);
        $this->logActivity('banner.toggle', 'banner', $id, 'Mengubah status aktif slide banner #' . $id);

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->json(['success' => true]);
        }

        $this->flash('success', 'Status slide banner berhasil diubah.');
        $this->redirect('dashboard/homepage/banners');
    }

    /**
     * Tukar Urutan Slide Banner Naik / Turun (POST)
     */
    public function reorderBanner(int $id, string $direction = 'up'): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $bannerModel = $this->model('Banner');
        $bannerModel->reorder($id, $direction);

        $this->logActivity('banner.reorder', 'banner', $id, 'Mengubah urutan slide banner #' . $id . ' (' . $direction . ')');

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            $this->json(['success' => true]);
        }

        $this->flash('success', 'Urutan slide banner berhasil diperbarui.');
        $this->redirect('dashboard/homepage/banners');
    }

    /**
     * Hapus Slide Banner (POST)
     */
    public function deleteBanner(int $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $bannerModel = $this->model('Banner');
        $banner = $bannerModel->find($id);

        if (!$banner) {
            $this->flash('error', 'Slide banner tidak ditemukan.');
            $this->redirect('dashboard/homepage/banners');
        }

        $bannerTitle = $banner['title'] ?? ('Slide #' . $id);
        $bannerModel->deleteSlide($id);

        $this->logActivity('banner.delete', 'banner', $id, 'Menghapus slide banner #' . $id . ' ("' . $bannerTitle . '")');

        $this->flash('success', 'Slide banner "' . $bannerTitle . '" berhasil dihapus.');
        $this->redirect('dashboard/homepage/banners');
    }

    /**
     * Halaman Tata Letak Homepage & Kurasi Featured Events
     */
    public function homepageLayout(): void {
        $this->authorize('dashboard.view');

        $sectionModel = $this->model('HomepageSection');
        $eventModel = $this->model('Event');

        $sections = $sectionModel->allSections();
        if (empty($sections)) {
            $sections = [
                'event_seru'    => ['section_key' => 'event_seru', 'title' => 'Event Seru Untukmu', 'is_visible' => 1, 'sort_order' => 1, 'item_limit' => 10],
                'kategori'      => ['section_key' => 'kategori', 'title' => 'Kategori Event', 'is_visible' => 1, 'sort_order' => 2, 'item_limit' => 8],
                'event_populer' => ['section_key' => 'event_populer', 'title' => 'Event Populer', 'is_visible' => 1, 'sort_order' => 3, 'item_limit' => 4],
                'jelajahi_kota' => ['section_key' => 'jelajahi_kota', 'title' => 'Jelajahi Event di Kotamu', 'is_visible' => 1, 'sort_order' => 4, 'item_limit' => 6]
            ];
        }

        $allEvents = $eventModel->all();
        $seruFeaturedIds = $sectionModel->getFeaturedEventIds('event_seru');
        $popFeaturedIds = $sectionModel->getFeaturedEventIds('event_populer');

        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/layout', [
            'pageTitle'       => 'NOVA LIVE — Tata Letak Homepage',
            'activeNav'       => 'layout',
            'activeMenu'      => 'homepage',
            'activeTab'       => 'layout',
            'sections'        => $sections,
            'events'          => $allEvents,
            'seruFeaturedIds' => $seruFeaturedIds,
            'popFeaturedIds'  => $popFeaturedIds,
            'sidebarCounts'   => $sidebarCounts
        ], false);
    }

    /**
     * Simpan Pengaturan Tata Letak Section Homepage (POST)
     */
    public function updateHomepageLayout(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $sectionModel = $this->model('HomepageSection');
        $postedSections = $_POST['sections'] ?? [];

        if (is_array($postedSections)) {
            foreach ($postedSections as $key => $data) {
                $title = trim((string)($data['title'] ?? ''));
                $isVisible = isset($data['is_visible']) ? 1 : 0;
                $sortOrder = (int)($data['sort_order'] ?? 0);
                $itemLimit = !empty($data['item_limit']) ? (int)$data['item_limit'] : null;

                $sectionModel->updateSection($key, [
                    'title'      => $title,
                    'is_visible' => $isVisible,
                    'sort_order' => $sortOrder,
                    'item_limit' => $itemLimit
                ]);
            }
        }

        $this->logActivity('homepage.layout_update', 'homepage_sections', null, 'Memperbarui pengaturan tata letak section homepage');

        $this->flash('success', 'Pengaturan tata letak section homepage berhasil disimpan.');
        $this->redirect('dashboard/homepage/layout');
    }

    /**
     * Simpan Kurasi Featured Events untuk Event Seru & Populer (POST)
     */
    public function updateFeaturedEvents(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $sectionModel = $this->model('HomepageSection');

        $seruEvents = isset($_POST['seru_events']) ? (array)$_POST['seru_events'] : [];
        $popEvents = isset($_POST['pop_events']) ? (array)$_POST['pop_events'] : [];

        $sectionModel->setFeaturedEvents('event_seru', $seruEvents);
        $sectionModel->setFeaturedEvents('event_populer', $popEvents);

        $this->logActivity('homepage.featured_update', 'featured_events', null, 'Memperbarui kurasi featured events Event Seru (' . count($seruEvents) . ' event) & Event Populer (' . count($popEvents) . ' event)');

        $this->flash('success', 'Kurasi daftar event homepage berhasil disimpan.');
        $this->redirect('dashboard/homepage/layout');
    }

    /* ================================================================
       BAGIAN A — PROMOSI (/dashboard/promotions)
       ================================================================ */

    /**
     * Halaman Daftar Kode Promo Tiket
     */
    public function promotions(): void {
        $this->authorize('dashboard.view');

        $promoModel = $this->model('Promo');
        $eventModel = $this->model('Event');

        $promos = $promoModel->allPromos();
        $events = $eventModel->all();
        $sidebarCounts = $this->getSidebarCounts();

        // Hitung status otomatis untuk setiap promo
        foreach ($promos as &$p) {
            $p['calculated_status'] = $promoModel->calculateStatus($p);
        }
        unset($p);

        $this->view('dashboard/promotions', [
            'pageTitle'     => 'NOVA LIVE — Kode Promo',
            'activeNav'     => 'promotions',
            'activeMenu'    => 'promosi',
            'activeTab'     => 'promotions',
            'promos'        => $promos,
            'events'        => $events,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Tambah Kode Promo Baru (POST)
     */
    public function storePromotion(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $code = strtoupper(preg_replace('/\s+/', '', trim($_POST['code'] ?? '')));
        $type = in_array($_POST['type'] ?? '', ['persen', 'nominal'], true) ? $_POST['type'] : 'persen';
        $value = (float)($_POST['value'] ?? 0);
        $eventId = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;
        $quota = !empty($_POST['quota']) ? (int)$_POST['quota'] : null;
        $startsAt = !empty($_POST['starts_at']) ? trim($_POST['starts_at']) : null;
        $endsAt = !empty($_POST['ends_at']) ? trim($_POST['ends_at']) : null;

        $promoModel = $this->model('Promo');

        if ($code === '') {
            $this->flash('error', 'Kode promo tidak boleh kosong.', 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($promoModel->codeExists($code)) {
            $this->flash('error', "Kode promo '{$code}' sudah digunakan.", 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($type === 'persen' && ($value <= 0 || $value > 100)) {
            $this->flash('error', 'Persentase diskon harus bernilai antara 1 sampai 100%.', 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($type === 'nominal' && $value <= 0) {
            $this->flash('error', 'Nominal potongan diskon harus lebih besar dari Rp 0.', 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($startsAt && $endsAt && $endsAt < $startsAt) {
            $this->flash('error', 'Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.', 'error');
            $this->redirect('dashboard/promotions');
        }

        $id = $promoModel->create([
            'code'      => $code,
            'type'      => $type,
            'value'     => $value,
            'event_id'  => $eventId,
            'quota'     => $quota,
            'used'      => 0,
            'starts_at' => $startsAt,
            'ends_at'   => $endsAt,
            'is_active' => 1
        ]);

        $this->logActivity('promo.create', 'promo_codes', $id, "Menambahkan kode promo '{$code}' ({$type}: {$value})");

        $this->flash('success', "Kode promo '{$code}' berhasil ditambahkan.");
        $this->redirect('dashboard/promotions');
    }

    /**
     * Perbarui Kode Promo (POST)
     */
    public function updatePromotion(int $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $promoModel = $this->model('Promo');
        $promo = $promoModel->find($id);
        if (!$promo) {
            $this->flash('error', 'Kode promo tidak ditemukan.', 'error');
            $this->redirect('dashboard/promotions');
        }

        $code = strtoupper(preg_replace('/\s+/', '', trim($_POST['code'] ?? '')));
        $type = in_array($_POST['type'] ?? '', ['persen', 'nominal'], true) ? $_POST['type'] : 'persen';
        $value = (float)($_POST['value'] ?? 0);
        $eventId = !empty($_POST['event_id']) ? (int)$_POST['event_id'] : null;
        $quota = !empty($_POST['quota']) ? (int)$_POST['quota'] : null;
        $startsAt = !empty($_POST['starts_at']) ? trim($_POST['starts_at']) : null;
        $endsAt = !empty($_POST['ends_at']) ? trim($_POST['ends_at']) : null;

        if ($code === '') {
            $this->flash('error', 'Kode promo tidak boleh kosong.', 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($promoModel->codeExists($code, $id)) {
            $this->flash('error', "Kode promo '{$code}' sudah digunakan oleh promo lain.", 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($type === 'persen' && ($value <= 0 || $value > 100)) {
            $this->flash('error', 'Persentase diskon harus bernilai antara 1 sampai 100%.', 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($type === 'nominal' && $value <= 0) {
            $this->flash('error', 'Nominal potongan diskon harus lebih besar dari Rp 0.', 'error');
            $this->redirect('dashboard/promotions');
        }

        if ($startsAt && $endsAt && $endsAt < $startsAt) {
            $this->flash('error', 'Tanggal berakhir tidak boleh lebih awal dari tanggal mulai.', 'error');
            $this->redirect('dashboard/promotions');
        }

        $promoModel->update($id, [
            'code'      => $code,
            'type'      => $type,
            'value'     => $value,
            'event_id'  => $eventId,
            'quota'     => $quota,
            'starts_at' => $startsAt,
            'ends_at'   => $endsAt
        ]);

        $this->logActivity('promo.update', 'promo_codes', $id, "Memperbarui kode promo '{$code}'");

        $this->flash('success', "Kode promo '{$code}' berhasil diperbarui.");
        $this->redirect('dashboard/promotions');
    }

    /**
     * Ubah Status Aktif / Nonaktif Kode Promo (POST)
     */
    public function togglePromotion(int $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $promoModel = $this->model('Promo');
        $promo = $promoModel->find($id);
        if (!$promo) {
            $this->flash('error', 'Kode promo tidak ditemukan.', 'error');
            $this->redirect('dashboard/promotions');
        }

        $promoModel->toggleActive($id);
        $newStatus = empty($promo['is_active']) ? 'diaktifkan' : 'dinonaktifkan';

        $this->logActivity('promo.toggle', 'promo_codes', $id, "Mengubah status kode promo '{$promo['code']}' menjadi {$newStatus}");

        $this->flash('success', "Kode promo '{$promo['code']}' berhasil {$newStatus}.");
        $this->redirect('dashboard/promotions');
    }

    /**
     * Hapus Kode Promo (POST)
     */
    public function deletePromotion(int $id): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $promoModel = $this->model('Promo');
        $promo = $promoModel->find($id);
        if (!$promo) {
            $this->flash('error', 'Kode promo tidak ditemukan.', 'error');
            $this->redirect('dashboard/promotions');
        }

        $promoModel->delete($id);

        $this->logActivity('promo.delete', 'promo_codes', $id, "Menghapus kode promo '{$promo['code']}'");

        $this->flash('success', "Kode promo '{$promo['code']}' berhasil dihapus.");
        $this->redirect('dashboard/promotions');
    }

    /* ================================================================
       BAGIAN B — PENGATURAN (/dashboard/settings)
       ================================================================ */

    /**
     * Halaman Pengaturan (Tab: Profil Organisasi, Akun Saya, Kelola Pengguna)
     */
    public function settings(?string $tab = 'org'): void {
        $this->authorize('dashboard.view');

        $tab = in_array($tab, ['org', 'account', 'users'], true) ? $tab : 'org';
        $settingModel = $this->model('Setting');
        $settings = $settingModel->allSettings();
        $sidebarCounts = $this->getSidebarCounts();

        $userModel = $this->model('User');
        $users = [];
        if ($tab === 'users') {
            if (($_SESSION['user']['role'] ?? '') !== 'admin') {
                Security::abort(403, 'Hanya Administrator yang memiliki akses ke manajemen pengguna.');
            }
            $users = $userModel->allUsers();
        }

        // Ambil data user login terbaru dari database
        $currentUserId = (int)($_SESSION['user']['id'] ?? 1);
        $currentUserData = $userModel->find($currentUserId);
        if ($currentUserData) {
            unset($currentUserData['password']);
            $_SESSION['user'] = array_merge($_SESSION['user'] ?? [], $currentUserData);
        }

        $this->view('dashboard/settings', [
            'pageTitle'     => 'NOVA LIVE — Pengaturan & Kontak',
            'activeNav'     => 'settings',
            'activeMenu'    => 'pengaturan',
            'activeTab'     => $tab,
            'settings'      => $settings,
            'users'         => $users,
            'currentUser'   => $_SESSION['user'] ?? [],
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Simpan Perubahan Profil Organisasi & Kontak (POST)
     */
    public function updateOrgProfile(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $settingModel = $this->model('Setting');

        $keys = [
            'org_name',
            'contact_email',
            'contact_phone',
            'contact_city',
            'contact_address',
            'contact_instagram',
            'org_description'
        ];

        $data = [];
        foreach ($keys as $k) {
            if (isset($_POST[$k])) {
                $data[$k] = trim($_POST[$k]);
            }
        }

        // Khusus checkbox preferensi notifikasi
        foreach (['notif_new_order', 'notif_low_stock', 'notif_payout', 'notif_weekly_summary'] as $notifKey) {
            $data[$notifKey] = isset($_POST[$notifKey]) ? '1' : '0';
        }

        $settingModel->updateMultiple($data);

        $this->logActivity('settings.update', 'settings', null, 'Memperbarui profil organisasi dan kontak');

        $this->flash('success', 'Profil organisasi dan kontak berhasil disimpan.');
        $this->redirect('dashboard/settings');
    }

    /**
     * Shortcut Halaman Akun Saya
     */
    public function accountSettings(): void {
        $this->settings('account');
    }

    /**
     * Simpan Pembaruan Profil Akun Sendiri (POST)
     */
    public function updateAccount(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $userId = (int)($_SESSION['user']['id'] ?? 1);
        $userModel = $this->model('User');

        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');

        if ($fullName === '') {
            $this->flash('error', 'Nama lengkap tidak boleh kosong.', 'error');
            $this->redirect('dashboard/settings/account');
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Alamat email tidak valid.', 'error');
            $this->redirect('dashboard/settings/account');
        }

        if ($userModel->emailExists($email, $userId)) {
            $this->flash('error', 'Alamat email sudah digunakan oleh akun lain.', 'error');
            $this->redirect('dashboard/settings/account');
        }

        $updateData = [
            'full_name' => $fullName,
            'email'     => $email,
            'phone'     => $phone
        ];

        // Proses unggah foto profil
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] !== UPLOAD_ERR_NO_FILE) {
            try {
                $avatarPath = Upload::image('avatar', 'avatars');
                if ($avatarPath) {
                    $oldUser = $userModel->find($userId);
                    if (!empty($oldUser['avatar'])) {
                        Upload::delete($oldUser['avatar']);
                    }
                    $updateData['avatar'] = $avatarPath;
                }
            } catch (\Exception $e) {
                $this->flash('error', 'Gagal mengunggah foto profil: ' . $e->getMessage(), 'error');
                $this->redirect('dashboard/settings/account');
            }
        }

        $userModel->updateProfile($userId, $updateData);

        // Segarkan data sesi user
        $freshUser = $userModel->find($userId);
        unset($freshUser['password']);
        $_SESSION['user'] = array_merge($_SESSION['user'] ?? [], $freshUser);

        $this->logActivity('user.update_profile', 'users', $userId, "Memperbarui profil akun {$email}");

        $this->flash('success', 'Profil akun Anda berhasil diperbarui.');
        $this->redirect('dashboard/settings/account');
    }

    /**
     * Ganti Kata Sandi Akun Sendiri (POST)
     */
    public function updatePassword(): void {
        $this->authorize('dashboard.view');
        $this->validateCsrf();

        $userId = (int)($_SESSION['user']['id'] ?? 1);
        $userModel = $this->model('User');
        $user = $userModel->find($userId);

        $currentPassword = (string)($_POST['current_password'] ?? '');
        $newPassword = (string)($_POST['new_password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');

        if (!password_verify($currentPassword, $user['password'])) {
            $this->flash('error', 'Kata sandi saat ini yang Anda masukkan salah.', 'error');
            $this->redirect('dashboard/settings/account');
        }

        if (strlen($newPassword) < 8) {
            $this->flash('error', 'Kata sandi baru minimal harus 8 karakter.', 'error');
            $this->redirect('dashboard/settings/account');
        }

        if ($newPassword !== $confirmPassword) {
            $this->flash('error', 'Konfirmasi kata sandi baru tidak cocok.', 'error');
            $this->redirect('dashboard/settings/account');
        }

        $userModel->updatePassword($userId, $newPassword);

        $this->logActivity('user.change_password', 'users', $userId, 'Mengubah kata sandi akun');

        $this->flash('success', 'Kata sandi berhasil diubah.');
        $this->redirect('dashboard/settings/account');
    }

    /**
     * Shortcut Halaman Kelola Pengguna
     */
    public function userSettings(): void {
        $this->settings('users');
    }

    /**
     * Tambah Pengguna Baru (POST — Khusus Admin)
     */
    public function storeUser(): void {
        $this->authorize('dashboard.view');
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            Security::abort(403, 'Hanya Administrator yang berhak menambah pengguna.');
        }
        $this->validateCsrf();

        $fullName = trim($_POST['full_name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $role = in_array($_POST['role'] ?? '', ['admin', 'manager', 'staff'], true) ? $_POST['role'] : 'staff';
        $password = (string)($_POST['password'] ?? '');

        $userModel = $this->model('User');

        if ($fullName === '' || $email === '') {
            $this->flash('error', 'Nama lengkap dan email wajib diisi.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->flash('error', 'Format email tidak valid.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        if ($userModel->emailExists($email)) {
            $this->flash('error', "Email '{$email}' sudah terdaftar pada sistem.", 'error');
            $this->redirect('dashboard/settings/users');
        }

        if (strlen($password) < 8) {
            $this->flash('error', 'Kata sandi pengguna minimal 8 karakter.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        $newId = $userModel->createUser([
            'full_name' => $fullName,
            'email'     => $email,
            'phone'     => $phone,
            'role'      => $role,
            'password'  => $password,
            'is_active' => 1
        ]);

        $this->logActivity('user.create', 'users', $newId, "Menambahkan pengguna baru {$fullName} ({$email}, peran: {$role})");

        $this->flash('success', "Pengguna {$fullName} ({$role}) berhasil didaftarkan.");
        $this->redirect('dashboard/settings/users');
    }

    /**
     * Ubah Peran Pengguna (POST — Khusus Admin)
     */
    public function updateUserRole(int $id): void {
        $this->authorize('dashboard.view');
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            Security::abort(403, 'Hanya Administrator yang berhak mengubah peran pengguna.');
        }
        $this->validateCsrf();

        $currentUserId = (int)($_SESSION['user']['id'] ?? 1);
        if ($id === $currentUserId) {
            $this->flash('error', 'Anda tidak dapat mengubah peran akun Anda sendiri.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        $role = in_array($_POST['role'] ?? '', ['admin', 'manager', 'staff'], true) ? $_POST['role'] : null;
        if (!$role) {
            $this->flash('error', 'Pilihan peran pengguna tidak valid.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        $userModel = $this->model('User');
        $targetUser = $userModel->find($id);
        if (!$targetUser) {
            $this->flash('error', 'Pengguna tidak ditemukan.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        $userModel->updateRole($id, $role);

        $this->logActivity('user.change_role', 'users', $id, "Mengubah peran pengguna {$targetUser['email']} menjadi {$role}");

        $this->flash('success', "Peran pengguna {$targetUser['full_name']} berhasil diubah menjadi {$role}.");
        $this->redirect('dashboard/settings/users');
    }

    /**
     * Ubah Status Aktif / Nonaktif Pengguna (POST — Khusus Admin)
     */
    public function toggleUserActive(int $id): void {
        $this->authorize('dashboard.view');
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            Security::abort(403, 'Hanya Administrator yang berhak mengubah status akun pengguna.');
        }
        $this->validateCsrf();

        $currentUserId = (int)($_SESSION['user']['id'] ?? 1);
        if ($id === $currentUserId) {
            $this->flash('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        $userModel = $this->model('User');
        $targetUser = $userModel->find($id);
        if (!$targetUser) {
            $this->flash('error', 'Pengguna tidak ditemukan.', 'error');
            $this->redirect('dashboard/settings/users');
        }

        $userModel->toggleActive($id);
        $newStatus = empty($targetUser['is_active']) ? 'diaktifkan' : 'dinonaktifkan';

        $this->logActivity('user.toggle_status', 'users', $id, "Mengubah status akun pengguna {$targetUser['email']} menjadi {$newStatus}");

        $this->flash('success', "Akun pengguna {$targetUser['full_name']} berhasil {$newStatus}.");
        $this->redirect('dashboard/settings/users');
    }
}

