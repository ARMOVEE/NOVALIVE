-- ==============================================================
-- DATABASE SKEMA & DATA DEMO: NOVA LIVE MVC
-- Platform Penjualan Tiket Event (PHP MVC + MySQL)
-- ==============================================================

CREATE DATABASE IF NOT EXISTS `db_novalive` 
CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `db_novalive`;

-- --------------------------------------------------------------
-- 1. TABEL USERS (Akun Penyelenggara & Staf)
-- Menyimpan kredensial otentikasi dan peran (RBAC) pengguna
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `username` VARCHAR(50) UNIQUE NOT NULL,
  `email` VARCHAR(100) UNIQUE NOT NULL,
  `password` VARCHAR(255) NOT NULL COMMENT 'Hash bcrypt',
  `full_name` VARCHAR(100) NOT NULL,
  `role` ENUM('admin','manager','staff') DEFAULT 'staff',
  `is_active` TINYINT(1) DEFAULT 1,
  `last_login_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- 2. TABEL CATEGORIES (8 Kategori Event)
-- Mengelompokkan event ke dalam kategori dengan dukungan soft delete
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) UNIQUE NOT NULL,
  `description` VARCHAR(255) NULL,
  `icon` VARCHAR(50) NULL,
  `color` VARCHAR(7) NULL,
  `deleted_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_categories_deleted_at` (`deleted_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- 3. TABEL CITIES (5 Kota Utama)
-- Master data kota tujuan event dan ikon landmark
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `cities` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) UNIQUE NOT NULL,
  `slug` VARCHAR(50) UNIQUE NOT NULL,
  `landmark` VARCHAR(100) NULL,
  `accent` VARCHAR(7) NULL,
  `search_alias` VARCHAR(50) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- 4. TABEL EVENTS (Katalog Event & Tiket)
-- Menyimpan data detail event, kuota, harga tiket, dan foto poster
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `events` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `code` VARCHAR(20) UNIQUE NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `category_id` INT NULL,
  `city_id` INT NULL,
  `organizer` VARCHAR(100) NOT NULL,
  `event_date` DATE NOT NULL,
  `event_time` VARCHAR(20) NULL,
  `venue` VARCHAR(150) NULL,
  `price` DECIMAL(15,2) DEFAULT 0,
  `quota` INT DEFAULT 0,
  `sold` INT DEFAULT 0,
  `photo` VARCHAR(255) NULL,
  `badge` VARCHAR(20) NULL COMMENT 'BARU / Tinggal Sedikit',
  `description` TEXT NULL,
  `deleted_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_events_deleted_at` (`deleted_at`),
  INDEX `idx_events_category` (`category_id`),
  INDEX `idx_events_city` (`city_id`),
  CONSTRAINT `fk_events_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_events_city` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- 5. TABEL ORDERS (Transaksi Pesanan Tiket)
-- Mencatat pembelian tiket oleh pengguna dan status tiket
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `invoice` VARCHAR(20) UNIQUE NOT NULL,
  `event_id` INT NOT NULL,
  `buyer_name` VARCHAR(100) NOT NULL,
  `buyer_email` VARCHAR(100) NOT NULL,
  `qty` INT DEFAULT 1,
  `total_price` DECIMAL(15,2) DEFAULT 0,
  `status` ENUM('Terkirim','Menunggu','Selesai','Dibatalkan') DEFAULT 'Menunggu',
  `ticket_code` VARCHAR(20) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_orders_event` (`event_id`),
  INDEX `idx_orders_status` (`status`),
  CONSTRAINT `fk_orders_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- 6. TABEL ACTIVITY_LOGS (Audit Trail Aktivitas)
-- Mencatat seluruh riwayat tindakan administratif (login, modifikasi data)
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `activity_logs` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NULL,
  `action` VARCHAR(50) NOT NULL,
  `entity` VARCHAR(50) NULL,
  `entity_id` INT NULL,
  `description` VARCHAR(255) NULL,
  `ip_address` VARCHAR(45) NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_activity_created_at` (`created_at`),
  INDEX `idx_activity_action` (`action`),
  CONSTRAINT `fk_activity_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------------
