-- Migration Tahap 5: Promosi & Pengaturan (Terakhir)
-- Non-destruktif: CREATE TABLE IF NOT EXISTS & ALTER TABLE

-- 1. Tabel promo_codes (Kode Promo Diskon Tiket)
CREATE TABLE IF NOT EXISTS promo_codes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(30) UNIQUE NOT NULL,
    type ENUM('persen', 'nominal') NOT NULL,
    value DECIMAL(15,2) NOT NULL,
    event_id INT NULL,
    quota INT NULL,
    used INT DEFAULT 0,
    starts_at DATE NULL,
    ends_at DATE NULL,
    is_active TINYINT DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_promo_code (code),
    INDEX idx_promo_event (event_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo seed promo codes (sesuai mockup)
INSERT INTO promo_codes (id, code, type, value, event_id, quota, used, starts_at, ends_at, is_active, created_at)
SELECT 1, 'NOVAHEMAT', 'persen', 15.00, NULL, 2000, 1240, '2026-01-01', '2026-11-30', 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM promo_codes WHERE code = 'NOVAHEMAT');

INSERT INTO promo_codes (id, code, type, value, event_id, quota, used, starts_at, ends_at, is_active, created_at)
SELECT 2, 'TULUSJKT', 'nominal', 25000.00, 2, 500, 486, '2026-01-01', '2026-12-05', 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM promo_codes WHERE code = 'TULUSJKT');

INSERT INTO promo_codes (id, code, type, value, event_id, quota, used, starts_at, ends_at, is_active, created_at)
SELECT 3, 'BALISERU', 'persen', 10.00, 3, 300, 92, '2026-01-01', '2026-12-31', 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM promo_codes WHERE code = 'BALISERU');

INSERT INTO promo_codes (id, code, type, value, event_id, quota, used, starts_at, ends_at, is_active, created_at)
SELECT 4, 'JAZZOKT', 'persen', 20.00, 1, 500, 310, '2026-01-01', '2026-11-14', 1, NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM promo_codes WHERE code = 'JAZZOKT');

-- 2. Tabel settings (Profil Penyelenggara, Kontak, & Pengaturan Sistem)
CREATE TABLE IF NOT EXISTS settings (
    setting_key VARCHAR(50) PRIMARY KEY,
    setting_value TEXT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo seed default settings
INSERT INTO settings (setting_key, setting_value, updated_at)
SELECT 'org_name', 'Sanggar Gembira', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = 'org_name');

INSERT INTO settings (setting_key, setting_value, updated_at)
SELECT 'contact_email', 'halo@sanggargembira.id', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = 'contact_email');

INSERT INTO settings (setting_key, setting_value, updated_at)
SELECT 'contact_phone', '+62 812-3456-7890', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = 'contact_phone');

INSERT INTO settings (setting_key, setting_value, updated_at)
SELECT 'contact_city', 'Jakarta', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = 'contact_city');

INSERT INTO settings (setting_key, setting_value, updated_at)
SELECT 'contact_address', 'Jl. Jenderal Sudirman No. 45, Senayan, Jakarta Selatan, 12190', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = 'contact_address');

INSERT INTO settings (setting_key, setting_value, updated_at)
SELECT 'contact_instagram', '@sanggargembira', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = 'contact_instagram');

INSERT INTO settings (setting_key, setting_value, updated_at)
SELECT 'org_description', 'Penyelenggara konser dan festival musik independen sejak 2019.', NOW()
FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM settings WHERE setting_key = 'org_description');
