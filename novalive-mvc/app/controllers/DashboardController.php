<?php
/**
 * DashboardController
 * Menangani panel administratif penyelenggara (Ringkasan KPI, Manajemen Pesanan, dan Manajemen Event)
 */

class DashboardController extends Controller {
    public function __construct() {
        $this->requireLogin();
    }

    /**
     * Halaman Ringkasan Dashboard (4 KPI, Grafik Penjualan, Event Terlaris, Event Berikutnya, Transaksi Terbaru)
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

        $sidebarCounts = [
            'events' => $totalEvents,
            'orders' => $totalOrders,
            'refund' => 2
        ];

        $this->view('dashboard/index', [
            'pageTitle'      => 'NOVA LIVE — Dashboard Admin',
            'activeNav'      => 'ringkasan',
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
     * Halaman Manajemen Pesanan Tiket (Filter Status & Paginasi)
     */
    public function orders(): void {
        $this->authorize('orders.view');

        $page = max(1, (int)query_param('page', 1));
        $filters = [
            'status' => query_param('status', ''),
            'search' => query_param('search', '')
        ];

        $orderModel = $this->model('Order');
        $eventModel = $this->model('Event');
        $statusCounts = $orderModel->countsByStatus();
        $ordersData = $orderModel->paginate($filters, $page, 15);

        $totalEvents = $eventModel->totalEvents();
        $totalOrders = $orderModel->count();
        $sidebarCounts = [
            'events' => $totalEvents,
            'orders' => $totalOrders,
            'refund' => 2
        ];

        $this->view('dashboard/orders', array_merge($ordersData, [
            'pageTitle'     => 'NOVA LIVE — Pesanan Tiket',
            'activeNav'     => 'orders',
            'statusCounts'  => $statusCounts,
            'filters'       => $filters,
            'sidebarCounts' => $sidebarCounts
        ]), false);
    }

    /**
     * Halaman Manajemen Event Saya (Pencarian & Kelola Event)
     */
    public function events(): void {
        $this->authorize('events.view');

        $page = max(1, (int)query_param('page', 1));
        $filters = [
            'search' => query_param('search', '')
        ];

        $eventModel = $this->model('Event');
        $orderModel = $this->model('Order');
        $eventsData = $eventModel->paginate($filters, $page, 15);

        $totalEvents = $eventModel->totalEvents();
        $totalOrders = $orderModel->count();
        $sidebarCounts = [
            'events' => $totalEvents,
            'orders' => $totalOrders,
            'refund' => 2
        ];

        $this->view('dashboard/events', array_merge($eventsData, [
            'pageTitle'     => 'NOVA LIVE — Kelola Event',
            'activeNav'     => 'events',
            'filters'       => $filters,
            'sidebarCounts' => $sidebarCounts
        ]), false);
    }
}

