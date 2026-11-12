-- ============================================================
-- Kelengkapan isian sesuai EMIS + Upload Kartu Keluarga
-- Alternatif manual untuk: php spark migrate
-- (migration: 2026-11-13-000001_AddKolomEmisDanFileKk)
-- Lewati baris ALTER untuk kolom yang sudah ada.
-- ============================================================

ALTER TABLE `pendaftar`
  ADD COLUMN `agama` VARCHAR(30) NULL AFTER `jenis_kelamin`,
  ADD COLUMN `file_kk` VARCHAR(255) NULL;

ALTER TABLE `data_ayah`
  ADD COLUMN `domisili_ayah` VARCHAR(30) NULL AFTER `pekerjaan_ayah`,
  ADD COLUMN `status_tempat_tinggal_ayah` VARCHAR(50) NULL AFTER `alamat_ayah`;

ALTER TABLE `data_ibu`
  ADD COLUMN `domisili_ibu` VARCHAR(30) NULL AFTER `pekerjaan_ibu`,
  ADD COLUMN `status_tempat_tinggal_ibu` VARCHAR(50) NULL AFTER `alamat_ibu`;

ALTER TABLE `data_wali`
  ADD COLUMN `domisili_wali` VARCHAR(30) NULL AFTER `pekerjaan_wali`,
  ADD COLUMN `alamat_wali` TEXT NULL AFTER `hp_wali`,
  ADD COLUMN `status_tempat_tinggal_wali` VARCHAR(50) NULL AFTER `alamat_wali`;

-- Seragamkan nama menjadi HURUF BESAR
UPDATE `pendaftar` SET `nama_lengkap` = UPPER(TRIM(`nama_lengkap`)) WHERE `nama_lengkap` IS NOT NULL;
UPDATE `data_ayah` SET `nama_ayah` = UPPER(TRIM(`nama_ayah`)) WHERE `nama_ayah` IS NOT NULL;
UPDATE `data_ibu`  SET `nama_ibu`  = UPPER(TRIM(`nama_ibu`))  WHERE `nama_ibu` IS NOT NULL;
UPDATE `data_wali` SET `nama_wali` = UPPER(TRIM(`nama_wali`)) WHERE `nama_wali` IS NOT NULL;
