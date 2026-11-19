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

        return [
            'events' => $eventModel->totalEvents(),
            'orders' => $orderModel->count(),
            'refund' => 2
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
        $upcomingEvents = $eventModel->seru(4);

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
     * Sub-tab Check-in Tiket (Menu Pesanan)
     */
    public function checkin(): void {
        $this->authorize('orders.view');

        $eventModel = $this->model('Event');
        $orderModel = $this->model('Order');

        $events = $eventModel->all();
        $orders = $orderModel->all();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/checkin', [
            'pageTitle'     => 'NOVA LIVE — Check-in Tiket',
            'activeNav'     => 'orders',
            'activeMenu'    => 'pesanan',
            'activeTab'     => 'checkin',
            'events'        => $events,
            'orders'        => $orders,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Sub-tab Refund / Pengembalian Dana (Menu Pesanan)
     */
    public function refunds(): void {
        $this->authorize('orders.view');

        $orderModel = $this->model('Order');
        $refundOrders = $orderModel->paginate(['status' => 'Dibatalkan'], 1, 20);
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/refunds', [
            'pageTitle'     => 'NOVA LIVE — Refund & Pengembalian Dana',
            'activeNav'     => 'orders',
            'activeMenu'    => 'pesanan',
            'activeTab'     => 'refund',
            'refundOrders'  => $refundOrders['data'] ?? [],
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Sub-tab E-Tiket / Dokumen (Menu Pesanan & Topbar Dokumen)
     */
    public function tickets(): void {
        $this->authorize('orders.view');

        $orderModel = $this->model('Order');
        $eventModel = $this->model('Event');

        $orders = $orderModel->recent(20);
        $events = $eventModel->all();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/tickets', [
            'pageTitle'     => 'NOVA LIVE — Pratinjau E-Tiket',
            'activeNav'     => 'tickets',
            'activeMenu'    => 'pesanan',
            'activeTab'     => 'etiket',
            'orders'        => $orders,
            'events'        => $events,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 4. Menu Keuangan (Saldo & Pencairan Dana)
     */
    public function finance(): void {
        $this->authorize('dashboard.view');

        $orderModel = $this->model('Order');
        $totalRevenue = $orderModel->totalRevenue();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/finance', [
            'pageTitle'     => 'NOVA LIVE — Keuangan & Pencairan Dana',
            'activeNav'     => 'finance',
            'activeMenu'    => 'keuangan',
            'activeTab'     => 'finance',
            'totalRevenue'  => $totalRevenue,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 5. Menu Homepage (Sub-tab Banner)
     */
    public function homepage(): void {
        $this->authorize('dashboard.view');

        $eventModel = $this->model('Event');
        $events = $eventModel->all();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/homepage', [
            'pageTitle'     => 'NOVA LIVE — Banner Homepage',
            'activeNav'     => 'homepage',
            'activeMenu'    => 'homepage',
            'activeTab'     => 'banner',
            'events'        => $events,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Menu Homepage (Sub-tab Tampilan Homepage: Seru & Populer)
     */
    public function homepageDisplay(): void {
        $this->authorize('dashboard.view');

        $eventModel = $this->model('Event');
        $events = $eventModel->all();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/homepage', [
            'pageTitle'     => 'NOVA LIVE — Tampilan Homepage',
            'activeNav'     => 'homepage',
            'activeMenu'    => 'homepage',
            'activeTab'     => 'tampilan',
            'events'        => $events,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 6. Menu Laporan (Sub-tab Analitik / Topbar Laporan)
     */
    public function reports(): void {
        $this->authorize('dashboard.view');

        $categoryModel = $this->model('Category');
        $cityModel = $this->model('City');
        $categories = $categoryModel->allWithCount();
        $cities = $cityModel->allWithCount();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/reports', [
            'pageTitle'     => 'NOVA LIVE — Analitik & Laporan',
            'activeNav'     => 'reports',
            'activeMenu'    => 'laporan',
            'activeTab'     => 'analitik',
            'categories'    => $categories,
            'cities'        => $cities,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Sub-tab Log Aktivitas (Menu Laporan / Topbar Riwayat)
     */
    public function reportLogs(): void {
        $this->authorize('dashboard.view');

        $activityLogModel = $this->model('ActivityLog');
        $page = max(1, (int)query_param('page', 1));
        $logs = $activityLogModel->paginate($page, 20);
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/reports', [
            'pageTitle'     => 'NOVA LIVE — Log Aktivitas Admin',
            'activeNav'     => 'logs',
            'activeMenu'    => 'laporan',
            'activeTab'     => 'logs',
            'logs'          => $logs['data'] ?? [],
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * Sub-tab Notifikasi ke Pembeli (Menu Laporan)
     */
    public function reportNotifications(): void {
        $this->authorize('dashboard.view');

        $eventModel = $this->model('Event');
        $events = $eventModel->all();
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/reports', [
            'pageTitle'     => 'NOVA LIVE — Notifikasi ke Pembeli',
            'activeNav'     => 'reports',
            'activeMenu'    => 'laporan',
            'activeTab'     => 'notifikasi',
            'events'        => $events,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 7. Menu Promosi (Kode Promo)
     */
    public function promotions(): void {
        $this->authorize('dashboard.view');

        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/promotions', [
            'pageTitle'     => 'NOVA LIVE — Kode Promo & Promosi',
            'activeNav'     => 'promotions',
            'activeMenu'    => 'promosi',
            'activeTab'     => 'promotions',
            'sidebarCounts' => $sidebarCounts
        ], false);
    }

    /**
     * 8. Menu Pengaturan (Profil Penyelenggara / Topbar Kontak)
     */
    public function settings(): void {
        $this->authorize('dashboard.view');

        $currentUser = $_SESSION['user'] ?? ['full_name' => 'Admin NOVA LIVE', 'role' => 'admin'];
        $sidebarCounts = $this->getSidebarCounts();

        $this->view('dashboard/settings', [
            'pageTitle'     => 'NOVA LIVE — Pengaturan & Kontak Penyelenggara',
            'activeNav'     => 'settings',
            'activeMenu'    => 'pengaturan',
            'activeTab'     => 'settings',
            'currentUser'   => $currentUser,
            'sidebarCounts' => $sidebarCounts
        ], false);
    }
}
