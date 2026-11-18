# NOVA LIVE MVC

Aplikasi web penjualan tiket event berbasis PHP MVC murni dan MySQL, dibangun sesuai spesifikasi Modul 04 dan Modul 05 mata kuliah Pemrograman Web.

---

## 1. Arsitektur Aplikasi
Aplikasi mengadopsi pola MVC murni tanpa framework:
- **Model** (`app/models/`): Mengelola seluruh interaksi database menggunakan PDO Singleton dan prepared statements.
- **View** (`app/views/`): Merender HTML antarmuka pengguna dengan proteksi Anti-XSS melalui fungsi `e()`.
- **Controller** (`app/controllers/`): Mengatur alur logika bisnis, otorisasi RBAC, pemanggilan Model, dan pemilihan View.
- **Front Controller** (`public/index.php`): Satu-satunya pintu masuk aplikasi dengan proteksi CSRF otomatis pada setiap request HTTP POST.

---

## 2. Struktur Folder
```
novalive-mvc/
├── .env.example                <- Template konfigurasi lingkungan
├── .gitignore                  <- Konfigurasi pengabaian Git
├── README.md                   <- Dokumentasi arsitektur dan panduan
├── CARA_JALANKAN.txt           <- Panduan instalasi & checklist uji
├── app/
│   ├── config/config.php       <- Loader .env dan konstanta aplikasi
│   ├── core/
│   │   ├── App.php             <- Router /{controller}/{method}/{param}
│   │   ├── Controller.php      <- Base Controller
│   │   ├── Database.php        <- PDO Singleton (utf8mb4)
│   │   ├── Model.php           <- Base Model
│   │   ├── SoftDeletes.php     <- Trait Soft Delete & Restore
│   │   ├── Middleware.php      <- Matriks Otorisasi RBAC
│   │   ├── Security.php        <- Session Hardening & CSRF Guard
│   │   ├── Upload.php          <- Pengunggah gambar aman (finfo + getimagesize)
│   │   ├── Logger.php          <- Pencatatan log error JSON
│   │   └── helpers.php         <- Kumpulan fungsi pembantu global
│   ├── controllers/            <- HomeController, AuthController, EventsController, dll.
│   ├── models/                 <- Event, Category, City, User, Order, ActivityLog
│   └── views/                  <- Layout, Home, Auth, Events, Cities, Categories, Dashboard, Errors
├── bin/
│   └── seed-admin.php          <- CLI seeder akun admin
├── database/
│   └── novalive.sql            <- Skema database dan 10 data demo
├── public/
│   ├── index.php               <- Front Controller
│   ├── router.php              <- Router untuk PHP Built-in Server
│   ├── .htaccess               <- Konfigurasi Apache mod_rewrite
│   ├── assets/                 <- Gambar landmark 4K dan aset statis
│   └── uploads/                <- Folder penyimpanan berkas foto pengguna (PHP diblokir)
├── logs/                       <- Log error aplikasi
└── tests/
    └── run.php                 <- 27 Uji otomatis fungsionalitas & keamanan
```

---

## 3. Daftar Route
| Method | URL | Controller / Aksi | Keterangan |
|---|---|---|---|
| GET | `/` | `HomeController@index` | Beranda (Banner, Event Seru, Kategori, Populer, Kotamu) |
| GET | `/login` | `AuthController@login` | Halaman formulir login |
| POST | `/auth/authenticate` | `AuthController@authenticate` | Proses verifikasi login (CSRF Protected) |
| GET | `/auth/logout` | `AuthController@logout` | Keluar dari sesi pengguna |
| GET | `/kategori` | `CategoriesController@index` | Daftar 8 kategori event |
| GET | `/kota` | `CitiesController@index` | Daftar 5 kota tujuan |
| GET | `/kota/{slug}` | `CitiesController@show` | Halaman event khusus di kota terpilih |
| GET | `/events` | `EventsController@index` | Katalog event dengan filter & paginasi |
| GET | `/events/show/{id}` | `EventsController@show` | Halaman detail event & ketersediaan tiket |
| GET | `/events/create` | `EventsController@create` | Formulir tambah event (Admin/Manager) |
| POST | `/events/store` | `EventsController@store` | Simpan event baru (Admin/Manager) |
| GET | `/events/edit/{id}` | `EventsController@edit` | Formulir ubah event (Admin/Manager) |
| POST | `/events/update/{id}` | `EventsController@update` | Simpan perubahan event (Admin/Manager) |
| GET | `/events/delete/{id}` | `EventsController@delete` | Soft delete event (Admin/Manager) |
| GET | `/events/trash` | `EventsController@trash` | Daftar sampah event terhapus (Admin/Manager) |
| GET | `/events/restore/{id}` | `EventsController@restore` | Pulihkan event dari sampah (Hanya Admin) |
| GET | `/dashboard` | `DashboardController@index` | Ringkasan KPI & transaksi terbaru (Wajib Login) |
| GET | `/dashboard/orders` | `DashboardController@orders` | Manajemen pesanan tiket (Filter Status) |
| GET | `/dashboard/events` | `DashboardController@events` | Manajemen katalog event penyelenggara |

---

## 4. Akun Demo
Semua akun demo menggunakan kata sandi: `nova1234`
- **Admin**: `demo@novalive.id` (Hak akses penuh: kelola event, restore sampah, dan lihat seluruh laporan)
- **Manager**: `manager@novalive.id` (Hak akses kelola event & pesanan; tidak dapat merestore event)
- **Staff**: `staff@novalive.id` (Hak akses lihat dashboard, event, dan pesanan)

---

## 5. Cara Menjalankan Aplikasi
1. Buka XAMPP Control Panel dan pastikan service **MySQL** sedang berjalan.
2. Impor file skema database `database/novalive.sql` (otomatis membuat `db_novalive`).
3. Jalankan server lokal dari root folder `novalive-mvc`:
   ```bash
   php -S localhost:8000 -t public public/router.php
   ```
4. Buka peramban ke: `http://localhost:8000`

---

## 6. Pengujian Otomatis
Jalankan pengujian keamanan dan fungsionalitas:
```bash
php tests/run.php
```
Hasil yang diharapkan: **Lulus: 27 | Gagal: 0**
