<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title) ?></title>
    <meta name="description" content="Pendaftaran Berhasil - Pesantren Persatuan Islam 31 Banjaran">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/logo/favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/logo/favicon-32x32.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo/apple-touch-icon.png') ?>">

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
        }

        body {
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 0;
        }

        .success-container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .success-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: var(--primary-green);
            font-size: 3rem;
            animation: successPulse 1s ease-in-out;
        }

        @keyframes successPulse {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
        }

        .success-title {
            font-size: 2rem;
            font-weight: 700;
            margin: 0;
        }

        .success-body {
            padding: 40px 30px;
        }

        .nomor-pendaftaran {
            background: var(--light-green);
            border: 3px dashed var(--primary-green);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }

        .nomor-pendaftaran-label {
            font-size: 0.9rem;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: 600;
        }

        .nomor-pendaftaran-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark-green);
            letter-spacing: 2px;
        }

        .info-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-card h5 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 15px;
        }

        .info-item {
            display: flex;
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: 600;
            color: #666;
            min-width: 150px;
        }

        .info-value {
            color: #333;
            flex: 1;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 179, 74, 0.3);
            display: inline-block;
            text-decoration: none;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 179, 74, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: var(--secondary-yellow);
            border: none;
            color: #333;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            display: inline-block;
            text-decoration: none;
            margin-left: 10px;
        }

        .btn-secondary-custom:hover {
            background: #e0b518;
            transform: translateY(-2px);
            color: #333;
        }

        .important-note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .important-note p {
            margin: 0;
            color: #856404;
        }

        @media print {
            .no-print {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .success-title {
                font-size: 1.5rem;
            }

            .nomor-pendaftaran-value {
                font-size: 1.8rem;
            }

            .btn-primary-custom,
            .btn-secondary-custom {
                display: block;
                width: 100%;
                margin: 10px 0;
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
                <h1 class="success-title">Pendaftaran Berhasil!</h1>
                <p>Terima kasih telah mendaftar di Pesantren Persatuan Islam 31 Banjaran</p>
            </div>

            <!-- Body -->
            <div class="success-body">
                <!-- Nomor Pendaftaran -->
                <div class="nomor-pendaftaran">
                    <div class="nomor-pendaftaran-label">Nomor Pendaftaran Anda</div>
                    <div class="nomor-pendaftaran-value"><?= esc($pendaftar['nomor_pendaftaran']) ?></div>
                </div>

                <!-- Info Card -->
                <div class="info-card">
                    <h5><i class="icofont-ui-user"></i> Data Pendaftar</h5>
                    <div class="info-item">
                        <div class="info-label">Nama Lengkap:</div>
                        <div class="info-value"><?= esc($pendaftar['nama_lengkap']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Jalur Pendaftaran:</div>
                        <div class="info-value"><?= esc($pendaftar['jalur_pendaftaran']) ?></div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Tanggal Daftar:</div>
                        <div class="info-value"><?= date('d F Y, H:i', strtotime($pendaftar['tanggal_daftar'])) ?> WIB</div>
                    </div>
                </div>

                <!-- Important Note -->
                <div class="important-note">
                    <p><strong><i class="icofont-info-circle"></i> Penting!</strong></p>
                    <p>Simpan nomor pendaftaran Anda dengan baik. Nomor ini akan digunakan untuk proses selanjutnya.</p>
                </div>

                <!-- Langkah Selanjutnya -->
                <div class="info-card mt-4">
                    <h5><i class="icofont-list"></i> Langkah Selanjutnya</h5>
                    <ol style="margin: 0; padding-left: 20px;">
                        <li>Catat dan simpan nomor pendaftaran Anda</li>
                        <li>Screenshot atau print halaman ini sebagai bukti pendaftaran</li>
                        <li>Tunggu informasi lebih lanjut melalui email atau WhatsApp</li>
                        <li>Pantau terus website kami untuk pengumuman jadwal tes dan wawancara</li>
                        <li>Siapkan dokumen-dokumen yang diperlukan untuk verifikasi</li>
                    </ol>
                </div>

                <!-- Actions -->
                <div class="text-center mt-4 no-print">
                    <a href="javascript:window.print()" class="btn btn-primary-custom">
                        <i class="icofont-print"></i> Cetak Bukti Pendaftaran
                    </a>
                    <a href="<?= base_url('/') ?>" class="btn btn-secondary-custom">
                        <i class="icofont-home"></i> Kembali ke Beranda
                    </a>
                </div>

                <!-- Footer Note -->
                <div class="text-center mt-4" style="color: #666; font-size: 0.9rem;">
                    <p>Jika ada pertanyaan, silakan hubungi panitia PSB di:</p>
                    <p>
                        <i class="icofont-phone"></i> 0812-3456-7890 |
                        <i class="icofont-email"></i> psb@persis31.com
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>
</html>
