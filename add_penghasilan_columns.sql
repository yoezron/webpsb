-- SQL Script to add penghasilan columns if they don't exist
-- Run this script in your MySQL database if penghasilan data is not being saved

-- Add penghasilan_ayah column to data_ayah table
ALTER TABLE `data_ayah`
ADD COLUMN IF NOT EXISTS `penghasilan_ayah` VARCHAR(50) NULL AFTER `pekerjaan_ayah`;

-- Add penghasilan_ibu column to data_ibu table
ALTER TABLE `data_ibu`
ADD COLUMN IF NOT EXISTS `penghasilan_ibu` VARCHAR(50) NULL AFTER `pekerjaan_ibu`;

-- Add penghasilan_wali column to data_wali table
ALTER TABLE `data_wali`
ADD COLUMN IF NOT EXISTS `penghasilan_wali` VARCHAR(50) NULL AFTER `pekerjaan_wali`;

-- Verify columns were added
SHOW COLUMNS FROM `data_ayah` LIKE 'penghasilan_ayah';
SHOW COLUMNS FROM `data_ibu` LIKE 'penghasilan_ibu';
SHOW COLUMNS FROM `data_wali` LIKE 'penghasilan_wali';
