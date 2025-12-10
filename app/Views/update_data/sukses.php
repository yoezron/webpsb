<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title) ?></title>
    <meta name="description" content="Update Data Berhasil - Pesantren Persatuan Islam 31 Banjaran">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">

    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-green: #e8f5e9;
            --warning-orange: #fd7e14;
        }

        body {
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 30px 0;
        }

        .success-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .success-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 50px 30px;
            text-align: center;
        }

        .success-icon {
            font-size: 80px;
            margin-bottom: 20px;
            animation: bounce 1s ease infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .success-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 15px 0;
        }

        .success-header p {
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.95;
        }

        .registration-number {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 30px;
            border-radius: 50px;
            margin-top: 20px;
            font-size: 1.5rem;
            font-weight: 700;
            letter-spacing: 2px;
        }

        .success-body {
            padding: 40px 30px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .info-card h5 {
            color: var(--dark-green);
            font-weight: 700;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-green);
        }

        .data-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #dee2e6;
        }

        .data-row:last-child {
            border-bottom: none;
        }

        .data-label {
            font-weight: 600;
            color: #495057;
        }

        .data-value {
            color: #212529;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border: none;
            color: white;
            padding: 15px 35px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 179, 74, 0.3);
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 179, 74, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            color: white;
            padding: 15px 35px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn-secondary-custom:hover {
            background: #5a6268;
            color: white;
        }

        .alert-success-box {
            background: #d4edda;
            border-left: 5px solid var(--primary-green);
            border-radius: 0 10px 10px 0;
            padding: 20px;
            margin-bottom: 30px;
        }

        .alert-success-box h5 {
            color: #155724;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .alert-success-box p {
            color: #155724;
            margin: 0;
        }

        .timestamp-badge {
            background: var(--warning-orange);
            color: white;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        @media (max-width: 768px) {
            .success-header h1 {
                font-size: 1.5rem;
            }

            .registration-number {
                font-size: 1.2rem;
                padding: 12px 20px;
            }

            .success-body {
                padding: 30px 20px;
            }

            .data-row {
                flex-direction: column;
            }

            .data-label {
                margin-bottom: 5px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="success-container animate__animated animate__fadeIn">
            <!-- Header -->
            <div class="success-header">
                <div class="success-icon">
                    <i class="icofont-check-circled"></i>
                </div>
                <h1>Data Berhasil Diperbarui!</h1>
                <p>Pesantren Persatuan Islam 31 Banjaran</p>
                <p>Tahun Ajaran <?= esc($year) ?>/<?= esc($year + 1) ?></p>
                <div class="registration-number">
                    <?= esc($pendaftar['nomor_pendaftaran']) ?>
                </div>
            </div>

            <!-- Body -->
            <div class="success-body">
                <!-- Success Alert -->
                <div class="alert-success-box">
                    <h5><i class="icofont-check-circled"></i> Update Berhasil!</h5>
                    <p>Data pendaftaran Anda telah berhasil diperbarui pada <span class="timestamp-badge"><?= date('d F Y, H:i') ?> WIB</span></p>
                </div>

                <!-- Data Diri -->
                <div class="info-card">
                    <h5><i class="icofont-ui-user"></i> Data Diri</h5>
                    <div class="data-row">
                        <span class="data-label">Nama Lengkap</span>
                        <span class="data-value"><?= esc($pendaftar['nama_lengkap']) ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">NISN</span>
                        <span class="data-value"><?= esc($pendaftar['nisn'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">NIK</span>
                        <span class="data-value"><?= esc($pendaftar['nik'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Jenis Kelamin</span>
                        <span class="data-value"><?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Tempat, Tanggal Lahir</span>
                        <span class="data-value"><?= esc($pendaftar['tempat_lahir'] ?: '-') ?>, <?= $pendaftar['tanggal_lahir'] ? date('d F Y', strtotime($pendaftar['tanggal_lahir'])) : '-' ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Jalur Pendaftaran</span>
                        <span class="data-value"><?= esc($pendaftar['jalur_pendaftaran']) ?></span>
                    </div>
                </div>

                <!-- Data Alamat -->
                <?php if ($alamat): ?>
                <div class="info-card">
                    <h5><i class="icofont-location-pin"></i> Data Alamat</h5>
                    <div class="data-row">
                        <span class="data-label">Alamat</span>
                        <span class="data-value"><?= esc($alamat['alamat'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Desa/Kelurahan</span>
                        <span class="data-value"><?= esc($alamat['desa'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Kecamatan</span>
                        <span class="data-value"><?= esc($alamat['kecamatan'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Kabupaten/Kota</span>
                        <span class="data-value"><?= esc($alamat['kabupaten'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Provinsi</span>
                        <span class="data-value"><?= esc($alamat['provinsi'] ?: '-') ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Data Orang Tua -->
                <div class="info-card">
                    <h5><i class="icofont-ui-user-group"></i> Data Orang Tua</h5>
                    <?php if ($ayah): ?>
                    <div class="data-row">
                        <span class="data-label">Nama Ayah</span>
                        <span class="data-value"><?= esc($ayah['nama_ayah'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">No. HP Ayah</span>
                        <span class="data-value"><?= esc($ayah['hp_ayah'] ?: '-') ?></span>
                    </div>
                    <?php endif; ?>
                    <?php if ($ibu): ?>
                    <div class="data-row">
                        <span class="data-label">Nama Ibu</span>
                        <span class="data-value"><?= esc($ibu['nama_ibu'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">No. HP Ibu</span>
                        <span class="data-value"><?= esc($ibu['hp_ibu'] ?: '-') ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Data Asal Sekolah -->
                <?php if ($sekolah): ?>
                <div class="info-card">
                    <h5><i class="icofont-university"></i> Data Asal Sekolah</h5>
                    <div class="data-row">
                        <span class="data-label">Nama Sekolah</span>
                        <span class="data-value"><?= esc($sekolah['nama_asal_sekolah'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Jenjang</span>
                        <span class="data-value"><?= esc($sekolah['jenjang_sekolah'] ?: '-') ?></span>
                    </div>
                    <div class="data-row">
                        <span class="data-label">Lokasi</span>
                        <span class="data-value"><?= esc($sekolah['lokasi_sekolah'] ?: '-') ?></span>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="text-center mt-4">
                    <a href="<?= base_url('pendaftaran/download-pdf/' . $pendaftar['nomor_pendaftaran']) ?>" class="btn btn-primary-custom me-3 mb-3">
                        <i class="icofont-download"></i> Download Data Lengkap (PDF)
                    </a>
                    <a href="<?= base_url('pendaftaran/download-kartu/' . $pendaftar['nomor_pendaftaran']) ?>" class="btn btn-secondary-custom mb-3">
                        <i class="icofont-id-card"></i> Download Kartu Pendaftaran
                    </a>
                </div>

                <div class="text-center mt-3">
                    <a href="<?= base_url('/') ?>" class="text-muted">
                        <i class="icofont-arrow-left"></i> Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>
