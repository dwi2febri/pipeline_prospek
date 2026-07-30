-- Upgrade schema production E-Prospek
-- Sumber production: sql_eprospek_bkk.sql
-- Target struktur: database lokal pipeline_prospek
-- Dibuat: 2026-07-30 08:55:58
-- WAJIB backup database production sebelum menjalankan script ini.
-- Script ini hanya MENAMBAH tabel, kolom, indeks, dan foreign key yang belum ada.

SET FOREIGN_KEY_CHECKS = 0;

-- Tabel baru: user_simpeg_syncs
CREATE TABLE `user_simpeg_syncs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `employee_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_status` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_message` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `snapshot_data` json DEFAULT NULL,
  `synced_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_simpeg_syncs_employee_id_unique` (`employee_id`),
  KEY `user_simpeg_syncs_user_id_foreign` (`user_id`),
  KEY `user_simpeg_syncs_sync_status_index` (`sync_status`),
  CONSTRAINT `user_simpeg_syncs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Kolom baru pada prospects
ALTER TABLE `prospects`
  ADD COLUMN `estimasi_nominal_realisasi` bigint unsigned DEFAULT NULL AFTER `updated_at`;

-- Tandai migration terkait agar `php artisan migrate` tidak mengulangi DDL di atas.
SET @eprospek_upgrade_batch = (SELECT COALESCE(MAX(`batch`), 0) + 1 FROM `migrations`);
INSERT INTO `migrations` (`migration`, `batch`) SELECT '2026_07_23_000001_add_simpeg_fields_and_sync_logs', @eprospek_upgrade_batch WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_07_23_000001_add_simpeg_fields_and_sync_logs');
INSERT INTO `migrations` (`migration`, `batch`) SELECT '2026_07_29_000002_add_estimasi_nominal_realisasi_to_prospects_table', @eprospek_upgrade_batch WHERE NOT EXISTS (SELECT 1 FROM `migrations` WHERE `migration` = '2026_07_29_000002_add_estimasi_nominal_realisasi_to_prospects_table');

SET FOREIGN_KEY_CHECKS = 1;

-- Perbedaan definisi kolom/indeks yang sudah ada sengaja TIDAK diubah otomatis.
-- Tinjau laporan Markdown sebelum memutuskan perubahan destruktif/konversi tipe.
