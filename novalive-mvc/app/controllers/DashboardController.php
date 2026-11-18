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
     * Halaman Ringkasan Dashboard (4 KPI, Pesanan Terbaru, Tiket Menipis)
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
        $lowTickets = $eventModel->lowTicketEvents(5);

        $this->view('dashboard/index', [
            'pageTitle'    => 'Dashboard Ringkasan — NOVA LIVE',
            'totalEvents'  => $totalEvents,
            'totalSold'    => $totalSold,
            'totalRevenue' => $totalRevenue,
            'totalOrders'  => $totalOrders,
            'recentOrders' => $recentOrders,
            'lowTickets'   => $lowTickets
        ]);
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
        $statusCounts = $orderModel->countsByStatus();
        $ordersData = $orderModel->paginate($filters, $page, 10);

        $this->view('dashboard/orders', array_merge($ordersData, [
            'pageTitle'    => 'Daftar Pesanan Tiket — NOVA LIVE',
            'statusCounts' => $statusCounts,
            'filters'      => $filters
        ]));
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
        $eventsData = $eventModel->paginate($filters, $page, 10);

        $this->view('dashboard/events', array_merge($eventsData, [
            'pageTitle' => 'Kelola Event Saya — NOVA LIVE',
            'filters'   => $filters
        ]));
    }
}
