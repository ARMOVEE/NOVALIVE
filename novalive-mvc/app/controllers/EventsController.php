<?php
/**
 * EventsController
 * Mengelola penjelajahan katalog event publik serta manajemen CRUD event bagi staf/admin
 */

class EventsController extends Controller {
    /**
     * Halaman Jelajahi Event dengan filter & paginasi
     */
    public function index(): void {
        $whitelistPerPage = [5, 10, 20, 50, 100];
        $perPageParam = (int)query_param('perPage', 10);
        $perPage = in_array($perPageParam, $whitelistPerPage, true) ? $perPageParam : 10;
        $page = max(1, (int)query_param('page', 1));

        $filters = [
            'search'   => query_param('search', ''),
            'category' => query_param('category', ''),
            'city'     => query_param('city', ''),
            'price'    => query_param('price', ''),
            'sort'     => query_param('sort', 'dateAsc')
        ];

        $eventModel = $this->model('Event');
        $categoryModel = $this->model('Category');
        $cityModel = $this->model('City');

        $result = $eventModel->paginate($filters, $page, $perPage);
        $categories = $categoryModel->all();
        $cities = $cityModel->all();

        $this->view('events/index', array_merge($result, [
            'pageTitle'  => 'Jelajahi Event — NOVA LIVE',
            'filters'    => $filters,
            'categories' => $categories,
            'cities'     => $cities
        ]));
    }

    /**
     * Detail satu event publik
     */
    public function show($id = null): void {
        if (empty($id) || !is_numeric($id)) {
            App::notFound('Event tidak ditemukan.');
        }

        $eventModel = $this->model('Event');
        $event = $eventModel->find((int)$id);

        if (!$event) {
            App::notFound('Event tidak ditemukan.');
        }

        $related = $event['city_id'] ? $eventModel->byCity((int)$event['city_id'], 4) : [];

        $this->view('events/show', [
            'pageTitle' => e($event['title']) . ' — NOVA LIVE',
            'event'     => $event,
            'related'   => $related
        ]);
    }

    /**
     * Form tambah event baru (Admin/Manager)
     */
    public function create(): void {
        $this->authorize('events.create');

        $categories = $this->model('Category')->all();
        $cities = $this->model('City')->all();
        $nextCode = $this->model('Event')->generateCode();

        $this->view('events/create', [
            'pageTitle'  => 'Tambah Event Baru — NOVA LIVE',
            'categories' => $categories,
            'cities'     => $cities,
            'nextCode'   => $nextCode,
            'event'      => null
        ]);
    }

    /**
     * Menyimpan event baru ke database (POST)
     */
    public function store(): void {
        $this->authorize('events.create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('events/create');
        }

        $payload = $this->normalizePayload();

        // Validasi kolom wajib
        if (empty($payload['title']) || empty($payload['organizer']) || empty($payload['event_date'])) {
            $this->flash('error', 'Judul, penyelenggara, dan tanggal event wajib diisi.');
            $this->redirect('events/create');
        }

        // Upload foto jika ada
        try {
            $photoPath = Upload::image('photo');
            if ($photoPath) {
                $payload['photo'] = $photoPath;
            }
        } catch (Exception $e) {
            $this->flash('error', $e->getMessage());
            $this->redirect('events/create');
        }

        $eventModel = $this->model('Event');
        $newId = $eventModel->create($payload);

        $this->logActivity('events.create', 'events', $newId, 'Menambahkan event baru: ' . $payload['title']);
        $this->flash('success', 'Event "' . $payload['title'] . '" berhasil ditambahkan.');
        $this->redirect('dashboard/events');
    }

    /**
     * Form edit event (Admin/Manager)
     */
    public function edit($id = null): void {
        $this->authorize('events.update');

        if (empty($id) || !is_numeric($id)) {
            App::notFound('Event tidak ditemukan.');
        }

        $eventModel = $this->model('Event');
        $event = $eventModel->find((int)$id);

        if (!$event) {
            $this->flash('error', 'Event yang akan diubah tidak ditemukan.');
            $this->redirect('dashboard/events');
        }

        $categories = $this->model('Category')->all();
        $cities = $this->model('City')->all();

        $this->view('events/edit', [
            'pageTitle'  => 'Ubah Event: ' . e($event['title']) . ' — NOVA LIVE',
            'event'      => $event,
            'categories' => $categories,
            'cities'     => $cities
        ]);
    }

    /**
     * Memperbarui data event (POST)
     */
    public function update($id = null): void {
        $this->authorize('events.update');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($id) || !is_numeric($id)) {
            $this->redirect('dashboard/events');
        }

