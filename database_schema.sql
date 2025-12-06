-- ====================================================
-- Database Schema - PSB Persis 31 Banjaran
-- Version: 1.0
-- Date: December 2025
-- ====================================================

-- Create Database
CREATE DATABASE IF NOT EXISTS psb_persis31 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE psb_persis31;

-- ====================================================
-- Table: pendaftar (Main Registration Table)
-- ====================================================
CREATE TABLE IF NOT EXISTS pendaftar (
    id_pendaftar INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nomor_pendaftaran VARCHAR(50) UNIQUE NOT NULL,
    jalur_pendaftaran ENUM('TSANAWIYYAH', 'MUALLIMIN') NOT NULL,
    
    -- Data Diri
    nisn VARCHAR(20),
    nik VARCHAR(20),
    nama_lengkap VARCHAR(150) NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    status_keluarga VARCHAR(50),
    anak_ke TINYINT UNSIGNED,
    jumlah_saudara TINYINT UNSIGNED,
    hobi VARCHAR(100),
    cita_cita VARCHAR(100),
    pernah_paud BOOLEAN DEFAULT FALSE,
    pernah_tk BOOLEAN DEFAULT FALSE,
    kebutuhan_disabilitas VARCHAR(100),
    imunisasi VARCHAR(100),
    no_hp VARCHAR(20),
    ukuran_baju VARCHAR(10),
    prestasi TEXT,
    
    -- Timestamps
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    INDEX idx_nomor (nomor_pendaftaran),
    INDEX idx_jalur (jalur_pendaftaran),
    INDEX idx_tanggal (tanggal_daftar),
    INDEX idx_nama (nama_lengkap)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Table: alamat_pendaftar (Address Information)
-- ====================================================
CREATE TABLE IF NOT EXISTS alamat_pendaftar (
    id_alamat INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT UNSIGNED NOT NULL,
    
    nomor_kk VARCHAR(20),
    jenis_tempat_tinggal VARCHAR(50),
    alamat TEXT,
    desa VARCHAR(100),
    kecamatan VARCHAR(100),
    kabupaten VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    jarak_ke_sekolah VARCHAR(50),
    waktu_tempuh VARCHAR(50),
    transportasi VARCHAR(100),
    email VARCHAR(100),
    media_sosial VARCHAR(100),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_pendaftar) REFERENCES pendaftar(id_pendaftar) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pendaftar (id_pendaftar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Table: data_ayah (Father's Information)
-- ====================================================
CREATE TABLE IF NOT EXISTS data_ayah (
    id_ayah INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT UNSIGNED NOT NULL,
    
    nama_ayah VARCHAR(150),
    nik_ayah VARCHAR(20),
    tempat_lahir_ayah VARCHAR(100),
    tanggal_lahir_ayah DATE,
    status_ayah VARCHAR(50),
    pendidikan_ayah VARCHAR(50),
    pekerjaan_ayah VARCHAR(100),
    penghasilan_ayah VARCHAR(50),
    hp_ayah VARCHAR(20),
    alamat_ayah TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_pendaftar) REFERENCES pendaftar(id_pendaftar) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pendaftar (id_pendaftar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Table: data_ibu (Mother's Information)
-- ====================================================
CREATE TABLE IF NOT EXISTS data_ibu (
    id_ibu INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT UNSIGNED NOT NULL,
    
    nama_ibu VARCHAR(150),
    nik_ibu VARCHAR(20),
    tempat_lahir_ibu VARCHAR(100),
    tanggal_lahir_ibu DATE,
    status_ibu VARCHAR(50),
    pendidikan_ibu VARCHAR(50),
    pekerjaan_ibu VARCHAR(100),
    penghasilan_ibu VARCHAR(50),
    hp_ibu VARCHAR(20),
    alamat_ibu TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_pendaftar) REFERENCES pendaftar(id_pendaftar) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pendaftar (id_pendaftar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Table: data_wali (Guardian's Information)
-- ====================================================
CREATE TABLE IF NOT EXISTS data_wali (
    id_wali INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT UNSIGNED NOT NULL,
    
    nama_wali VARCHAR(150),
    nik_wali VARCHAR(20),
    tahun_lahir_wali YEAR,
    pendidikan_wali VARCHAR(50),
    pekerjaan_wali VARCHAR(100),
    penghasilan_wali VARCHAR(50),
    hp_wali VARCHAR(20),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_pendaftar) REFERENCES pendaftar(id_pendaftar) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pendaftar (id_pendaftar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Table: bansos_pendaftar (Social Aid Information)
-- ====================================================
CREATE TABLE IF NOT EXISTS bansos_pendaftar (
    id_bansos INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT UNSIGNED NOT NULL,
    
    no_kks VARCHAR(30),
    no_pkh VARCHAR(30),
    no_kip VARCHAR(30),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_pendaftar) REFERENCES pendaftar(id_pendaftar) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pendaftar (id_pendaftar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Table: asal_sekolah (Previous School Information)
-- ====================================================
CREATE TABLE IF NOT EXISTS asal_sekolah (
    id_sekolah INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    id_pendaftar INT UNSIGNED NOT NULL,
    
    nama_asal_sekolah VARCHAR(200),
    jenjang_sekolah VARCHAR(50),
    status_sekolah VARCHAR(50),
    npsn VARCHAR(20),
    lokasi_sekolah VARCHAR(200),
    asal_jenjang VARCHAR(50), -- Ibtidaiyyah/Tsanawiyyah
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (id_pendaftar) REFERENCES pendaftar(id_pendaftar) 
        ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX idx_pendaftar (id_pendaftar)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Table: admin_panitia (Admin/Committee Users)
-- ====================================================
CREATE TABLE IF NOT EXISTS admin_panitia (
    id_admin INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(150),
    email VARCHAR(100),
    role_panitia ENUM('tsanawiyyah', 'muallimin', 'superadmin') NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    last_login TIMESTAMP NULL,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_username (username),
    INDEX idx_role (role_panitia)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ====================================================
-- Sample Data for Admin (Password: admin123)
-- Password hash generated with: password_hash('admin123', PASSWORD_DEFAULT)
-- ====================================================
INSERT INTO admin_panitia (username, password_hash, nama_lengkap, email, role_panitia) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Admin', 'admin@persis31.com', 'superadmin'),
('panitia_tsn', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Panitia Tsanawiyyah', 'tsn@persis31.com', 'tsanawiyyah'),
('panitia_mua', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Panitia Mu\'allimin', 'mua@persis31.com', 'muallimin');

-- ====================================================
-- Views for Reporting
-- ====================================================

-- View: Complete Registration Data
CREATE OR REPLACE VIEW v_pendaftar_lengkap AS
SELECT 
    p.id_pendaftar,
    p.nomor_pendaftaran,
    p.jalur_pendaftaran,
    p.nama_lengkap,
    p.nisn,
    p.nik,
    p.jenis_kelamin,
    CONCAT(p.tempat_lahir, ', ', DATE_FORMAT(p.tanggal_lahir, '%d-%m-%Y')) AS ttl,
    p.no_hp,
    a.email,
    a.alamat,
    a.desa,
    a.kecamatan,
    ay.nama_ayah,
    ay.hp_ayah,
    i.nama_ibu,
    i.hp_ibu,
    s.nama_asal_sekolah,
    s.npsn,
    p.tanggal_daftar
FROM pendaftar p
LEFT JOIN alamat_pendaftar a ON p.id_pendaftar = a.id_pendaftar
LEFT JOIN data_ayah ay ON p.id_pendaftar = ay.id_pendaftar
LEFT JOIN data_ibu i ON p.id_pendaftar = i.id_pendaftar
LEFT JOIN asal_sekolah s ON p.id_pendaftar = s.id_pendaftar
WHERE p.deleted_at IS NULL;

-- View: Statistics per Jalur
CREATE OR REPLACE VIEW v_statistik_pendaftar AS
SELECT 
    jalur_pendaftaran,
    COUNT(*) AS total_pendaftar,
    SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) AS total_laki,
    SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) AS total_perempuan,
    DATE(MAX(tanggal_daftar)) AS pendaftar_terakhir
FROM pendaftar
WHERE deleted_at IS NULL
GROUP BY jalur_pendaftaran;

-- ====================================================
-- Stored Procedures
-- ====================================================

DELIMITER $$

-- Procedure: Generate Nomor Pendaftaran
CREATE PROCEDURE sp_generate_nomor_pendaftaran(
    IN p_jalur ENUM('TSANAWIYYAH', 'MUALLIMIN'),
    OUT p_nomor VARCHAR(50)
)
BEGIN
    DECLARE v_prefix VARCHAR(5);
    DECLARE v_tahun VARCHAR(4);
    DECLARE v_counter INT;
    
    SET v_tahun = '2026';
    SET v_prefix = IF(p_jalur = 'TSANAWIYYAH', 'T', 'M');
    
    -- Get last counter
    SELECT IFNULL(MAX(CAST(SUBSTRING(nomor_pendaftaran, -3) AS UNSIGNED)), 0) + 1
    INTO v_counter
    FROM pendaftar
    WHERE jalur_pendaftaran = p_jalur
    AND YEAR(tanggal_daftar) = v_tahun;
    
    -- Generate nomor
    SET p_nomor = CONCAT(v_prefix, v_tahun, '-', LPAD(v_counter, 3, '0'));
END$$

DELIMITER ;

-- ====================================================
-- Triggers
-- ====================================================

DELIMITER $$

-- Trigger: Auto-generate nomor pendaftaran before insert
CREATE TRIGGER trg_before_insert_pendaftar
BEFORE INSERT ON pendaftar
FOR EACH ROW
BEGIN
    DECLARE v_nomor VARCHAR(50);
    
    IF NEW.nomor_pendaftaran IS NULL OR NEW.nomor_pendaftaran = '' THEN
        CALL sp_generate_nomor_pendaftaran(NEW.jalur_pendaftaran, v_nomor);
        SET NEW.nomor_pendaftaran = v_nomor;
    END IF;
END$$

DELIMITER ;

-- ====================================================
-- Indexes for Performance
-- ====================================================

-- Additional indexes for common queries
CREATE INDEX idx_pendaftar_status ON pendaftar(jalur_pendaftaran, tanggal_daftar);
CREATE INDEX idx_alamat_wilayah ON alamat_pendaftar(provinsi, kabupaten, kecamatan);
CREATE INDEX idx_sekolah_npsn ON asal_sekolah(npsn);

-- ====================================================
-- Database Information
-- ====================================================

-- Show table statistics
SELECT 
    TABLE_NAME,
    TABLE_ROWS,
    ROUND(DATA_LENGTH / 1024 / 1024, 2) AS 'Size (MB)',
    ENGINE,
    TABLE_COLLATION
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = 'psb_persis31';

-- ====================================================
-- END OF SCHEMA
-- ====================================================
