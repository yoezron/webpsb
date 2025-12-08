<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Meta Tags -->
    <title><?= esc($title) ?></title>
    <meta name="description" content="<?= esc($meta_description) ?>">
    <meta name="keywords" content="<?= esc($meta_keywords) ?>">
    <meta name="author" content="Pesantren Persatuan Islam 31 Banjaran">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= esc($title) ?>">
    <meta property="og:description" content="<?= esc($meta_description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:image" content="<?= base_url('assets/images/logo/01.png') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/logo/favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/logo/favicon-32x32.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo/01.png') ?>">

    <!-- Hafsa Template CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/lightcase.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/swiper.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Custom Branding CSS -->
    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-yellow: #f5d565;
        }

        /* Custom Branding */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border: none;
            color: white;
            padding: 18px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 179, 74, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-primary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(26, 179, 74, 0.4);
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
            color: white;
        }

        .btn-secondary-custom {
            background: linear-gradient(135deg, var(--secondary-yellow) 0%, #e0b518 100%);
            border: none;
            color: #333;
            padding: 18px 40px;
            font-size: 18px;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(243, 198, 35, 0.3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-secondary-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(243, 198, 35, 0.4);
            background: linear-gradient(135deg, #e0b518 0%, var(--secondary-yellow) 100%);
            color: #333;
        }

        .hero-section {
            min-height: 100vh;
            display: flex;
            align-items: center;
            background: linear-gradient(135deg, rgba(26, 179, 74, 0.95) 0%, rgba(21, 138, 58, 0.95) 100%),
                url('<?= base_url('assets/images/banner/01.png') ?>') center/cover;
            position: relative;
            padding: 80px 0;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 20% 50%, rgba(243, 198, 35, 0.1) 0%, transparent 50%);
        }

        .hero-content {
            position: relative;
            z-index: 2;
            color: white;
        }

        .hero-logo {
            width: 250px;
            /* Ubah height menjadi auto agar mengikuti proporsi logo asli */
            height: auto;
            margin: 0 auto 30px;

            /* Baris berikut dihapus/dikomentari agar tidak ada latar lingkaran */
            /* background: rgba(255, 255, 255, 0.95); */
            /* border-radius: 50%; */
            /* padding: 15px; */
            /* box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2); */

            animation: fadeInDown 1s ease;
        }

        .hero-logo img {
            width: 100%;
            height: auto;
            /* Pastikan rasio gambar tetap terjaga */
            display: block;
            /* Menghilangkan celah default pada elemen inline */
            filter: drop-shadow(0 10px 10px rgba(0, 0, 0, 0.3));
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 30px;
            color: var(--light-yellow);
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.3);
        }

        .hero-text {
            font-size: 1.1rem;
            margin-bottom: 40px;
            line-height: 1.8;
            max-width: 700px;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-top: 40px;
        }

        .info-badge {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 20px 30px;
            border-radius: 15px;
            display: inline-block;
            margin-bottom: 30px;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .info-badge h3 {
            color: var(--secondary-yellow);
            font-size: 1.8rem;
            margin: 0;
            font-weight: 700;
        }

        .info-badge p {
            margin: 5px 0 0 0;
            font-size: 1rem;
            color: white;
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: linear-gradient(135deg, rgba(243, 198, 35, 0.4) 0%, rgba(21, 138, 58, 0.95) 100%), url('<?= base_url('assets/images/banner/01.png') ?>') center/cover;
        }

        .feature-card {
            background: white;
            padding: 40px 30px;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            border-top: 4px solid var(--primary-green);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(26, 179, 74, 0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 2rem;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .feature-text {
            color: #666;
            line-height: 1.8;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-logo {
                width: 120px;
                height: 120px;
                margin-bottom: 20px;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-subtitle {
                font-size: 1.2rem;
            }

            .hero-text {
                font-size: 1rem;
            }

            .cta-buttons {
                flex-direction: column;
            }

            .btn-primary-custom,
            .btn-secondary-custom {
                width: 100%;
                padding: 15px 30px;
                font-size: 16px;
            }

            .info-badge {
                padding: 15px 20px;
            }

            .info-badge h3 {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 576px) {
            .hero-logo {
                width: 150px;
                height: 150px;
                margin-bottom: 15px;
                padding: 10px;
            }

            .hero-title {
                font-size: 1.8rem;
            }

            .hero-subtitle {
                font-size: 1rem;
            }

            .feature-card {
                padding: 30px 20px;
            }
        }

        /* Announcements Section */
        .announcements-section {
            background: #f8f9fa;
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
        }

        .section-subtitle {
            color: #666;
            font-size: 1.1rem;
        }

        .announcement-card-landing {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .announcement-card-landing:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .announcement-img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .announcement-img-placeholder {
            width: 100%;
            height: 180px;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .announcement-card-body {
            padding: 20px;
        }

        .announcement-card-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .announcement-card-title a {
            color: #333;
            text-decoration: none;
        }

        .announcement-card-title a:hover {
            color: var(--primary-green);
        }

        .announcement-card-meta {
            color: #888;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .announcement-card-text {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .announcement-card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .announcement-stats {
            color: #666;
            font-size: 0.9rem;
        }

        .btn-read-more-landing {
            color: var(--primary-green);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn-read-more-landing:hover {
            color: var(--dark-green);
        }

        /* Footer */
        .footer {
            background: #222;
            color: white;
            padding: 30px 0;
            text-align: center;
        }

        .footer a {
            color: var(--secondary-yellow);
            text-decoration: none;
        }

        .footer a:hover {
            color: var(--primary-green);
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <div class="hero-content text-center">
                        <!-- Logo -->
                        <div class="hero-logo">
                            <img src="<?= base_url('assets/images/logo/01.png') ?>" alt="Logo Pesantren Persatuan Islam 31 Banjaran">
                        </div>

                        <!-- Info Badge -->
                        <div class="info-badge">
                            <h3>Tahun Ajaran <?= esc($year) ?>/<?= esc($year + 1) ?></h3>
                            <p>Pendaftaran Dibuka!</p>
                        </div>

                        <!-- Hero Title -->
                        <h1 class="hero-title animate__animated animate__fadeInDown">
                            Pendaftaran Santri Baru
                        </h1>

                        <!-- Hero Subtitle -->
                        <p class="hero-subtitle animate__animated animate__fadeInUp">
                            Pesantren Persatuan Islam 31 Banjaran
                        </p>

                        <!-- Hero Text -->
                        <p class="hero-text mx-auto animate__animated animate__fadeIn">
                            Selamat datang di sistem pendaftaran online Pesantren Persatuan Islam 31 Banjaran.
                            Silakan pilih tingkat pendidikan yang Anda inginkan untuk memulai proses pendaftaran.
                            Kami menawarkan pendidikan Islam yang berkualitas dengan metode pembelajaran modern.
                        </p>

                        <!-- CTA Buttons -->
                        <div class="cta-buttons justify-content-center animate__animated animate__fadeInUp">
                            <a href="<?= base_url('daftar/tsanawiyyah') ?>" class="btn btn-primary-custom">
                                <i class="icofont-book-alt"></i> Daftar Tingkat Tsanawiyyah
                            </a>
                            <a href="<?= base_url('daftar/muallimin') ?>" class="btn btn-secondary-custom">
                                <i class="icofont-graduate-alt"></i> Daftar Tingkat Mu'allimin
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Announcements Section -->
    <?php if (!empty($announcements)) : ?>
    <section class="announcements-section py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-4">
                    <h2 class="section-title"><i class="icofont-megaphone me-2"></i>Pengumuman Terbaru</h2>
                    <p class="section-subtitle">Informasi penting untuk calon santri dan orang tua</p>
                </div>
            </div>

            <div class="row">
                <?php foreach ($announcements as $announcement) : ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="announcement-card-landing">
                        <?php if ($announcement['gambar']) : ?>
                            <img src="<?= base_url('uploads/pengumuman/' . $announcement['gambar']) ?>" class="announcement-img" alt="<?= esc($announcement['judul']) ?>">
                        <?php else : ?>
                            <div class="announcement-img-placeholder">
                                <i class="icofont-megaphone"></i>
                            </div>
                        <?php endif; ?>
                        <div class="announcement-card-body">
                            <h4 class="announcement-card-title">
                                <a href="<?= base_url('/pengumuman/' . $announcement['id_pengumuman']) ?>">
                                    <?= esc($announcement['judul']) ?>
                                </a>
                            </h4>
                            <p class="announcement-card-meta">
                                <i class="icofont-calendar me-1"></i>
                                <?= date('d M Y', strtotime($announcement['created_at'])) ?>
                            </p>
                            <p class="announcement-card-text">
                                <?= esc(substr(strip_tags($announcement['konten']), 0, 100)) ?>...
                            </p>
                            <div class="announcement-card-footer">
                                <span class="announcement-stats">
                                    <i class="icofont-heart text-danger"></i> <?= $announcement['likes_count'] ?>
                                    <i class="icofont-comment ms-2"></i> <?= $announcement['replies_count'] ?>
                                </span>
                                <a href="<?= base_url('/pengumuman/' . $announcement['id_pengumuman']) ?>" class="btn-read-more-landing">
                                    Selengkapnya <i class="icofont-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="row">
                <div class="col-lg-12 text-center mt-3">
                    <a href="<?= base_url('/pengumuman') ?>" class="btn btn-outline-success btn-lg">
                        <i class="icofont-listine-dots me-2"></i>Lihat Semua Pengumuman
                    </a>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Features Section -->
    <section class="features-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 style="font-size: 2.5rem; font-weight: 700; color: #333;">Mengapa Memilih Kami?</h2>
                    <p style="color: rgba(255, 255, 255, 0.95); font-size: 1.1rem;">Keunggulan Pesantren Persatuan Islam 31 Banjaran</p>
                </div>
            </div>

            <div class="row">
                <!-- Feature 1 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icofont-book-alt"></i>
                        </div>
                        <h3 class="feature-title">Kurikulum Terpadu</h3>
                        <p class="feature-text">
                            Menggabungkan kurikulum Kementerian Agama dengan kurikulum pesantren untuk pembelajaran yang komprehensif.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icofont-teacher"></i>
                        </div>
                        <h3 class="feature-title">Tenaga Pengajar Berkualitas</h3>
                        <p class="feature-text">
                            Didukung oleh ustadz dan ustadzah yang berpengalaman dan berkompeten di bidangnya.
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icofont-home"></i>
                        </div>
                        <h3 class="feature-title">Fasilitas Lengkap</h3>
                        <p class="feature-text">
                            Ruang kelas nyaman, masjid luas, perpustakaan, laboratorium, dan fasilitas olahraga yang memadai.
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icofont-award"></i>
                        </div>
                        <h3 class="feature-title">Prestasi Gemilang</h3>
                        <p class="feature-text">
                            Santri kami berprestasi dalam bidang akademik, tahfidz, dan kegiatan ekstrakurikuler.
                        </p>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icofont-ui-calendar"></i>
                        </div>
                        <h3 class="feature-title">Pendaftaran Online</h3>
                        <p class="feature-text">
                            Proses pendaftaran mudah dan cepat melalui sistem online yang user-friendly.
                        </p>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="icofont-heart"></i>
                        </div>
                        <h3 class="feature-title">Pembinaan Karakter</h3>
                        <p class="feature-text">
                            Fokus pada pembentukan akhlak mulia dan karakter Islami yang kuat.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <h3 style="font-size: 2rem; font-weight: 700; color: #333; margin-bottom: 30px;">
                        Siap Bergabung Bersama Kami?
                    </h3>
                    <div class="cta-buttons justify-content-center">
                        <a href="<?= base_url('daftar/tsanawiyyah') ?>" class="btn btn-primary-custom">
                            <i class="icofont-book-alt"></i> Daftar Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= esc($year) ?> Pesantren Persatuan Islam 31 Banjaran. All Rights Reserved.</p>
            <p>
                <a href="#">Tentang Kami</a> |
                <a href="#">Kontak</a> |
                <a href="#">Syarat & Ketentuan</a>
            </p>
        </div>
    </footer>

    <!-- Include Components -->
    <?php include APPPATH . 'Views/components/toast.php'; ?>
    <?php include APPPATH . 'Views/components/loading.php'; ?>

    <!-- Hafsa Template JS -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/waypoints.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/swiper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/lightcase.js') ?>"></script>
    <script src="<?= base_url('assets/js/wow.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/functions.js') ?>"></script>

    <!-- Initialize WOW.js for animations -->
    <script>
        new WOW().init();

        // Enhanced UX for landing page
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading state to CTA buttons
            const ctaButtons = document.querySelectorAll('.btn-primary-custom, .btn-secondary-custom');
            ctaButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    showLoading('Memuat formulir pendaftaran...');
                });
            });

            // Accessibility improvements
            document.querySelectorAll('a').forEach(link => {
                if (link.href && !link.getAttribute('aria-label')) {
                    const text = link.textContent.trim();
                    if (text) {
                        link.setAttribute('aria-label', text);
                    }
                }
            });

            console.log('Landing page initialized successfully');
        });
    </script>
</body>

</html>