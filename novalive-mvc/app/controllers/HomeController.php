<?php
/**
 * HomeController
 * Menangani halaman utama (beranda) NOVA LIVE
 */

class HomeController extends Controller {
    public function index(): void {
        $eventModel = $this->model('Event');
        $categoryModel = $this->model('Category');
        $cityModel = $this->model('City');
        $bannerModel = $this->model('Banner');
        $sectionModel = $this->model('HomepageSection');

        // Mengambil banners aktif terurut
        $banners = [];
        try {
            $banners = $bannerModel->allActive();
        } catch (\Throwable $e) {
            $banners = [];
        }

        // Mengambil konfigurasi tata letak homepage
        $sections = [];
        try {
            $sections = $sectionModel->allSections();
        } catch (\Throwable $e) {
            $sections = [];
        }

        // Fallback default sections jika tabel belum ada atau kosong
        if (empty($sections)) {
            $sections = [
                'event_seru'    => ['section_key' => 'event_seru', 'title' => 'Event Seru Untukmu', 'is_visible' => 1, 'sort_order' => 1, 'item_limit' => 10],
                'kategori'      => ['section_key' => 'kategori', 'title' => 'Kategori Event', 'is_visible' => 1, 'sort_order' => 2, 'item_limit' => 8],
                'event_populer' => ['section_key' => 'event_populer', 'title' => 'Event Populer', 'is_visible' => 1, 'sort_order' => 3, 'item_limit' => 4],
                'jelajahi_kota' => ['section_key' => 'jelajahi_kota', 'title' => 'Jelajahi Event di Kotamu', 'is_visible' => 1, 'sort_order' => 4, 'item_limit' => 6]
            ];
        }

        $seruLimit = (int)($sections['event_seru']['item_limit'] ?? 10) ?: 10;
        $popularLimit = (int)($sections['event_populer']['item_limit'] ?? 4) ?: 4;

        // Ambil event seru: terkurasi jika ada, jika kosong otomatis query terdekat
        $eventsSeru = [];
        try {
            $eventsSeru = $sectionModel->getSectionEvents('event_seru', $seruLimit, $eventModel);
        } catch (\Throwable $e) {
            $eventsSeru = $eventModel->seru($seruLimit);
        }
        if (empty($eventsSeru)) {
            $eventsSeru = $eventModel->seru($seruLimit);
        }

        // Ambil event populer: terkurasi jika ada, jika kosong otomatis query 4 event terdekat
        $eventsPopular = [];
        try {
            $eventsPopular = $sectionModel->getSectionEvents('event_populer', $popularLimit, $eventModel);
        } catch (\Throwable $e) {
            $eventsPopular = $eventModel->upcoming($popularLimit);
        }
        if (empty($eventsPopular)) {
            $eventsPopular = $eventModel->upcoming($popularLimit);
        }

        $categories = $categoryModel->all();
        $cities = $cityModel->all();

        $this->view('home/index', [
            'pageTitle'     => 'NOVA LIVE — Platform Tiket Event Indonesia',
            'banners'       => $banners,
            'sections'      => $sections,
            'eventsSeru'    => $eventsSeru,
            'eventsPopular' => $eventsPopular,
            'categories'    => $categories,
            'cities'        => $cities
        ]);
    }
}
