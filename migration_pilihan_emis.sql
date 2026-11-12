-- ============================================================
-- Sesuaikan pilihan Jarak & Transportasi lama ke versi EMIS
-- Alternatif manual untuk: php spark migrate
-- (migration: 2026-11-14-000001_SesuaikanPilihanEmis)
-- Nilai ambigu (jarak "> 20 km", waktu tempuh lama) sengaja tidak diubah.
-- ============================================================

UPDATE `alamat_pendaftar` SET `jarak_ke_sekolah` = 'Kurang dari 5 km'  WHERE `jarak_ke_sekolah` IN ('< 1 km', '1-5 km');
UPDATE `alamat_pendaftar` SET `jarak_ke_sekolah` = 'Antara 5 - 10 Km'  WHERE `jarak_ke_sekolah` = '5-10 km';
UPDATE `alamat_pendaftar` SET `jarak_ke_sekolah` = 'Antara 11 - 20 Km' WHERE `jarak_ke_sekolah` = '10-20 km';

UPDATE `alamat_pendaftar` SET `transportasi` = 'Sepeda Motor'  WHERE `transportasi` = 'Motor';
UPDATE `alamat_pendaftar` SET `transportasi` = 'Mobil Pribadi' WHERE `transportasi` = 'Mobil';
UPDATE `alamat_pendaftar` SET `transportasi` = 'Ojek'          WHERE `transportasi` = 'Ojek Online';