-- 7. TABEL API_TOKENS (Token Akses API)
-- Menyimpan hash SHA-256 token API untuk integrasi sistem
-- --------------------------------------------------------------
CREATE TABLE IF NOT EXISTS `api_tokens` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT NOT NULL,
  `token_hash` CHAR(64) UNIQUE NOT NULL,
  `name` VARCHAR(100) DEFAULT 'default',
  `last_used_at` DATETIME NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_api_tokens_user` (`user_id`),
  CONSTRAINT `fk_api_tokens_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ==============================================================
-- DATA DEMO SEEDING (Sesuai Spesifikasi Prompt Bagian 4)
-- ==============================================================

-- Seed Users (Password: nova1234)
INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `role`, `is_active`) VALUES
(1, 'admin', 'demo@novalive.id', '$2y$10$L5Ub6xal70cWjgVzUv4lNe4fhh7DDyhUm5VI23BsIIXUI.yknSdLy', 'Admin NOVA LIVE', 'admin', 1),
(2, 'manager1', 'manager@novalive.id', '$2y$10$L5Ub6xal70cWjgVzUv4lNe4fhh7DDyhUm5VI23BsIIXUI.yknSdLy', 'Manager NOVA LIVE', 'manager', 1),
(3, 'staff1', 'staff@novalive.id', '$2y$10$L5Ub6xal70cWjgVzUv4lNe4fhh7DDyhUm5VI23BsIIXUI.yknSdLy', 'Staff NOVA LIVE', 'staff', 1)
ON DUPLICATE KEY UPDATE `full_name` = VALUES(`full_name`);

-- Seed Categories (8 Kategori)
INSERT INTO `categories` (`id`, `name`, `description`, `icon`, `color`) VALUES
(1, 'Musik', 'Konser musik live dan festival panggung megah', 'music', '#0E3254'),
(2, 'Pameran', 'Eksibisi seni kontemporer dan galeri visual', 'palette', '#23649A'),
(3, 'Teater', 'Pertunjukan drama musikal dan panggung teater', 'theater', '#509FC8'),
(4, 'Olahraga', 'Turnamen kejuaraan dan ajang lari bersama', 'trophy', '#14B8A6'),
(5, 'Wisata', 'Eksplorasi destinasi liburan dan wahana rekreasi', 'compass', '#F97316'),
(6, 'Talkshow', 'Bincang inspiratif bersama praktisi profesional', 'mic', '#3B82F6'),
(7, 'Workshop', 'Pelatihan keterampilan dan kelas praktek langsung', 'tool', '#EC4899'),
(8, 'Kompetisi', 'Perlombaan bakat, gaming, dan uji ketangkasan', 'award', '#D4B475')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Seed Cities (5 Kota)
INSERT INTO `cities` (`id`, `name`, `slug`, `landmark`, `accent`, `search_alias`) VALUES
(1, 'Jakarta', 'jakarta', 'Monas', '#F97316', NULL),
(2, 'Denpasar', 'denpasar', 'Bajra Sandhi', '#14B8A6', 'Bali'),
(3, 'Bandung', 'bandung', 'Gedung Sate', '#3B82F6', NULL),
(4, 'Surabaya', 'surabaya', 'Patung Sura & Baya', '#22C55E', NULL),
(5, 'Yogyakarta', 'yogyakarta', 'Tugu Yogyakarta', '#EC4899', 'Jogja')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- Seed Events (10 Event Demo)
INSERT INTO `events` (`id`, `code`, `title`, `category_id`, `city_id`, `organizer`, `event_date`, `event_time`, `venue`, `price`, `quota`, `sold`, `photo`, `badge`, `description`) VALUES
(1, 'EVT001', 'Midnight Jazz Session', 1, 1, 'The Blue Note Quartet', '2026-11-14', '19:00', 'The Grand Hall, Jakarta', 150000.00, 500, 320, 'assets/midnight-jazz.jpg', NULL, 'Malam intim penuh melodi jazz klasik bersama kuartet musisi ternama.'),
(2, 'EVT002', 'Tulus Live in Concert', 1, 1, 'Tulus', '2026-12-06', '19:00', 'Istora Senayan, Jakarta', 450000.00, 5000, 4600, 'assets/tulus.jpg', 'Tinggal Sedikit', 'Penampilan spektakuler Tulus membawakan lagu-lagu hits penuh emosi.'),
(3, 'EVT003', 'Nadin Amizah: Intimate Session', 1, 3, 'Nadin Amizah', '2026-12-11', '19:30', 'The Hall, Bandung', 350000.00, 800, 410, 'assets/nadin-amizah.jpg', NULL, 'Konser hangat dengan aransemen puitis khas Nadin Amizah di Kota Bandung.'),
(4, 'EVT004', 'Ghea Indrawari Live', 1, 5, 'Ghea Indrawari', '2026-12-19', '19:00', 'Jogja Expo Center, Yogyakarta', 300000.00, 2000, 900, 'assets/ghea.jpg', NULL, 'Penampilan manis vokal penuh penghayatan Ghea Indrawari di Yogyakarta.'),
(5, 'EVT005', 'Festival Balon Udara Bali', 5, 2, 'NOVA LIVE', '2027-01-16', '06:00', 'Lapangan Nusa Dua, Bali', 200000.00, 1000, 850, 'assets/balon-udara.jpg', 'Tinggal Sedikit', 'Pemandangan spektakuler puluhan balon udara warna-warni di langit Nusa Dua.'),
(6, 'EVT006', 'Final Kejuaraan Basket Nasional', 4, 4, 'DBL Indonesia', '2027-01-23', '15:00', 'DBL Arena, Surabaya', 100000.00, 3000, 1200, 'assets/basket.jpg', NULL, 'Pertandingan puncak memperebutkan gelar juara basket bergengsi tahun ini.'),
(7, 'EVT007', 'Galeri Warna', 2, 1, 'Galeri Nasional', '2026-11-15', '10:00', 'Galeri Nasional, Jakarta', 50000.00, 2000, 600, 'assets/galeri-warna.jpg', 'BARU', 'Eksibisi seni kontemporer menampilkan ragam instalasi warna berani.'),
(8, 'EVT008', 'Pameran Fotografi Analog', 2, 5, 'Komunitas Analog Jogja', '2026-11-28', '09:00', 'Taman Budaya, Yogyakarta', 35000.00, 300, 180, 'assets/fotografi.jpg', 'BARU', 'Koleksi jepretan film seluloid dari fotografer muda se-Nusantara.'),
(9, 'EVT009', 'Karnaval Kincir Ria Ancol', 5, 1, 'Ancol', '2026-12-20', '16:00', 'Ancol, Jakarta', 125000.00, 5000, 2100, 'assets/kincir-ria.jpg', NULL, 'Festival keluarga meriah dengan wahana kincir ria dan pesta kembang api.'),
(10, 'EVT010', 'Fun Run 10K', 4, 1, 'NOVA Sports', '2026-12-13', '06:00', 'GBK, Jakarta', 150000.00, 1000, 750, 'assets/fun-run.jpg', NULL, 'Lari santai 10 kilometer mengelilingi kawasan hijau Gelora Bung Karno.')
ON DUPLICATE KEY UPDATE `title` = VALUES(`title`);

