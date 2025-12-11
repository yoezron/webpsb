<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <title><?= esc($title) ?></title>
    <meta name="description" content="Update Data Pendaftar - Pesantren Persatuan Islam 31 Banjaran">

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
            --danger-red: #dc3545;
            --info-blue: #17a2b8;
        }

        body {
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .verification-container {
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .verification-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .verification-header-logo {
            width: 120px;
            height: auto;
            margin: 0 auto 20px;
        }

        .verification-header-logo img {
            width: 100%;
            height: auto;
            display: block;
        }

        .verification-header h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .verification-header p {
            font-size: 1rem;
            margin: 0;
            opacity: 0.9;
        }

        .verification-body {
            padding: 40px 30px;
        }

        .info-box {
            background: #e3f2fd;
            border-left: 4px solid var(--info-blue);
            padding: 15px 20px;
            border-radius: 0 10px 10px 0;
            margin-bottom: 30px;
        }

        .info-box h5 {
            color: var(--info-blue);
            font-weight: 700;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .info-box p {
            color: #0c5460;
            margin: 0;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .required::after {
            content: ' *';
            color: var(--danger-red);
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(26, 179, 74, 0.25);
        }

        .form-control.is-invalid {
            border-color: var(--danger-red);
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
            width: 100%;
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
            padding: 12px 30px;
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

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .alert-info {
            background: #d1ecf1;
            color: #0c5460;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #6c757d;
            text-decoration: none;
            font-size: 0.95rem;
        }

        .back-link a:hover {
            color: var(--primary-green);
            text-decoration: underline;
        }

        .form-text {
            font-size: 0.85rem;
            color: #6c757d;
        }

        @media (max-width: 576px) {
            .verification-header h1 {
                font-size: 1.3rem;
            }

            .verification-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>

<body>
    <div class="verification-container animate__animated animate__fadeIn">
        <!-- Header -->
        <div class="verification-header">
            <div class="verification-header-logo">
                <img src="<?= base_url('assets/images/logo/01.png') ?>" alt="Logo Pesantren Persatuan Islam 31 Banjaran">
            </div>
            <h1>Update Data Pendaftar</h1>
            <p>Pesantren Persatuan Islam 31 Banjaran</p>
            <p>Tahun Ajaran <?= esc($year) ?>/<?= esc($year + 1) ?></p>
        </div>

        <!-- Body -->
        <div class="verification-body">
            <!-- Info Box -->
            <div class="info-box">
                <h5><i class="icofont-info-circle"></i> Informasi</h5>
                <p>
                    Untuk memperbarui data pendaftaran, silakan masukkan <strong>Nomor Pendaftaran</strong> dan <strong>NISN</strong> Anda yang telah didaftarkan sebelumnya. Pastikan kedua data tersebut sesuai dengan data yang telah Anda isikan saat pendaftaran.
                </p>
            </div>

            <!-- Alert Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="icofont-check-circled"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="icofont-warning"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('info')): ?>
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="icofont-info-circle"></i> <?= session()->getFlashdata('info') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Verification Form -->
            <form method="post" action="<?= base_url('update-data/verifikasi') ?>" id="verification-form">
                <?= csrf_field() ?>

                <!-- Nomor Pendaftaran -->
                <div class="mb-4">
                    <label for="nomor_pendaftaran" class="form-label required">Nomor Pendaftaran</label>
                    <input type="text" class="form-control" id="nomor_pendaftaran" name="nomor_pendaftaran"
                        value="<?= old('nomor_pendaftaran') ?>" placeholder="Contoh: T2026-001 atau M2026-001" required>
                    <div class="form-text">Nomor pendaftaran yang Anda terima saat mendaftar.</div>
                </div>

                <!-- NISN -->
                <div class="mb-4">
                    <label for="nisn" class="form-label required">NISN</label>
                    <input type="text" class="form-control numeric-only" id="nisn" name="nisn"
                        value="<?= old('nisn') ?>" placeholder="Masukkan 10 digit NISN" maxlength="10" required>
                    <div class="form-text">Nomor Induk Siswa Nasional (10 digit).</div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary-custom" id="btn-submit">
                    <i class="icofont-search-1"></i> Cari Data Saya
                </button>
            </form>

            <!-- Back Link -->
            <div class="back-link">
                <a href="<?= base_url('/') ?>">
                    <i class="icofont-arrow-left"></i> Kembali ke Halaman Utama
                </a>
            </div>
        </div>
    </div>

    <!-- Include Components -->
    <?php include APPPATH . 'Views/components/toast.php'; ?>
    <?php include APPPATH . 'Views/components/loading.php'; ?>

    <!-- JS -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            // Numeric validation for NISN
            $('.numeric-only').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Form submission
            $('#verification-form').on('submit', function() {
                showLoading('Mencari data pendaftaran...');
                setButtonLoading($('#btn-submit')[0], true, 'Mencari...');
            });

            // Auto dismiss alerts
            setTimeout(function() {
                $('.alert-dismissible').fadeOut('slow');
            }, 8000);
        });
    </script>
</body>

</html>
