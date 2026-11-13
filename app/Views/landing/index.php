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
    <meta name="author" content="<?= esc($set['nama_lembaga']) ?>">
    <meta name="robots" content="index, follow">

    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?= esc($title) ?>">
    <meta property="og:description" content="<?= esc($meta_description) ?>">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="id_ID">
    <meta property="og:image" content="<?= logo_aset('01.png') ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= logo_aset('favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= logo_aset('favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= logo_aset('favicon-32x32.png') ?>">
    <link rel="apple-touch-icon" href="<?= logo_aset('01.png') ?>">

    <!-- Hafsa Template CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/lightcase.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/swiper.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Custom Branding CSS -->
    <?php
    $heroHex = static fn($v, $d) => preg_match('/^#[0-9a-fA-F]{6}$/', (string) $v) ? $v : $d;
    $heroJudul = $heroHex($set['hero_warna_judul'] ?? '', '#FFFFFF');
    $heroTeks  = $heroHex($set['hero_warna_teks'] ?? '', '#FFFFFF');
    $heroSub   = $heroHex($set['hero_warna_subjudul'] ?? '', 'var(--light-yellow)');
    $heroAlpha = number_format(max(0, min(100, (int) ($set['hero_overlay'] ?? 95))) / 100, 2, '.', '');
    $heroShadow = ($set['hero_bayangan_teks'] ?? '1') === '1';
    // URL dipakai di dalam CSS url('...'): buang karakter yang bisa keluar dari konteks tsb.
    $latarUrl  = str_replace(["'", '"', '(', ')', '\\', '<', '>'], '', latar_url());
    ?>
    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-yellow: #f5d565;
            --hero-judul: <?= $heroJudul ?>;
            --hero-sub: <?= $heroSub ?>;
            --hero-teks: <?= $heroTeks ?>;
            --hero-shadow-lg: <?= $heroShadow ? '2px 2px 4px rgba(0, 0, 0, 0.3)' : 'none' ?>;
            --hero-shadow-sm: <?= $heroShadow ? '1px 1px 2px rgba(0, 0, 0, 0.35)' : 'none' ?>;
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
            background: linear-gradient(135deg, rgba(26, 179, 74, <?= $heroAlpha ?>) 0%, rgba(21, 138, 58, <?= $heroAlpha ?>) 100%),
                url('<?= $latarUrl ?>') center/cover;
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
            color: var(--hero-teks);
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
            color: var(--hero-judul);
            text-shadow: var(--hero-shadow-lg);
        }

        .hero-subtitle {
            font-size: 1.5rem;
            font-weight: 400;
            margin-bottom: 30px;
            color: var(--hero-sub);
            text-shadow: var(--hero-shadow-sm);
        }

        .hero-text {
            font-size: 1.1rem;
            margin-bottom: 40px;
            line-height: 1.8;
            max-width: 700px;
            color: var(--hero-teks);
            text-shadow: var(--hero-shadow-sm);
        }

        .hero-note {
            font-size: 0.9rem;
            opacity: 0.85;
            color: var(--hero-teks);
            text-shadow: var(--hero-shadow-sm);
        }

        .hero-content .btn-hero-outline {
            border: 2px solid var(--hero-teks);
            color: var(--hero-teks);
            background: transparent;
            border-radius: 50px;
            padding: 12px 30px;
            font-weight: 600;
            text-shadow: var(--hero-shadow-sm);
        }

        .hero-content .btn-hero-outline:hover {
            background: var(--hero-teks);
            color: var(--dark-green);
            text-shadow: none;
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
            color: var(--hero-teks);
        }

        /* Features Section */
        .features-section {
            padding: 80px 0;
            background: linear-gradient(135deg, rgba(243, 198, 35, 0.4) 0%, rgba(21, 138, 58, 0.95) 100%), url('<?= $latarUrl ?>') center/cover;
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

        /* Pendaftaran ditutup */
        .pendaftaran-tutup {
            display: flex;
            align-items: center;
            gap: 15px;
            max-width: 560px;
            background: rgba(255, 255, 255, 0.15);
            border: 2px solid color-mix(in srgb, var(--hero-teks) 55%, transparent);
            border-radius: 15px;
            padding: 18px 24px;
            text-align: left;
            color: var(--hero-teks);
            text-shadow: var(--hero-shadow-sm);
        }

        .pendaftaran-tutup i {
            font-size: 2.2rem;
        }

        /* Contact Section */
        .contact-section {
            background: #fff;
        }

        .contact-card {
            background: #f8f9fa;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .contact-item {
            display: flex;
            gap: 15px;
            margin-bottom: 18px;
            color: #444;
        }

        .contact-item i {
            font-size: 1.5rem;
            color: var(--primary-green);
            width: 30px;
            flex-shrink: 0;
        }

        .contact-item a {
            color: var(--dark-green);
            text-decoration: none;
            word-break: break-word;
        }

        .contact-social {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .contact-social a {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: #fff;
            font-size: 1.2rem;
            text-decoration: none;
        }

        .contact-map {
            min-height: 320px;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .contact-map iframe {
            width: 100%;
            height: 100%;
            min-height: 320px;
            border: 0;
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
                            <img src="<?= logo_aset('01.png') ?>" alt="Logo <?= esc($set['nama_lembaga']) ?>">
                        </div>

                        <!-- Info Badge -->
                        <div class="info-badge">
                            <h3>Tahun Ajaran <?= esc($tahun_ajaran) ?></h3>
                            <p><?= esc($status_pendaftaran['dibuka'] ? $set['badge_dibuka'] : $set['badge_ditutup']) ?></p>
                        </div>

                        <!-- Hero Title -->
                        <h1 class="hero-title animate__animated animate__fadeInDown">
                            <?= esc($set['hero_judul']) ?>
                        </h1>

                        <!-- Hero Subtitle -->
                        <p class="hero-subtitle animate__animated animate__fadeInUp">
                            <?= esc($set['hero_subjudul']) ?>
                        </p>

                        <!-- Hero Text -->
                        <p class="hero-text mx-auto animate__animated animate__fadeIn">
                            <?= nl2br(esc($set['hero_teks'])) ?>
                        </p>

                        <!-- CTA Buttons -->
                        <?php if ($status_pendaftaran['dibuka']) : ?>
                        <div class="cta-buttons justify-content-center animate__animated animate__fadeInUp">
                            <a href="<?= base_url('daftar/tsanawiyyah') ?>" class="btn btn-primary-custom">
                                <i class="icofont-book-alt"></i> <?= esc($set['label_tombol_tsn']) ?>
                            </a>
                            <a href="<?= base_url('daftar/muallimin') ?>" class="btn btn-secondary-custom">
                                <i class="icofont-graduate-alt"></i> <?= esc($set['label_tombol_mua']) ?>
                            </a>
                        </div>
                        <?php else : ?>
                        <div class="pendaftaran-tutup mx-auto animate__animated animate__fadeInUp">
                            <i class="icofont-lock"></i>
                            <div>
                                <strong><?= esc($set['badge_ditutup']) ?></strong><br>
                                <span><?= esc($status_pendaftaran['alasan']) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Update Data & Statistics Links -->
                        <?php if (!empty($set['tampil_tombol_update']) || !empty($set['tampil_tombol_statistik'])) : ?>
                        <div class="mt-4 animate__animated animate__fadeInUp">
                            <div class="d-flex gap-3 justify-content-center flex-wrap">
                                <?php if (!empty($set['tampil_tombol_update'])) : ?>
                                <a href="<?= base_url('update-data') ?>" class="btn btn-hero-outline btn-lg">
                                    <i class="icofont-edit"></i> Perbarui Data Pendaftaran
                                </a>
                                <?php endif; ?>
                                <?php if (!empty($set['tampil_tombol_statistik'])) : ?>
                                <a href="<?= base_url('statistik') ?>" class="btn btn-hero-outline btn-lg">
                                    <i class="icofont-chart-bar-graph"></i> Lihat Statistik Pendaftar
                                </a>
                                <?php endif; ?>
                            </div>
                            <p class="mt-3 hero-note">
                                Sudah mendaftar? Perbarui data Anda atau lihat statistik pendaftar
                            </p>
                        </div>
                        <?php endif; ?>
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
    <?php if (!empty($set['tampil_keunggulan'])) : ?>
    <section class="features-section" id="keunggulan">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center mb-5">
                    <h2 style="font-size: 2.5rem; font-weight: 700; color: #333;"><?= esc($set['keunggulan_judul']) ?></h2>
                    <p style="color: rgba(255, 255, 255, 0.95); font-size: 1.1rem;"><?= esc($set['keunggulan_subjudul']) ?></p>
                </div>
            </div>

            <div class="row justify-content-center">
                <?php foreach ($keunggulan as $item) : ?>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="<?= esc($item['ikon'] ?? 'icofont-star') ?>"></i>
                        </div>
                        <h3 class="feature-title"><?= esc($item['judul'] ?? '') ?></h3>
                        <p class="feature-text">
                            <?= esc($item['teks'] ?? '') ?>
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <?php if ($status_pendaftaran['dibuka']) : ?>
            <!-- Call to Action -->
            <div class="row mt-5">
                <div class="col-lg-12 text-center">
                    <h3 style="font-size: 2rem; font-weight: 700; color: #333; margin-bottom: 30px;">
                        <?= esc($set['cta_judul']) ?>
                    </h3>
                    <div class="cta-buttons justify-content-center">
                        <a href="<?= base_url('daftar/tsanawiyyah') ?>" class="btn btn-primary-custom">
                            <i class="icofont-book-alt"></i> Daftar Tsanawiyyah
                        </a>
                        <a href="<?= base_url('daftar/muallimin') ?>" class="btn btn-secondary-custom">
                            <i class="icofont-graduate-alt"></i> Daftar Mu'allimin
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Contact Section -->
    <?php
    $sosmed = array_filter([
        'icofont-instagram'    => url_aman($set['sosmed_instagram']),
        'icofont-facebook'     => url_aman($set['sosmed_facebook']),
        'icofont-youtube-play' => url_aman($set['sosmed_youtube']),
        'icofont-music-note'   => url_aman($set['sosmed_tiktok']),
    ]);
    $website   = url_aman($set['kontak_website']);
    $adaKontak = $set['kontak_alamat'] || $set['kontak_telepon'] || $set['kontak_whatsapp'] || $set['kontak_email'] || $website || $set['kontak_maps'] || $sosmed;
    ?>
    <?php if (!empty($set['tampil_kontak']) && $adaKontak) : ?>
    <section class="contact-section py-5" id="kontak">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="section-title"><i class="icofont-phone me-2"></i><?= esc($set['kontak_judul']) ?></h2>
            </div>
            <div class="row g-4 align-items-stretch">
                <div class="<?= $set['kontak_maps'] ? 'col-lg-5' : 'col-lg-8 mx-auto' ?>">
                    <div class="contact-card h-100">
                        <?php if ($set['kontak_alamat']) : ?>
                        <div class="contact-item">
                            <i class="icofont-location-pin"></i>
                            <div><strong>Alamat</strong><br><?= nl2br(esc($set['kontak_alamat'])) ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($set['kontak_telepon']) : ?>
                        <div class="contact-item">
                            <i class="icofont-phone"></i>
                            <div><strong>Telepon</strong><br><a href="tel:<?= esc(preg_replace('/[^0-9+]/', '', $set['kontak_telepon'])) ?>"><?= esc($set['kontak_telepon']) ?></a></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($set['kontak_whatsapp']) : ?>
                        <div class="contact-item">
                            <i class="icofont-brand-whatsapp"></i>
                            <div><strong>WhatsApp</strong><br><a href="<?= esc(link_whatsapp($set['kontak_whatsapp'])) ?>" target="_blank" rel="noopener"><?= esc($set['kontak_whatsapp']) ?></a></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($set['kontak_email']) : ?>
                        <div class="contact-item">
                            <i class="icofont-email"></i>
                            <div><strong>Email</strong><br><a href="mailto:<?= esc($set['kontak_email']) ?>"><?= esc($set['kontak_email']) ?></a></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($website) : ?>
                        <div class="contact-item">
                            <i class="icofont-web"></i>
                            <div><strong>Website</strong><br><a href="<?= esc($website) ?>" target="_blank" rel="noopener"><?= esc($set['kontak_website']) ?></a></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($set['kontak_jam']) : ?>
                        <div class="contact-item">
                            <i class="icofont-clock-time"></i>
                            <div><strong>Jam Layanan</strong><br><?= esc($set['kontak_jam']) ?></div>
                        </div>
                        <?php endif; ?>
                        <?php if ($sosmed) : ?>
                        <div class="contact-social">
                            <?php foreach ($sosmed as $icon => $url) : ?>
                                <a href="<?= esc($url) ?>" target="_blank" rel="noopener" aria-label="Media sosial"><i class="<?= $icon ?>"></i></a>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if ($set['kontak_maps']) : ?>
                <div class="col-lg-7">
                    <div class="contact-map h-100">
                        <iframe src="<?= esc($set['kontak_maps']) ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen title="Peta lokasi"></iframe>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> <?= esc($set['nama_lembaga']) ?>. <?= esc($set['footer_teks']) ?></p>
            <p>
                <?php if (!empty($set['tampil_keunggulan'])) : ?><a href="#keunggulan">Tentang Kami</a> |<?php endif; ?>
                <?php if (!empty($set['tampil_kontak']) && $adaKontak) : ?><a href="#kontak">Kontak</a> |<?php endif; ?>
                <a href="<?= base_url('statistik') ?>">Statistik Pendaftar</a> |
                <a href="<?= base_url('pengumuman') ?>">Pengumuman</a> |
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