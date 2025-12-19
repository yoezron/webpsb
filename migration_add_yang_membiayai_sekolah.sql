-- SQL Script to add yang_membiayai_sekolah column if it doesn't exist
-- Run this script if you see "Unknown column 'yang_membiayai_sekolah' in 'field list'" errors when saving registrations

ALTER TABLE `pendaftar`
ADD COLUMN IF NOT EXISTS `yang_membiayai_sekolah` VARCHAR(100) NULL AFTER `cita_cita`;

-- Verify column was added
SHOW COLUMNS FROM `pendaftar` LIKE 'yang_membiayai_sekolah';
