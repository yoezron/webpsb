-- SQL Script to add yang_membiayai_sekolah column
-- Run this script if you see "Unknown column 'yang_membiayai_sekolah' in 'field list'" errors when saving registrations
--
-- NOTES:
-- - For MySQL 5.7 and below: If column already exists, you'll get an error that you can safely ignore
-- - For MySQL 8.0+: You can use "ADD COLUMN IF NOT EXISTS" syntax instead
-- - It's safe to run this even if the column exists - just ignore the error message

ALTER TABLE `pendaftar`
ADD COLUMN `yang_membiayai_sekolah` VARCHAR(100) NULL AFTER `cita_cita`;

-- Verify column was added
SHOW COLUMNS FROM `pendaftar` LIKE 'yang_membiayai_sekolah';
