<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title) ?> - PSB Persis 31 Banjaran</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">

    <!-- Hafsa Template CSS -->
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
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            padding: 30px;
        }

        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: var(--primary-green);
            color: white;
            transform: translateX(-5px);
        }

        .detail-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-info h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-info p {
            font-size: 1rem;
            color: var(--secondary-yellow);
            margin: 0;
        }

        .registration-number {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
        }

        .registration-number span {
            font-size: 0.8rem;
            display: block;
            margin-bottom: 5px;
        }

        .registration-number strong {
            font-size: 1.5rem;
        }

        .detail-body {
            background: white;
            padding: 30px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-green);
            border-bottom: 2px solid var(--light-green);
            padding-bottom: 10px;
            margin-bottom: 20px;
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .section-title i {
            font-size: 1.4rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1rem;
            color: #333;
            font-weight: 500;
        }

        .info-value.empty {
            color: #ccc;
            font-style: italic;
        }

        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-tsanawiyyah {
            background: var(--light-green);
            color: var(--dark-green);
        }

        .badge-muallimin {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-male {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-female {
            background: #fce7f3;
            color: #be185d;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 179, 74, 0.3);
            color: white;
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
            color: white;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .detail-header {
                padding: 20px;
            }

            .header-info h1 {
                font-size: 1.4rem;
            }

            .detail-body {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .back-btn,
            .action-buttons {
                display: none;
            }

            .detail-header {
                background: var(--primary-green);
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="detail-container animate__animated animate__fadeIn">
        <a href="javascript:history.back()" class="back-btn">
            <i class="icofont-arrow-left"></i>
            Kembali ke Dashboard
        </a>

        <div class="detail-card">
            <!-- Header -->
            <div class="detail-header">
                <div class="header-info">
                    <h1><?= esc($pendaftar['nama_lengkap']) ?></h1>
                    <p>
                        <span class="badge <?= $pendaftar['jalur_pendaftaran'] === 'TSANAWIYYAH' ? 'badge-tsanawiyyah' : 'badge-muallimin' ?>">
                            <?= esc($pendaftar['jalur_pendaftaran']) ?>
                        </span>
                    </p>
                </div>
                <div class="registration-number">
                    <span>Nomor Pendaftaran</span>
                    <strong><?= esc($pendaftar['nomor_pendaftaran']) ?></strong>
                </div>
            </div>

            <!-- Body -->
            <div class="detail-body">
                <!-- Data Diri -->
                <h3 class="section-title">
                    <i class="icofont-user"></i>
                    Data Diri Calon Santri
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">NISN</span>
                        <span class="info-value"><?= esc($pendaftar['nisn'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIK</span>
                        <span class="info-value"><?= esc($pendaftar['nik'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value"><?= esc($pendaftar['nama_lengkap']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-value">
                            <span class="badge <?= $pendaftar['jenis_kelamin'] === 'L' ? 'badge-male' : 'badge-female' ?>">
                                <?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tempat, Tanggal Lahir</span>
                        <span class="info-value"><?= esc($pendaftar['tempat_lahir']) ?>, <?= date('d F Y', strtotime($pendaftar['tanggal_lahir'])) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">No. HP</span>
                        <span class="info-value"><?= esc($pendaftar['no_hp'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status dalam Keluarga</span>
                        <span class="info-value"><?= esc($pendaftar['status_keluarga'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Anak ke / Jumlah Saudara</span>
                        <span class="info-value"><?= esc($pendaftar['anak_ke'] ?? '-') ?> dari <?= esc($pendaftar['jumlah_saudara'] ?? '-') ?> bersaudara</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ukuran Baju</span>
                        <span class="info-value"><?= esc($pendaftar['ukuran_baju'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Daftar</span>
                        <span class="info-value"><?= date('d F Y, H:i', strtotime($pendaftar['tanggal_daftar'])) ?> WIB</span>
                    </div>
                </div>

                <!-- Alamat -->
                <?php if ($alamat): ?>
                    <h3 class="section-title">
                        <i class="icofont-location-pin"></i>
                        Alamat Domisili
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Alamat</span>
                            <span class="info-value"><?= esc($alamat['alamat'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Desa/Kelurahan</span>
                            <span class="info-value"><?= esc($alamat['desa'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kecamatan</span>
                            <span class="info-value"><?= esc($alamat['kecamatan'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kabupaten/Kota</span>
                            <span class="info-value"><?= esc($alamat['kabupaten'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Provinsi</span>
                            <span class="info-value"><?= esc($alamat['provinsi'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kode Pos</span>
                            <span class="info-value"><?= esc($alamat['kode_pos'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jarak ke Pesantren</span>
                            <span class="info-value"><?= esc($alamat['jarak_ke_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Transportasi</span>
                            <span class="info-value"><?= esc($alamat['transportasi'] ?? '-') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Data Ayah -->
                <?php if ($ayah): ?>
                    <h3 class="section-title">
                        <i class="icofont-user-male"></i>
                        Data Ayah Kandung
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value"><?= esc($ayah['nama_ayah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NIK</span>
                            <span class="info-value"><?= esc($ayah['nik_ayah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tempat, Tanggal Lahir</span>
                            <span class="info-value"><?= esc($ayah['tempat_lahir_ayah'] ?? '-') ?>, <?= $ayah['tanggal_lahir_ayah'] ? date('d F Y', strtotime($ayah['tanggal_lahir_ayah'])) : '-' ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value"><?= esc($ayah['status_ayah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pendidikan Terakhir</span>
                            <span class="info-value"><?= esc($ayah['pendidikan_ayah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pekerjaan</span>
                            <span class="info-value"><?= esc($ayah['pekerjaan_ayah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Penghasilan</span>
                            <span class="info-value"><?= esc($ayah['penghasilan_ayah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. HP</span>
                            <span class="info-value"><?= esc($ayah['hp_ayah'] ?? '-') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Data Ibu -->
                <?php if ($ibu): ?>
                    <h3 class="section-title">
                        <i class="icofont-user-female"></i>
                        Data Ibu Kandung
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Lengkap</span>
                            <span class="info-value"><?= esc($ibu['nama_ibu'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NIK</span>
                            <span class="info-value"><?= esc($ibu['nik_ibu'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tempat, Tanggal Lahir</span>
                            <span class="info-value"><?= esc($ibu['tempat_lahir_ibu'] ?? '-') ?>, <?= $ibu['tanggal_lahir_ibu'] ? date('d F Y', strtotime($ibu['tanggal_lahir_ibu'])) : '-' ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status</span>
                            <span class="info-value"><?= esc($ibu['status_ibu'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pendidikan Terakhir</span>
                            <span class="info-value"><?= esc($ibu['pendidikan_ibu'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Pekerjaan</span>
                            <span class="info-value"><?= esc($ibu['pekerjaan_ibu'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Penghasilan</span>
                            <span class="info-value"><?= esc($ibu['penghasilan_ibu'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. HP</span>
                            <span class="info-value"><?= esc($ibu['hp_ibu'] ?? '-') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Data Sekolah -->
                <?php if ($sekolah): ?>
                    <h3 class="section-title">
                        <i class="icofont-education"></i>
                        Asal Sekolah
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Sekolah</span>
                            <span class="info-value"><?= esc($sekolah['nama_asal_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NPSN</span>
                            <span class="info-value"><?= esc($sekolah['npsn'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenjang</span>
                            <span class="info-value"><?= esc($sekolah['jenjang_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status Sekolah</span>
                            <span class="info-value"><?= esc($sekolah['status_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Lokasi</span>
                            <span class="info-value"><?= esc($sekolah['lokasi_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Asal Jenjang</span>
                            <span class="info-value"><?= esc($sekolah['asal_jenjang'] ?? '-') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('pendaftaran/download-pdf/' . $pendaftar['nomor_pendaftaran']) ?>" class="btn-action btn-primary-custom" target="_blank">
                        <i class="icofont-file-pdf"></i> Download PDF Lengkap
                    </a>
                    <a href="<?= base_url('pendaftaran/download-kartu/' . $pendaftar['nomor_pendaftaran']) ?>" class="btn-action btn-danger-custom" target="_blank">
                        <i class="icofont-id-card"></i> Download Kartu Pendaftaran
                    </a>
                    <button onclick="window.print()" class="btn-action btn-primary-custom">
                        <i class="icofont-print"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>
