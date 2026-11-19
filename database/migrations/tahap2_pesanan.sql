-- ================================================================
-- NOVA LIVE MVC — Migrasi Tahap 2: Check-in, Refund, dan E-Tiket
-- File: database/migrations/tahap2_pesanan.sql
-- ================================================================

-- 1. Tambah kolom checked_in_at pada tabel orders jika belum ada
SET @col_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
      AND TABLE_NAME = 'orders' 
      AND COLUMN_NAME = 'checked_in_at'
);

SET @sql_checkin = IF(@col_exists = 0, 
    'ALTER TABLE orders ADD COLUMN checked_in_at DATETIME NULL AFTER ticket_code', 
    'SELECT 1'
);
PREPARE stmt FROM @sql_checkin;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2. Buat tabel refunds jika belum ada
CREATE TABLE IF NOT EXISTS refunds (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    reason VARCHAR(255) NOT NULL,
    amount DECIMAL(15,2) NOT NULL,
    status ENUM('Diajukan','Disetujui','Ditolak') DEFAULT 'Diajukan',
    processed_by INT NULL,
    processed_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    KEY idx_refunds_order (order_id),
    KEY idx_refunds_status (status),
    CONSTRAINT fk_refunds_order FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. Seed 2 refund demo berstatus Diajukan (jika belum ada)
INSERT INTO refunds (order_id, reason, amount, status, created_at)
SELECT 1, 'Bentrok dengan jadwal ujian kedinasan', 900000.00, 'Diajukan', '2026-11-18 10:20:00'
WHERE NOT EXISTS (SELECT 1 FROM refunds WHERE order_id = 1);

INSERT INTO refunds (order_id, reason, amount, status, created_at)
SELECT 4, 'Tiket berlebih karena teman berhalangan hadir', 800000.00, 'Diajukan', '2026-11-18 16:45:00'
WHERE NOT EXISTS (SELECT 1 FROM refunds WHERE order_id = 4);
