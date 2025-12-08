-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Waktu pembuatan: 08 Des 2025 pada 03.06
-- Versi server: 8.4.3
-- Versi PHP: 8.4.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Basis data: `psb_persis31`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `admin_panitia`
--

CREATE TABLE `admin_panitia` (
  `id_admin` int UNSIGNED NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `role_panitia` enum('tsanawiyyah','muallimin','superadmin') COLLATE utf8mb4_general_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `admin_panitia`
--

INSERT INTO `admin_panitia` (`id_admin`, `username`, `password_hash`, `nama_lengkap`, `email`, `role_panitia`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', '$2y$10$k3fsXqDAKjf4cm/OPEIhS.2NAgE4yFOQTe2vfloKXNiz2XFv3vn7u', 'Super Administrator', 'admin@persis31.com', 'superadmin', 1, '2025-12-07 19:40:14', '2025-12-06 02:21:54', '2025-12-07 19:40:14'),
(2, 'panitia_tsn', '$2y$10$H4QT0ZVot/SUxjfuxEbvpu9c.a4MPmmyRm0lTBjAUAt/oVmku/3vm', 'Panitia Tsanawiyyah', 'tsn@persis31.com', 'tsanawiyyah', 1, NULL, '2025-12-06 02:21:55', '2025-12-06 02:21:55'),
(3, 'panitia_mua', '$2y$10$XRCxDSna9fLdG5njj39nV.emmFsh1/hUYUNY1xF4FEXzzrtDAVOAK', 'Panitia Mu\'allimin', 'mua@persis31.com', 'muallimin', 1, NULL, '2025-12-06 02:21:55', '2025-12-06 02:21:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `alamat_pendaftar`
--

CREATE TABLE `alamat_pendaftar` (
  `id_alamat` int UNSIGNED NOT NULL,
  `id_pendaftar` int UNSIGNED NOT NULL,
  `nomor_kk` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenis_tempat_tinggal` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `desa` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kecamatan` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kabupaten` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `provinsi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kode_pos` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jarak_ke_sekolah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `waktu_tempuh` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `transportasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `media_sosial` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `alamat_pendaftar`
--

INSERT INTO `alamat_pendaftar` (`id_alamat`, `id_pendaftar`, `nomor_kk`, `jenis_tempat_tinggal`, `alamat`, `desa`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `jarak_ke_sekolah`, `waktu_tempuh`, `transportasi`, `email`, `media_sosial`, `created_at`, `updated_at`) VALUES
(1, 1, '3273011234567890', 'Rumah Sendiri', 'Jl. Raya Banjaran No. 123', 'Banjaran', 'Banjaran', 'Bandung', 'Jawa Barat', '40377', '2 Km', '10 Menit', 'Sepeda', 'ahmad.fauzi@example.com', '@ahmadfauzi', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(2, 2, '3273019876543210', 'Rumah Orang Tua', 'Jl. Pesantren No. 45', 'Banjaran Wetan', 'Banjaran', 'Bandung', 'Jawa Barat', '40377', '1 Km', '5 Menit', 'Jalan Kaki', 'fatimah.zahra@example.com', '@fatimazhz', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(3, 3, '3515150215151051', 'Milik Sendiri', 'Banjaran, Kabupaten Bandung, Jawa Barat', 'Ciapus', 'Banjaran', 'Bandung', 'Jawa Barat', '40377', '', '', 'Angkutan Umum', 'rahmaniyusron@outlook.co.id', '@serikatpekerjakampus', '2025-12-06 08:44:37', '2025-12-06 08:44:37'),
(4, 4, '1234567891234567', 'Milik Sendiri', 'Banjaran, Kabupaten Bandung, Jawa Barat', 'Ciapus', 'Banjaran', 'Bandung', 'Jawa Barat', '45285', '1-5 km', '15-30 menit', 'Sepeda', 'yoezron90@gmail.com', '@serikat', '2025-12-06 13:12:44', '2025-12-06 13:12:44'),
(5, 5, '1234567891234567', 'Milik Sendiri', 'Banjaran, Kabupaten Bandung, Jawa Barat', 'Ciapus', 'Banjaran', 'Bandung', 'Jawa Barat', '45285', '< 1 km', '15-30 menit', 'Sepeda', 'yoezron90@gmail.com', '@serikat', '2025-12-06 13:24:52', '2025-12-06 13:24:52'),
(6, 6, '3515150215151051', 'Milik Sendiri', '', 'Ciapus', 'Banjaran', 'Bandung', 'Jawa Barat', '40377', '', '', '', 'rahmaniyusron@outlook.co.id', '@serikatpekerjakampus', '2025-12-06 20:33:50', '2025-12-06 20:33:50'),
(7, 7, '3515150215151051', 'Milik Sendiri', 'Banjaran, Kabupaten Bandung, Jawa Barat', 'Ciapus', 'Banjaran', 'Bandung', 'Jawa Barat', '40377', '< 1 km', '15-30 menit', 'Sepeda', 'rahmaniyusron@outlook.co.id', '@serikatpekerjakampus', '2025-12-07 07:22:45', '2025-12-07 07:22:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `asal_sekolah`
--

CREATE TABLE `asal_sekolah` (
  `id_sekolah` int UNSIGNED NOT NULL,
  `id_pendaftar` int UNSIGNED NOT NULL,
  `nama_asal_sekolah` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jenjang_sekolah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_sekolah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `npsn` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `lokasi_sekolah` varchar(200) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `asal_jenjang` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL COMMENT 'Ibtidaiyyah/Tsanawiyyah',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `asal_sekolah`
--

INSERT INTO `asal_sekolah` (`id_sekolah`, `id_pendaftar`, `nama_asal_sekolah`, `jenjang_sekolah`, `status_sekolah`, `npsn`, `lokasi_sekolah`, `asal_jenjang`, `created_at`, `updated_at`) VALUES
(1, 1, 'MI Al-Ikhlas Banjaran', 'MI', 'Swasta', '10234567', 'Banjaran, Bandung', 'Ibtidaiyyah', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(2, 2, 'MTs Persis 31 Banjaran', 'MTs', 'Swasta', '20345678', 'Banjaran, Bandung', 'Tsanawiyyah', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(3, 3, 'MTS PERSIS 31 Banjaran', '', 'Swasta', '15505510', 'Banjaran', 'MTS Persis 31 Banjaran', '2025-12-06 08:44:37', '2025-12-06 08:44:37'),
(4, 4, 'MTS Persis 31', 'MTs', 'Swasta', '12345678', 'Banjaran', 'MTs Persis 31 Banjaran', '2025-12-06 13:12:44', '2025-12-06 13:12:44'),
(5, 5, 'MTS Persis 31', 'MTs', 'Swasta', '12345678', 'Banjaran', 'MTs Persis 31 Banjaran', '2025-12-06 13:24:52', '2025-12-06 13:24:52'),
(6, 6, 'MTS Persis 31', '', '', '12345678', 'Banjaran', 'MTs Persis 31 Banjaran', '2025-12-06 20:33:50', '2025-12-06 20:33:50'),
(7, 7, 'MTS Persis 31', 'MI', 'Swasta', '12345678', 'Banjaran', 'MTs Persis 31 Banjaran', '2025-12-07 07:22:45', '2025-12-07 07:22:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `bansos_pendaftar`
--

CREATE TABLE `bansos_pendaftar` (
  `id_bansos` int UNSIGNED NOT NULL,
  `id_pendaftar` int UNSIGNED NOT NULL,
  `no_kks` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_pkh` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_kip` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `bansos_pendaftar`
--

INSERT INTO `bansos_pendaftar` (`id_bansos`, `id_pendaftar`, `no_kks`, `no_pkh`, `no_kip`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, '123456789012', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(2, 2, '987654321098', NULL, NULL, '2025-12-06 09:21:55', '2025-12-06 09:21:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_ayah`
--

CREATE TABLE `data_ayah` (
  `id_ayah` int UNSIGNED NOT NULL,
  `id_pendaftar` int UNSIGNED NOT NULL,
  `nama_ayah` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik_ayah` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir_ayah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir_ayah` date DEFAULT NULL,
  `status_ayah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pendidikan_ayah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan_ayah` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `penghasilan_ayah` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hp_ayah` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_ayah` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_ayah`
--

INSERT INTO `data_ayah` (`id_ayah`, `id_pendaftar`, `nama_ayah`, `nik_ayah`, `tempat_lahir_ayah`, `tanggal_lahir_ayah`, `status_ayah`, `pendidikan_ayah`, `pekerjaan_ayah`, `penghasilan_ayah`, `hp_ayah`, `alamat_ayah`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bapak Ahmad Fauzi Sr.', '3273011234567891', 'Bandung', '1980-01-15', 'Hidup', 'S1', 'Wiraswasta', 'Rp 5.000.000 - Rp 10.000.000', '081234567891', 'Jl. Raya Banjaran No. 123', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(2, 2, 'H. Abdullah Rahman', '3273019876543211', 'Bandung', '1975-05-10', 'Hidup', 'S2', 'PNS', 'Rp 10.000.000 - Rp 20.000.000', '082345678902', 'Jl. Pesantren No. 45', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(3, 3, 'Nurdin', '1101520510205105', 'Bandung', '2010-03-10', 'Masih Hidup', 'D4/S1', 'Wiraswasta', '1-2 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-06 08:44:37', '2025-12-06 08:44:37'),
(4, 4, 'Mulyono', '1234567891234567', 'Bandung', '2015-06-01', 'Masih Hidup', 'D4/S1', 'Wiraswasta', '3-5 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-06 13:12:44', '2025-12-06 13:12:44'),
(5, 5, 'Mulyono', '1234567891234567', 'Bandung', '2015-06-01', 'Sudah Meninggal', 'D4/S1', 'Wiraswasta', '5-10 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-06 13:24:52', '2025-12-06 13:24:52'),
(6, 6, 'Nurdin', '1101520510205105', 'Bandung', '2010-03-10', '', '', 'Wiraswasta', '', '08122125163', '', '2025-12-06 20:33:50', '2025-12-06 20:33:50'),
(7, 7, 'Nurdin', '1101520510205105', 'Bandung', '2010-03-10', 'Masih Hidup', 'D3', 'Wiraswasta', '1-2 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-07 07:22:45', '2025-12-07 07:22:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_ibu`
--

CREATE TABLE `data_ibu` (
  `id_ibu` int UNSIGNED NOT NULL,
  `id_pendaftar` int UNSIGNED NOT NULL,
  `nama_ibu` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik_ibu` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tempat_lahir_ibu` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir_ibu` date DEFAULT NULL,
  `status_ibu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pendidikan_ibu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan_ibu` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `penghasilan_ibu` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hp_ibu` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat_ibu` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_ibu`
--

INSERT INTO `data_ibu` (`id_ibu`, `id_pendaftar`, `nama_ibu`, `nik_ibu`, `tempat_lahir_ibu`, `tanggal_lahir_ibu`, `status_ibu`, `pendidikan_ibu`, `pekerjaan_ibu`, `penghasilan_ibu`, `hp_ibu`, `alamat_ibu`, `created_at`, `updated_at`) VALUES
(1, 1, 'Ibu Siti Nurhaliza', '3273011234567892', 'Bandung', '1982-03-20', 'Hidup', 'SMA', 'Ibu Rumah Tangga', '< Rp 1.000.000', '081234567892', 'Jl. Raya Banjaran No. 123', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(2, 2, 'Hj. Khadijah Aminah', '3273019876543212', 'Bandung', '1978-07-25', 'Hidup', 'S1', 'Guru', 'Rp 3.000.000 - Rp 5.000.000', '082345678903', 'Jl. Pesantren No. 45', '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(3, 3, 'Marni', '1061031502511654', 'Bandung', '2010-02-10', 'Masih Hidup', 'D4/S1', 'PNS', '2-3 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-06 08:44:37', '2025-12-06 08:44:37'),
(4, 4, 'Mulyati', '1234567891234567', 'Bandung', '2019-01-29', 'Masih Hidup', 'D4/S1', 'IRT', '1-2 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-06 13:12:44', '2025-12-06 13:12:44'),
(5, 5, 'Mulyati', '1234567891234567', 'Bandung', '2019-01-29', 'Sudah Meninggal', 'S2', 'IRT', '1-2 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-06 13:24:52', '2025-12-06 13:24:52'),
(6, 6, 'Marni', '1061031502511654', 'Bandung', '2010-02-10', '', '', 'PNS', '', '08122125163', '', '2025-12-06 20:33:50', '2025-12-06 20:33:50'),
(7, 7, 'Marni', '1061031502511654', 'Bandung', '2010-02-10', 'Masih Hidup', 'D4/S1', 'PNS', '3-5 juta', '08122125163', 'Banjaran, Kabupaten Bandung, Jawa Barat', '2025-12-07 07:22:45', '2025-12-07 07:22:45');

-- --------------------------------------------------------

--
-- Struktur dari tabel `data_wali`
--

CREATE TABLE `data_wali` (
  `id_wali` int UNSIGNED NOT NULL,
  `id_pendaftar` int UNSIGNED NOT NULL,
  `nama_wali` varchar(150) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik_wali` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tahun_lahir_wali` year DEFAULT NULL,
  `pendidikan_wali` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pekerjaan_wali` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `penghasilan_wali` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `hp_wali` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `data_wali`
--

INSERT INTO `data_wali` (`id_wali`, `id_pendaftar`, `nama_wali`, `nik_wali`, `tahun_lahir_wali`, `pendidikan_wali`, `pekerjaan_wali`, `penghasilan_wali`, `hp_wali`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 09:21:55', '2025-12-06 09:21:55'),
(2, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 09:21:55', '2025-12-06 09:21:55');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint UNSIGNED NOT NULL,
  `version` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `class` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `group` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `namespace` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(1, '2026-01-01-000001', 'App\\Database\\Migrations\\CreatePendaftarTable', 'default', 'App', 1765012906, 1),
(2, '2026-01-01-000002', 'App\\Database\\Migrations\\CreateAlamatPendaftarTable', 'default', 'App', 1765012906, 1),
(3, '2026-01-01-000003', 'App\\Database\\Migrations\\CreateDataAyahTable', 'default', 'App', 1765012906, 1),
(4, '2026-01-01-000004', 'App\\Database\\Migrations\\CreateDataIbuTable', 'default', 'App', 1765012906, 1),
(5, '2026-01-01-000005', 'App\\Database\\Migrations\\CreateDataWaliTable', 'default', 'App', 1765012906, 1),
(6, '2026-01-01-000006', 'App\\Database\\Migrations\\CreateBansosPendaftarTable', 'default', 'App', 1765012906, 1),
(7, '2026-01-01-000007', 'App\\Database\\Migrations\\CreateAsalSekolahTable', 'default', 'App', 1765012906, 1),
(8, '2026-01-01-000008', 'App\\Database\\Migrations\\CreateAdminPanitiaTable', 'default', 'App', 1765012906, 1),
(9, '2026-01-01-000009', 'App\\Database\\Migrations\\CreatePengumumanTable', 'default', 'App', 1765161530, 2),
(10, '2026-01-01-000010', 'App\\Database\\Migrations\\CreatePengumumanBalasanTable', 'default', 'App', 1765161530, 2),
(11, '2026-01-01-000011', 'App\\Database\\Migrations\\CreatePengumumanLikeTable', 'default', 'App', 1765161530, 2);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pendaftar`
--

CREATE TABLE `pendaftar` (
  `id_pendaftar` int UNSIGNED NOT NULL,
  `nomor_pendaftaran` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `jalur_pendaftaran` enum('TSANAWIYYAH','MUALLIMIN') COLLATE utf8mb4_general_ci NOT NULL,
  `nisn` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nik` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `nama_lengkap` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_lahir` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `status_keluarga` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `anak_ke` tinyint UNSIGNED DEFAULT NULL,
  `jumlah_saudara` tinyint UNSIGNED DEFAULT NULL,
  `hobi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `cita_cita` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pernah_paud` tinyint(1) NOT NULL DEFAULT '0',
  `pernah_tk` tinyint(1) NOT NULL DEFAULT '0',
  `kebutuhan_disabilitas` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `imunisasi` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `no_hp` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ukuran_baju` varchar(10) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prestasi` text COLLATE utf8mb4_general_ci,
  `tanggal_daftar` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pendaftar`
--

INSERT INTO `pendaftar` (`id_pendaftar`, `nomor_pendaftaran`, `jalur_pendaftaran`, `nisn`, `nik`, `nama_lengkap`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `status_keluarga`, `anak_ke`, `jumlah_saudara`, `hobi`, `cita_cita`, `pernah_paud`, `pernah_tk`, `kebutuhan_disabilitas`, `imunisasi`, `no_hp`, `ukuran_baju`, `prestasi`, `tanggal_daftar`, `updated_at`, `deleted_at`) VALUES
(1, 'T2026-001', 'TSANAWIYYAH', '0051234567', '3273011234567890', 'Ahmad Fauzi', 'L', 'Bandung', '2012-05-15', 'Anak Kandung', 1, 2, 'Membaca', 'Ustadz', 1, 1, 'Tidak Ada', 'Lengkap', '081234567890', 'M', 'Juara 1 Hafalan Quran Tingkat Kecamatan', '2025-12-06 09:21:55', '2025-12-06 09:21:55', NULL),
(2, 'M2026-001', 'MUALLIMIN', '0059876543', '3273019876543210', 'Fatimah Zahra', 'P', 'Bandung', '2009-08-20', 'Anak Kandung', 2, 3, 'Menulis', 'Guru', 1, 1, 'Tidak Ada', 'Lengkap', '082345678901', 'S', 'Juara 2 Lomba Karya Tulis Ilmiah', '2025-12-06 09:21:55', '2025-12-06 09:21:55', NULL),
(3, 'T2025-002', 'TSANAWIYYAH', '2909292952', '2909292925215254', 'Isman Rahmani Yusron', 'L', 'Bandung', '2020-07-08', 'Anak Kandung', 1, 2, 'Masak', 'Chief', 1, 1, '', 'Lengkap', '08122125163', 'M', 'Juara masak', '2025-12-06 08:44:36', '2025-12-06 08:44:36', NULL),
(4, 'M2025-002', 'MUALLIMIN', '1234567890', '1234567891234567', 'Handoko Murtopo', 'L', 'Bandung', '2020-07-08', 'Anak Kandung', 1, 2, 'Masak', 'Chief', 1, 1, '', 'Lengkap', '08122125163', 'XXL', 'Juara Makan', '2025-12-06 13:12:44', '2025-12-06 13:12:44', NULL),
(5, 'M2025-003', 'MUALLIMIN', '1234567890', '1234567891234567', 'Handoko Murtopo', 'L', 'Bandung', '2020-07-08', 'Anak Tiri', 1, 2, 'Masak', 'Chief', 1, 1, '', 'Lengkap', '08122125163', 'M', 'Jago makan', '2025-12-06 13:24:51', '2025-12-06 13:24:51', NULL),
(6, 'M2025-004', 'MUALLIMIN', '1234567890', '1234567891234567', 'Handoko Murtopo', 'L', 'Bandung', '2020-07-08', 'Anak Kandung', 1, 2, 'Masak', 'Chief', 1, 1, '', 'Lengkap', '08122125163', 'M', '', '2025-12-06 20:33:50', '2025-12-06 20:33:50', NULL),
(7, 'T2025-003', 'TSANAWIYYAH', '1234567890', '1234567891234567', 'Handoko Murtopo', 'L', 'Bandung', '2020-07-08', 'Anak Kandung', 1, 2, 'Masak', 'Chief', 1, 1, '', 'Lengkap', '08122125163', 'S', '', '2025-12-07 07:22:45', '2025-12-07 07:22:45', NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id_pengumuman` int UNSIGNED NOT NULL,
  `id_admin` int UNSIGNED NOT NULL,
  `judul` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `konten` text COLLATE utf8mb4_general_ci NOT NULL,
  `gambar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman`
--

INSERT INTO `pengumuman` (`id_pengumuman`, `id_admin`, `judul`, `konten`, `gambar`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Tes Pengumuman', 'Ini adalah pengumuman baru!', '1765162099_7fff8eec57745f96ac11.png', 1, '2025-12-07 19:48:19', '2025-12-07 19:48:19');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman_balasan`
--

CREATE TABLE `pengumuman_balasan` (
  `id_balasan` int UNSIGNED NOT NULL,
  `id_pengumuman` int UNSIGNED NOT NULL,
  `parent_id` int UNSIGNED DEFAULT NULL COMMENT 'ID balasan parent untuk reply admin',
  `nama_pengirim` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `email_pengirim` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `isi_balasan` text COLLATE utf8mb4_general_ci NOT NULL,
  `is_admin_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'True jika balasan dari admin',
  `id_admin` int UNSIGNED DEFAULT NULL COMMENT 'ID admin jika balasan dari admin',
  `is_approved` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Status moderasi balasan',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman_balasan`
--

INSERT INTO `pengumuman_balasan` (`id_balasan`, `id_pengumuman`, `parent_id`, `nama_pengirim`, `email_pengirim`, `isi_balasan`, `is_admin_reply`, `id_admin`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Isman', 'rahmaniyusron@outlook.co.id', 'Saya mengerti dan bisa membaca pengumumannya!', 0, NULL, 1, '2025-12-07 20:00:23', '2025-12-07 20:02:45'),
(2, 1, 1, 'Super Administrator', 'admin@persis31.com', 'Terimakasih!', 1, 1, 1, '2025-12-07 20:00:46', '2025-12-07 20:00:46');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pengumuman_like`
--

CREATE TABLE `pengumuman_like` (
  `id_like` int UNSIGNED NOT NULL,
  `id_pengumuman` int UNSIGNED DEFAULT NULL COMMENT 'ID pengumuman yang di-like (null jika like pada balasan)',
  `id_balasan` int UNSIGNED DEFAULT NULL COMMENT 'ID balasan yang di-like (null jika like pada pengumuman)',
  `session_id` varchar(128) COLLATE utf8mb4_general_ci NOT NULL COMMENT 'Session ID untuk identifikasi user publik',
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `pengumuman_like`
--

INSERT INTO `pengumuman_like` (`id_like`, `id_pengumuman`, `id_balasan`, `session_id`, `ip_address`, `created_at`) VALUES
(1, 1, NULL, '7f3e57dbbbc01c88efb1e3601ce746e6', '::1', '2025-12-08 03:00:07');

--
-- Indeks untuk tabel yang dibuang
--

--
-- Indeks untuk tabel `admin_panitia`
--
ALTER TABLE `admin_panitia`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `role_panitia` (`role_panitia`);

--
-- Indeks untuk tabel `alamat_pendaftar`
--
ALTER TABLE `alamat_pendaftar`
  ADD PRIMARY KEY (`id_alamat`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `asal_sekolah`
--
ALTER TABLE `asal_sekolah`
  ADD PRIMARY KEY (`id_sekolah`),
  ADD KEY `id_pendaftar` (`id_pendaftar`),
  ADD KEY `npsn` (`npsn`);

--
-- Indeks untuk tabel `bansos_pendaftar`
--
ALTER TABLE `bansos_pendaftar`
  ADD PRIMARY KEY (`id_bansos`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `data_ayah`
--
ALTER TABLE `data_ayah`
  ADD PRIMARY KEY (`id_ayah`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `data_ibu`
--
ALTER TABLE `data_ibu`
  ADD PRIMARY KEY (`id_ibu`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `data_wali`
--
ALTER TABLE `data_wali`
  ADD PRIMARY KEY (`id_wali`),
  ADD KEY `id_pendaftar` (`id_pendaftar`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  ADD PRIMARY KEY (`id_pendaftar`),
  ADD UNIQUE KEY `nomor_pendaftaran` (`nomor_pendaftaran`),
  ADD KEY `jalur_pendaftaran` (`jalur_pendaftaran`),
  ADD KEY `tanggal_daftar` (`tanggal_daftar`),
  ADD KEY `nama_lengkap` (`nama_lengkap`);

--
-- Indeks untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id_pengumuman`),
  ADD KEY `id_admin` (`id_admin`),
  ADD KEY `is_active` (`is_active`),
  ADD KEY `created_at` (`created_at`);

--
-- Indeks untuk tabel `pengumuman_balasan`
--
ALTER TABLE `pengumuman_balasan`
  ADD PRIMARY KEY (`id_balasan`),
  ADD KEY `id_pengumuman` (`id_pengumuman`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `is_admin_reply` (`is_admin_reply`),
  ADD KEY `created_at` (`created_at`);

--
-- Indeks untuk tabel `pengumuman_like`
--
ALTER TABLE `pengumuman_like`
  ADD PRIMARY KEY (`id_like`),
  ADD KEY `id_pengumuman` (`id_pengumuman`),
  ADD KEY `id_balasan` (`id_balasan`),
  ADD KEY `session_id` (`session_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `admin_panitia`
--
ALTER TABLE `admin_panitia`
  MODIFY `id_admin` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `alamat_pendaftar`
--
ALTER TABLE `alamat_pendaftar`
  MODIFY `id_alamat` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `asal_sekolah`
--
ALTER TABLE `asal_sekolah`
  MODIFY `id_sekolah` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `bansos_pendaftar`
--
ALTER TABLE `bansos_pendaftar`
  MODIFY `id_bansos` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `data_ayah`
--
ALTER TABLE `data_ayah`
  MODIFY `id_ayah` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `data_ibu`
--
ALTER TABLE `data_ibu`
  MODIFY `id_ibu` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `data_wali`
--
ALTER TABLE `data_wali`
  MODIFY `id_wali` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT untuk tabel `pendaftar`
--
ALTER TABLE `pendaftar`
  MODIFY `id_pendaftar` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id_pengumuman` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `pengumuman_balasan`
--
ALTER TABLE `pengumuman_balasan`
  MODIFY `id_balasan` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT untuk tabel `pengumuman_like`
--
ALTER TABLE `pengumuman_like`
  MODIFY `id_like` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `alamat_pendaftar`
--
ALTER TABLE `alamat_pendaftar`
  ADD CONSTRAINT `alamat_pendaftar_id_pendaftar_foreign` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `asal_sekolah`
--
ALTER TABLE `asal_sekolah`
  ADD CONSTRAINT `asal_sekolah_id_pendaftar_foreign` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `bansos_pendaftar`
--
ALTER TABLE `bansos_pendaftar`
  ADD CONSTRAINT `bansos_pendaftar_id_pendaftar_foreign` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `data_ayah`
--
ALTER TABLE `data_ayah`
  ADD CONSTRAINT `data_ayah_id_pendaftar_foreign` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `data_ibu`
--
ALTER TABLE `data_ibu`
  ADD CONSTRAINT `data_ibu_id_pendaftar_foreign` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `data_wali`
--
ALTER TABLE `data_wali`
  ADD CONSTRAINT `data_wali_id_pendaftar_foreign` FOREIGN KEY (`id_pendaftar`) REFERENCES `pendaftar` (`id_pendaftar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `pengumuman_id_admin_foreign` FOREIGN KEY (`id_admin`) REFERENCES `admin_panitia` (`id_admin`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumuman_balasan`
--
ALTER TABLE `pengumuman_balasan`
  ADD CONSTRAINT `pengumuman_balasan_id_pengumuman_foreign` FOREIGN KEY (`id_pengumuman`) REFERENCES `pengumuman` (`id_pengumuman`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ketidakleluasaan untuk tabel `pengumuman_like`
--
ALTER TABLE `pengumuman_like`
  ADD CONSTRAINT `pengumuman_like_id_balasan_foreign` FOREIGN KEY (`id_balasan`) REFERENCES `pengumuman_balasan` (`id_balasan`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pengumuman_like_id_pengumuman_foreign` FOREIGN KEY (`id_pengumuman`) REFERENCES `pengumuman` (`id_pengumuman`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
