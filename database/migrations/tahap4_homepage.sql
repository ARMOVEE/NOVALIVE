-- Migration Tahap 4: Homepage (Banner & Tampilan Homepage)
-- Non-destruktif: CREATE TABLE IF NOT EXISTS

-- 1. Tabel Banners
CREATE TABLE IF NOT EXISTS banners (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NULL,
    subtitle VARCHAR(255) NULL,
    image VARCHAR(255) NULL,
    link_url VARCHAR(255) NULL,
    cta_text VARCHAR(100) NULL DEFAULT 'Lihat Event',
    bg_color VARCHAR(50) NULL DEFAULT '#15497A',
    start_date DATE NULL,
    end_date DATE NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo seed data: 3 slide aktif TANPA gambar (tampil navy kosong sesuai mockup)
INSERT INTO banners (id, title, subtitle, image, link_url, cta_text, bg_color, sort_order, is_active, created_at)
SELECT 1, 'Promo Spesial Konser Musik', 'Dapatkan diskon tiket hingga 50% untuk event pilihan bulan ini.', NULL, '/events', 'Lihat Promo', '#15497A', 1, 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM banners WHERE id = 1);

INSERT INTO banners (id, title, subtitle, image, link_url, cta_text, bg_color, sort_order, is_active, created_at)
SELECT 2, 'Festival Kuliner & Budaya Nusantara', 'Jelajahi kelezatan kuliner tradisional dan atraksi kesenian.', NULL, '/events', 'Beli Tiket', '#0E3254', 2, 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM banners WHERE id = 2);

INSERT INTO banners (id, title, subtitle, image, link_url, cta_text, bg_color, sort_order, is_active, created_at)
SELECT 3, 'Turnamen E-Sports & Workshop Kreatif', 'Saksikan pertarungan tim terbaik dan ikuti workshop inspiratif.', NULL, '/events', 'Daftar Sekarang', '#23649A', 3, 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM banners WHERE id = 3);

-- 2. Tabel Homepage Sections (Pengaturan Section Homepage)
CREATE TABLE IF NOT EXISTS homepage_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(50) UNIQUE NOT NULL,
    title VARCHAR(255) NOT NULL,
    is_visible TINYINT DEFAULT 1,
    sort_order INT DEFAULT 0,
    item_limit INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO homepage_sections (id, section_key, title, is_visible, sort_order, item_limit, created_at)
SELECT 1, 'event_seru', 'Event Seru Untukmu', 1, 1, 10, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_key = 'event_seru');

INSERT INTO homepage_sections (id, section_key, title, is_visible, sort_order, item_limit, created_at)
SELECT 2, 'kategori', 'Kategori Event', 1, 2, 8, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_key = 'kategori');

INSERT INTO homepage_sections (id, section_key, title, is_visible, sort_order, item_limit, created_at)
SELECT 3, 'event_populer', 'Event Populer', 1, 3, 4, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_key = 'event_populer');

INSERT INTO homepage_sections (id, section_key, title, is_visible, sort_order, item_limit, created_at)
SELECT 4, 'jelajahi_kota', 'Jelajahi Event di Kotamu', 1, 4, 6, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM homepage_sections WHERE section_key = 'jelajahi_kota');

-- 3. Tabel Featured Events (Kurasi Event per Section)
CREATE TABLE IF NOT EXISTS featured_events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_key VARCHAR(50) NOT NULL,
    event_id INT NOT NULL,
    sort_order INT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_featured_section (section_key),
    INDEX idx_featured_event (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
