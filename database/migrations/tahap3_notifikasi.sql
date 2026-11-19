-- Migration Tahap 3: Notifikasi
-- Non-destruktif: CREATE TABLE IF NOT EXISTS

CREATE TABLE IF NOT EXISTS notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'system',
    title VARCHAR(150) NOT NULL,
    message TEXT NOT NULL,
    link VARCHAR(255) NULL,
    is_read TINYINT DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo seed notifications jika belum ada
INSERT INTO notifications (id, user_id, type, title, message, link, is_read, created_at)
SELECT 1, 1, 'order', 'Pembayaran Tiket Berhasil', 'Pembayaran tiket Tulus Live in Concert berhasil diterima dari Eko Wijaya.', '/dashboard/orders', 0, DATE_SUB(NOW(), INTERVAL 2 HOUR)
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM notifications WHERE id = 1);

INSERT INTO notifications (id, user_id, type, title, message, link, is_read, created_at)
SELECT 2, 1, 'refund', 'Pengajuan Refund Baru', 'Permohonan refund #1 diajukan untuk order INV-20261118-001 (Fun Run 10K).', '/dashboard/orders/refunds', 0, DATE_SUB(NOW(), INTERVAL 5 HOUR)
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM notifications WHERE id = 2);

INSERT INTO notifications (id, user_id, type, title, message, link, is_read, created_at)
SELECT 3, 1, 'stock', 'Peringatan Stok Tiket Menipis', 'Kuota tiket Festival Musik Senja tersisa kurang dari 20%. Segera tambah kuota.', '/dashboard/events/stock', 0, DATE_SUB(NOW(), INTERVAL 1 DAY)
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM notifications WHERE id = 3);
