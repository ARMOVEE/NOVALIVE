-- ================================================================
-- NOVA LIVE MVC — Migrasi Skema Tahap 1: Event, Stok, Kategori & Kota
-- File: database/migrations/tahap1_event.sql
-- ================================================================

-- 1. Tambahkan kolom soft delete (deleted_at) pada tabel cities jika belum ada
ALTER TABLE `cities`
  ADD COLUMN IF NOT EXISTS `deleted_at` DATETIME NULL DEFAULT NULL AFTER `search_alias`;

-- 2. Tambahkan index untuk deleted_at pada tabel cities
ALTER TABLE `cities`
  ADD INDEX IF NOT EXISTS `idx_cities_deleted_at` (`deleted_at`);
