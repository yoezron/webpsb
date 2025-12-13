-- Add hubungan_wali column to data_wali table
ALTER TABLE `data_wali`
ADD COLUMN `hubungan_wali` VARCHAR(50) NULL AFTER `nama_wali`;