-- Seed Orders (8 Pesanan Demo)
INSERT INTO `orders` (`id`, `invoice`, `event_id`, `buyer_name`, `buyer_email`, `qty`, `total_price`, `status`, `ticket_code`) VALUES
(1, 'NL-88001', 2, 'Andi Pratama', 'andi@mail.com', 2, 900000.00, 'Terkirim', 'NVL-A1B2C3'),
(2, 'NL-88002', 1, 'Budi Santoso', 'budi@mail.com', 1, 150000.00, 'Selesai', 'NVL-D4E5F6'),
(3, 'NL-88003', 3, 'Citra Lestari', 'citra@mail.com', 2, 700000.00, 'Menunggu', 'NVL-G7H8I9'),
(4, 'NL-88004', 5, 'Dewi Anggraini', 'dewi@mail.com', 4, 800000.00, 'Terkirim', 'NVL-J1K2L3'),
(5, 'NL-88005', 2, 'Eko Wijaya', 'eko@mail.com', 1, 450000.00, 'Menunggu', 'NVL-M4N5O6'),
(6, 'NL-88006', 6, 'Fajar Nugroho', 'fajar@mail.com', 3, 300000.00, 'Selesai', 'NVL-P7Q8R9'),
(7, 'NL-88007', 4, 'Gita Permata', 'gita@mail.com', 2, 600000.00, 'Terkirim', 'NVL-S1T2U3'),
(8, 'NL-88008', 10, 'Hadi Gunawan', 'hadi@mail.com', 1, 150000.00, 'Menunggu', 'NVL-V4W5X6')
ON DUPLICATE KEY UPDATE `invoice` = VALUES(`invoice`);
