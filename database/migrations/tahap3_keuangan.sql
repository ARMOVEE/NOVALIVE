-- Migration Tahap 3: Keuangan (Withdrawals / Penarikan Dana)
-- Non-destruktif: CREATE TABLE IF NOT EXISTS

CREATE TABLE IF NOT EXISTS withdrawals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    amount DECIMAL(15,2) NOT NULL,
    bank_name VARCHAR(100) NOT NULL,
    account_no VARCHAR(50) NOT NULL,
    account_name VARCHAR(150) NOT NULL,
    status ENUM('Diproses', 'Berhasil', 'Ditolak') DEFAULT 'Diproses',
    note TEXT NULL,
    created_by INT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demo seed data jika belum ada
INSERT INTO withdrawals (id, amount, bank_name, account_no, account_name, status, note, created_by, created_at)
SELECT 1, 500000.00, 'BCA', '8820491823', 'Admin NOVA LIVE', 'Berhasil', 'Pencairan dana tiket penjualan bulan lalu', 1, DATE_SUB(NOW(), INTERVAL 5 DAY)
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM withdrawals WHERE id = 1);
