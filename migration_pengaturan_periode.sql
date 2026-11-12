-- ============================================================
-- Menu Pengaturan Superadmin: Periode PSB, Arsip, Pengaturan Tampilan
-- Alternatif manual untuk: php spark migrate
-- (migration: 2026-11-12-000001_CreatePengaturanDanPeriode)
-- Kompatibel MySQL 5.7+ / MariaDB 10.2+
-- ============================================================

-- 1. Tabel periode / tahun ajaran PSB
CREATE TABLE IF NOT EXISTS `periode_pendaftaran` (
  `id_periode` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tahun_ajaran` VARCHAR(20) NOT NULL,
  `nama_periode` VARCHAR(150) NULL,
  `tanggal_buka` DATE NULL,
  `tanggal_tutup` DATE NULL,
  `format_nomor` VARCHAR(100) NOT NULL DEFAULT '{PREFIX}-{YY1}{YY2}{NOMOR}',
  `prefix_tsanawiyyah` VARCHAR(10) NOT NULL DEFAULT 'T',
  `prefix_muallimin` VARCHAR(10) NOT NULL DEFAULT 'M',
  `digit_nomor` TINYINT(2) UNSIGNED NOT NULL DEFAULT 4,
  `nomor_mulai` INT(11) UNSIGNED NOT NULL DEFAULT 1,
  `kuota_tsanawiyyah` INT(11) UNSIGNED NULL,
  `kuota_muallimin` INT(11) UNSIGNED NULL,
  `pendaftaran_dibuka` TINYINT(1) NOT NULL DEFAULT 1,
  `status` ENUM('aktif','arsip') NOT NULL DEFAULT 'aktif',
  `catatan` TEXT NULL,
  `diarsipkan_pada` DATETIME NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id_periode`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Periode awal (data lama masuk ke sini)
INSERT INTO `periode_pendaftaran`
  (`tahun_ajaran`, `nama_periode`, `format_nomor`, `prefix_tsanawiyyah`, `prefix_muallimin`, `digit_nomor`, `nomor_mulai`, `pendaftaran_dibuka`, `status`, `created_at`, `updated_at`)
SELECT '2026/2027', 'PSB Tahun Ajaran 2026/2027', '{PREFIX}-{YY1}{YY2}{NOMOR}', 'T', 'M', 4, 1, 1, 'aktif', NOW(), NOW()
FROM DUAL
WHERE NOT EXISTS (SELECT 1 FROM `periode_pendaftaran`);

-- 2. Tabel pengaturan (key-value)
CREATE TABLE IF NOT EXISTS `pengaturan` (
  `kunci` VARCHAR(100) NOT NULL,
  `nilai` TEXT NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`kunci`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Kolom id_periode pada pendaftar
--    (lewati baris ini bila kolom sudah ada)
ALTER TABLE `pendaftar`
  ADD COLUMN `id_periode` INT(11) UNSIGNED NULL AFTER `id_pendaftar`,
  ADD INDEX `idx_pendaftar_periode` (`id_periode`);

-- 4. Masukkan data pendaftar lama ke periode aktif
UPDATE `pendaftar`
SET `id_periode` = (SELECT `id_periode` FROM `periode_pendaftaran` WHERE `status` = 'aktif' ORDER BY `id_periode` LIMIT 1)
WHERE `id_periode` IS NULL;
