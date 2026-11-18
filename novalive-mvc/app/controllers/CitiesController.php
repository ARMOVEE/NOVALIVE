<?php
/**
 * CitiesController
 * Menangani daftar kota ("Kategori Kotamu") dan halaman spesifik tiap kota ("Event Menarik di ...")
 */

class CitiesController extends Controller {
    /**
     * Menampilkan daftar 5 kota utama
     */
    public function index(): void {
        $cityModel = $this->model('City');
        $cities = $cityModel->all();

        $this->view('cities/index', [
            'pageTitle' => 'Jelajahi Event di Kotamu — NOVA LIVE',
            'cities'    => $cities
        ]);
    }

    /**
     * Menampilkan halaman event di kota terpilih
     * Mendukung URL segmen /kota/jakarta serta query string ?q=Jakarta, ?kota=Denpasar, dll.
     */
    public function show($slugOrQuery = null): void {
        $target = $slugOrQuery;

        if (empty($target)) {
            $target = query_param('q') ?? query_param('kota') ?? query_param('city') ?? query_param('teritory');
        }

        if (empty($target)) {
            $this->redirect('kota');
        }

        $cityModel = $this->model('City');
        $city = $cityModel->findBySlug($target);

        // Jika tidak ditemukan dengan slug, coba pencocokan nama atau alias (contoh: Bali -> Denpasar)
        if (!$city) {
            $city = $cityModel->findByName($target);
        }

        if (!$city) {
            App::notFound("Kota '{$target}' tidak ditemukan atau belum didukung.");
        }

        $eventModel = $this->model('Event');
        $events = $eventModel->byCity((int)$city['id']);

        $this->view('cities/show', [
            'pageTitle' => 'Event Menarik di ' . strtoupper($city['name']) . ' — NOVA LIVE',
            'city'      => $city,
            'events'    => $events
        ]);
    }
}
