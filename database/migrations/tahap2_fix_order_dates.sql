-- ================================================================
-- NOVA LIVE MVC — Migrasi Tahap 2: Variasi Tanggal Pesanan Demo
-- File: database/migrations/tahap2_fix_order_dates.sql
-- ================================================================

UPDATE orders SET created_at = '2026-11-12 09:15:22' WHERE id = 1;
UPDATE orders SET created_at = '2026-11-13 14:30:45' WHERE id = 2;
UPDATE orders SET created_at = '2026-11-14 11:20:10' WHERE id = 3;
UPDATE orders SET created_at = '2026-11-15 16:45:33' WHERE id = 4;
UPDATE orders SET created_at = '2026-11-16 10:05:18' WHERE id = 5;
UPDATE orders SET created_at = '2026-11-17 13:50:40' WHERE id = 6;
UPDATE orders SET created_at = '2026-11-17 19:25:12' WHERE id = 7;
UPDATE orders SET created_at = '2026-11-18 08:40:05' WHERE id = 8;