        $eventModel = $this->model('Event');
        $existing = $eventModel->find((int)$id);

        if (!$existing) {
            $this->flash('error', 'Event tidak ditemukan.');
            $this->redirect('dashboard/events');
        }

        $payload = $this->normalizePayload();

        // Validasi kolom wajib
        if (empty($payload['title']) || empty($payload['organizer']) || empty($payload['event_date'])) {
            $this->flash('error', 'Judul, penyelenggara, dan tanggal event wajib diisi.');
            $this->redirect('events/edit/' . $id);
        }

        // Kelola penggantian atau penghapusan foto
        $photo = $existing['photo']; // Default: pertahankan foto lama
        $removePhoto = post_input('remove_photo') === '1';

        if ($removePhoto) {
            Upload::delete($existing['photo']);
            $photo = null;
        } else {
            try {
                $newPhoto = Upload::image('photo');
                if ($newPhoto) {
                    Upload::delete($existing['photo']); // Hapus file lama hanya jika berkas baru valid
                    $photo = $newPhoto;
                }
            } catch (Exception $e) {
                $this->flash('error', $e->getMessage());
                $this->redirect('events/edit/' . $id);
            }
        }

        $payload['photo'] = $photo;

        $eventModel->update((int)$id, $payload);
        $this->logActivity('events.update', 'events', (int)$id, 'Memperbarui event: ' . $payload['title']);

        $this->flash('success', 'Perubahan event "' . $payload['title'] . '" berhasil disimpan.');
        $this->redirect('dashboard/events');
    }

    /**
     * Soft delete event
     */
    public function delete($id = null): void {
        $this->authorize('events.delete');

        if (empty($id) || !is_numeric($id)) {
            App::notFound('Event tidak ditemukan.');
        }

        $eventModel = $this->model('Event');
        $event = $eventModel->find((int)$id);

        if (!$event) {
            $this->flash('error', 'Event tidak ditemukan.');
            $this->redirect('dashboard/events');
        }

        $eventModel->delete((int)$id);
        $this->logActivity('events.delete', 'events', (int)$id, 'Menghapus event (soft delete): ' . $event['title']);

        $this->flash('success', 'Event "' . $event['title'] . '" berhasil dipindahkan ke Sampah.');
        $this->redirect('dashboard/events');
    }

    /**
     * Daftar event yang ada di Sampah (Soft Delete)
     */
    public function trash(): void {
        $this->authorize('events.delete');

        $eventModel = $this->model('Event');
        $trashEvents = $eventModel->trash();

        $this->view('events/trash', [
            'pageTitle'   => 'Sampah Event — NOVA LIVE',
            'trashEvents' => $trashEvents
        ]);
    }

    /**
     * Memulihkan event dari sampah (HANYA Admin)
     */
    public function restore($id = null): void {
        // Matriks RBAC ketat: restore hanya diizinkan untuk admin
        if (($_SESSION['user']['role'] ?? '') !== 'admin') {
            Security::abort(403, 'Hanya peran Administrator yang berhak memulihkan event dari sampah.');
        }

        if (empty($id) || !is_numeric($id)) {
            App::notFound('Event tidak ditemukan.');
        }

        $eventModel = $this->model('Event');
        $eventModel->restore((int)$id);

        $this->logActivity('events.restore', 'events', (int)$id, 'Memulihkan event ID: ' . $id);
        $this->flash('success', 'Event berhasil dipulihkan dari sampah.');
        $this->redirect('events/trash');
    }

    /**
     * Normalisasi payload formulir (string kosong diubah menjadi null agar relasi FK aman)
     */
    protected function normalizePayload(): array {
        $categoryId = post_input('category_id');
        $cityId = post_input('city_id');
        $badge = post_input('badge');
        $price = post_input('price', 0);
        $quota = post_input('quota', 0);
        $sold = post_input('sold', 0);

        return [
            'code'        => post_input('code'),
            'title'       => post_input('title'),
            'category_id' => !empty($categoryId) ? (int)$categoryId : null,
            'city_id'     => !empty($cityId) ? (int)$cityId : null,
            'organizer'   => post_input('organizer'),
            'event_date'  => post_input('event_date'),
            'event_time'  => post_input('event_time') ?: null,
            'venue'       => post_input('venue') ?: null,
            'price'       => max(0, (float)$price),
            'quota'       => max(0, (int)$quota),
            'sold'        => max(0, (int)$sold),
            'badge'       => !empty($badge) ? $badge : null,
            'description' => post_input('description') ?: null
        ];
    }
}
