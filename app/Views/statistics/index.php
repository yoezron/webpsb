<?php
/**
 * Laman statistik pendaftar publik.
 *
 * @var array      $ringkasan
 * @var array      $grafik
 * @var array      $tabel
 * @var array      $status_pendaftaran
 * @var array|null $periode
 */
$r = $ringkasan;
$fmt = static fn($n) => number_format((int) $n, 0, ',', '.');
$persen = static fn($a, $b) => $b > 0 ? round($a / $b * 100) : 0;
$bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$terakhir = !empty($r['terakhir'])
    ? date('j', strtotime($r['terakhir'])) . ' ' . $bulan[(int) date('n', strtotime($r['terakhir'])) - 1] . ' ' . date('Y, H:i', strtotime($r['terakhir']))
    : null;

$kartuJalur = [
    'TSANAWIYYAH' => ["Tsanawiyyah", 'icofont-book-alt', 'primer'],
    'MUALLIMIN'   => ["Mu'allimin", 'icofont-graduate-alt', 'aksen'],
];

$charts = [
    ['id' => 'jalur',       'judul' => 'Jalur & Jenis Kelamin',       'ikon' => 'icofont-chart-histogram',  'ket' => 'Jumlah pendaftar per jalur, dipisah laki-laki dan perempuan'],
    ['id' => 'tanggal',     'judul' => 'Perkembangan Pendaftaran',     'ikon' => 'icofont-chart-line-alt',   'ket' => 'Pendaftar per hari (batang) dan total kumulatif (garis)'],
    ['id' => 'usia',        'judul' => 'Usia Pendaftar',               'ikon' => 'icofont-users-alt-3',      'ket' => 'Dihitung per 1 Juli ' . periode_tahun_mulai()],
    ['id' => 'desa',        'judul' => '10 Desa/Kelurahan Terbanyak',  'ikon' => 'icofont-location-pin',     'ket' => 'Berdasarkan alamat domisili'],
    ['id' => 'jarak',       'judul' => 'Jarak Rumah ke Sekolah',       'ikon' => 'icofont-road',             'ket' => 'Kategori sesuai EMIS'],
    ['id' => 'waktu',       'judul' => 'Waktu Tempuh',                 'ikon' => 'icofont-clock-time',       'ket' => 'Kategori sesuai EMIS'],
    ['id' => 'sekolah',     'judul' => 'Status Asal Sekolah',          'ikon' => 'icofont-institution',      'ket' => 'Negeri atau swasta'],
    ['id' => 'jenjang',     'judul' => 'Jenjang Asal Sekolah',         'ikon' => 'icofont-graduate',         'ket' => 'SD, MI, SMP, MTs, paket, dll.'],
    ['id' => 'penghasilan', 'judul' => 'Penghasilan Orang Tua',        'ikon' => 'icofont-money',            'ket' => 'Per bulan, kategori EMIS (ayah; bila kosong ibu/wali)', 'lebar' => true],
];
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title) ?></title>
    <meta name="description" content="<?= esc($meta_description) ?>">
    <meta name="keywords" content="<?= esc($meta_keywords) ?>">
    <meta name="robots" content="index, follow">
    <meta property="og:title" content="<?= esc($title) ?>">
    <meta property="og:description" content="<?= esc($meta_description) ?>">
    <meta property="og:image" content="<?= logo_aset('01.png') ?>">

    <link rel="icon" type="image/x-icon" href="<?= logo_aset('favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= logo_aset('favicon-32x32.png') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">

    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-yellow: #f5d565;
            --laki: #3B82F6;
            --perempuan: #EC4899;
            --teks: #1f2937;
            --teks-lembut: #6b7280;
            --garis: #e5e7eb;
        }

        body {
            background: #f4f6f9;
            font-family: 'Poppins', 'Segoe UI', Tahoma, sans-serif;
            color: var(--teks);
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand {
            color: #fff;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-custom .navbar-brand img {
            height: 38px;
            width: auto;
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            border-radius: 8px;
            padding: 8px 14px !important;
        }

        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: #fff !important;
            background: rgba(255, 255, 255, 0.15);
        }

        .navbar-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.6);
        }

        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 1%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        /* Hero */
        .hero-stat {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: #fff;
            padding: 48px 0 110px;
        }

        .hero-stat h1 {
            font-size: 2.2rem;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .hero-stat .lead {
            font-size: 1.05rem;
            opacity: 0.95;
            margin-bottom: 18px;
        }

        .hero-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, 0.18);
            border: 1px solid rgba(255, 255, 255, 0.35);
            border-radius: 50px;
            padding: 6px 14px;
            font-size: 0.9rem;
            font-weight: 500;
            margin: 0 6px 6px 0;
        }

        .hero-chip .dot {
            width: 9px;
            height: 9px;
            border-radius: 50%;
            background: #fff;
        }

        .hero-chip.buka .dot {
            background: #4ade80;
            box-shadow: 0 0 0 3px rgba(74, 222, 128, 0.35);
        }

        .hero-chip.tutup .dot {
            background: #f87171;
        }

        /* Kartu ringkasan */
        .ringkasan {
            margin-top: -80px;
        }

        .kpi {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 24px rgba(0, 0, 0, 0.07);
            height: 100%;
        }

        .kpi .kpi-label {
            font-size: 0.85rem;
            color: var(--teks-lembut);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .kpi .kpi-ikon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        }

        .kpi .kpi-ikon.aksen {
            background: linear-gradient(135deg, var(--secondary-yellow), #e0b518);
            color: #3b3b3b;
        }

        .kpi .kpi-ikon.netral {
            background: linear-gradient(135deg, #64748b, #475569);
        }

        .kpi .kpi-angka {
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.1;
            margin: 10px 0 4px;
        }

        .kpi .kpi-ket {
            font-size: 0.82rem;
            color: var(--teks-lembut);
        }

        .kpi .progress {
            height: 7px;
            border-radius: 10px;
            background: #eef2f7;
            margin-top: 10px;
        }

        .kpi .progress-bar {
            background: var(--primary-green);
        }

        .kpi .progress-bar.aksen {
            background: var(--secondary-yellow);
        }

        .jk-bar {
            display: flex;
            height: 9px;
            border-radius: 10px;
            overflow: hidden;
            background: #eef2f7;
            margin-top: 12px;
        }

        .jk-bar span:first-child {
            background: var(--laki);
        }

        .jk-bar span:last-child {
            background: var(--perempuan);
        }

        .jk-legend {
            display: flex;
            flex-wrap: wrap;
            column-gap: 10px;
            justify-content: space-between;
            white-space: nowrap;
            font-size: 0.82rem;
            margin-top: 6px;
        }

        .jk-legend i {
            font-style: normal;
            display: inline-block;
            width: 9px;
            height: 9px;
            border-radius: 50%;
            margin-right: 4px;
        }

        /* Grafik */
        .section-title {
            font-weight: 700;
            font-size: 1.35rem;
            margin: 36px 0 16px;
        }

        .chart-card {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .chart-card h3 {
            font-size: 1.05rem;
            font-weight: 600;
            margin-bottom: 2px;
            color: var(--dark-green);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chart-card h3 i {
            color: var(--primary-green);
        }

        .chart-card .chart-ket {
            font-size: 0.8rem;
            color: var(--teks-lembut);
            margin-bottom: 14px;
        }

        .chart-container {
            position: relative;
            height: 290px;
            flex: 1;
        }

        #wrap-penghasilan {
            height: 380px;
        }

        .chart-kosong {
            position: absolute;
            inset: 0;
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: #9ca3af;
            font-size: 0.9rem;
            text-align: center;
        }

        .chart-kosong i {
            font-size: 2.6rem;
            margin-bottom: 6px;
            opacity: 0.6;
        }

        .chart-container.kosong canvas {
            display: none;
        }

        .chart-container.kosong .chart-kosong {
            display: flex;
        }

        /* Tabel */
        .table-card {
            background: #fff;
            border-radius: 16px;
            padding: 22px;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.06);
        }

        .filter-buttons .btn {
            border-radius: 25px;
            padding: 7px 16px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 2px solid var(--primary-green);
            color: var(--primary-green);
            background: #fff;
        }

        .filter-buttons .btn.active,
        .filter-buttons .btn:hover {
            background: var(--primary-green);
            color: #fff;
        }

        .filter-buttons .btn .jml {
            display: inline-block;
            min-width: 22px;
            padding: 0 6px;
            margin-left: 4px;
            border-radius: 10px;
            background: rgba(0, 0, 0, 0.08);
            font-size: 0.78rem;
        }

        .cari {
            max-width: 300px;
        }

        .cari .form-control,
        .cari .input-group-text {
            border-radius: 25px;
        }

        .cari .input-group-text {
            background: #fff;
            border-right: 0;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .cari .form-control {
            border-left: 0;
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .table thead th {
            background: var(--primary-green);
            color: #fff;
            border: none;
            font-weight: 600;
            font-size: 0.88rem;
            padding: 12px 14px;
            white-space: nowrap;
        }

        .table thead th:first-child {
            border-top-left-radius: 10px;
        }

        .table thead th:last-child {
            border-top-right-radius: 10px;
        }

        .table tbody td {
            padding: 11px 14px;
            vertical-align: middle;
            font-size: 0.92rem;
        }

        .badge-soft {
            display: inline-block;
            padding: 4px 11px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.78rem;
        }

        .badge-l {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-p {
            background: #fce7f3;
            color: #be185d;
        }

        .badge-tsn {
            background: rgba(26, 179, 74, 0.14);
            color: var(--dark-green);
        }

        .badge-mua {
            background: rgba(243, 198, 35, 0.25);
            color: #7a5d00;
        }

        .pagination .page-link {
            color: var(--dark-green);
            border-radius: 8px !important;
            margin: 0 2px;
            border: 1px solid var(--garis);
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-green);
            border-color: var(--primary-green);
            color: #fff;
        }

        .footer {
            background: #1f2937;
            color: #d1d5db;
            padding: 28px 0;
            margin-top: 56px;
            text-align: center;
            font-size: 0.9rem;
        }

        .footer a {
            color: var(--secondary-yellow);
            text-decoration: none;
        }

        @media (max-width: 768px) {
            .hero-stat {
                padding: 32px 0 96px;
            }

            .hero-stat h1 {
                font-size: 1.6rem;
            }

            .kpi .kpi-angka {
                font-size: 1.6rem;
            }

            .chart-container {
                height: 250px;
            }

            .cari {
                max-width: none;
                width: 100%;
            }

            /* Tabel jadi kartu di layar kecil */
            #dataTable thead {
                display: none;
            }

            #dataTable tbody tr {
                display: block;
                border: 1px solid var(--garis);
                border-radius: 12px;
                margin-bottom: 10px;
                padding: 8px 4px;
            }

            #dataTable tbody td {
                display: flex;
                justify-content: space-between;
                gap: 12px;
                border: 0;
                padding: 5px 10px;
                text-align: right;
            }

            #dataTable tbody td::before {
                content: attr(data-label);
                font-weight: 600;
                color: var(--teks-lembut);
                text-align: left;
            }

            #dataTable tbody td.td-kosong::before {
                content: none;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <img src="<?= logo_aset('01.png') ?>" alt="Logo">
                <span><?= esc(pengaturan('nama_singkat')) ?></span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Buka menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>"><i class="icofont-home"></i> Beranda</a></li>
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="<?= base_url('statistik') ?>"><i class="icofont-chart-bar-graph"></i> Statistik</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('pengumuman') ?>"><i class="icofont-megaphone"></i> Pengumuman</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero-stat">
        <div class="container">
            <h1><i class="icofont-chart-bar-graph"></i> Statistik Pendaftar</h1>
            <p class="lead">Data pendaftar santri baru <?= esc(nama_lembaga()) ?></p>
            <span class="hero-chip"><i class="icofont-calendar"></i> Tahun Ajaran <?= esc($tahun_ajaran) ?></span>
            <span class="hero-chip <?= $status_pendaftaran['dibuka'] ? 'buka' : 'tutup' ?>">
                <span class="dot"></span> <?= esc($status_pendaftaran['dibuka'] ? pengaturan('badge_dibuka') : pengaturan('badge_ditutup')) ?>
            </span>
            <?php if ($terakhir): ?>
                <span class="hero-chip"><i class="icofont-clock-time"></i> Pendaftar terakhir: <?= esc($terakhir) ?></span>
            <?php endif; ?>
        </div>
    </header>

    <main class="container">
        <!-- Ringkasan -->
        <section class="ringkasan row g-3">
            <div class="col-6 col-lg-3">
                <div class="kpi">
                    <div class="kpi-label"><span class="kpi-ikon netral"><i class="icofont-users-alt-4"></i></span> Total Pendaftar</div>
                    <div class="kpi-angka"><?= $fmt($r['total']) ?></div>
                    <div class="kpi-ket">
                        <strong><?= $fmt($r['hari_ini']) ?></strong> hari ini &middot; <strong><?= $fmt($r['tujuh_hari']) ?></strong> dalam 7 hari
                    </div>
                </div>
            </div>
            <?php foreach ($kartuJalur as $kode => [$label, $ikon, $gaya]): ?>
                <?php $kuota = $r['kuota'][$kode]; $n = $r[$kode]; ?>
                <div class="col-6 col-lg-3">
                    <div class="kpi">
                        <div class="kpi-label"><span class="kpi-ikon <?= $gaya === 'aksen' ? 'aksen' : '' ?>"><i class="<?= $ikon ?>"></i></span> <?= esc($label) ?></div>
                        <div class="kpi-angka"><?= $fmt($n) ?></div>
                        <?php if ($kuota): ?>
                            <div class="kpi-ket">dari kuota <?= $fmt($kuota) ?> (<?= $persen($n, $kuota) ?>%)<?= $n >= $kuota ? ' &middot; <strong>kuota terpenuhi</strong>' : '' ?></div>
                            <div class="progress" role="progressbar" aria-label="Keterisian kuota <?= esc($label) ?>" aria-valuenow="<?= min(100, $persen($n, $kuota)) ?>" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar <?= $gaya === 'aksen' ? 'aksen' : '' ?>" style="width: <?= min(100, $persen($n, $kuota)) ?>%"></div>
                            </div>
                        <?php else: ?>
                            <div class="kpi-ket"><?= $persen($n, $r['total']) ?>% dari total pendaftar</div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
            <div class="col-6 col-lg-3">
                <div class="kpi">
                    <div class="kpi-label"><span class="kpi-ikon netral"><i class="icofont-users-social"></i></span> Jenis Kelamin</div>
                    <div class="kpi-angka"><?= $fmt($r['L']) ?> <small class="fs-6 text-muted fw-semibold">/</small> <?= $fmt($r['P']) ?></div>
                    <div class="jk-bar" aria-hidden="true">
                        <span style="width: <?= $r['total'] ? $persen($r['L'], $r['total']) : 0 ?>%"></span>
                        <span style="width: <?= $r['total'] ? 100 - $persen($r['L'], $r['total']) : 0 ?>%"></span>
                    </div>
                    <div class="jk-legend">
                        <span><i style="background: var(--laki)"></i>Laki-laki <?= $persen($r['L'], $r['total']) ?>%</span>
                        <span><i style="background: var(--perempuan)"></i>Perempuan <?= $persen($r['P'], $r['total']) ?>%</span>
                    </div>
                </div>
            </div>
        </section>

        <?php if ((int) $r['total'] === 0): ?>
            <div class="alert alert-light border text-center mt-4 mb-0">
                <i class="icofont-info-circle"></i> Belum ada pendaftar pada Tahun Ajaran <?= esc($tahun_ajaran) ?>. Statistik akan tampil setelah ada pendaftar.
            </div>
        <?php endif; ?>

        <!-- Grafik -->
        <h2 class="section-title">Sebaran Data Pendaftar</h2>
        <section class="row g-4">
            <?php foreach ($charts as $c): ?>
                <div class="<?= !empty($c['lebar']) ? 'col-12' : 'col-lg-6' ?>">
                    <div class="chart-card">
                        <h3><i class="<?= $c['ikon'] ?>"></i> <?= esc($c['judul']) ?></h3>
                        <div class="chart-ket"><?= esc($c['ket']) ?></div>
                        <div class="chart-container" id="wrap-<?= $c['id'] ?>">
                            <canvas id="chart-<?= $c['id'] ?>" role="img" aria-label="Grafik <?= esc($c['judul']) ?>"></canvas>
                            <div class="chart-kosong"><i class="icofont-chart-pie-alt"></i>Belum ada data</div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </section>

        <!-- Tabel -->
        <h2 class="section-title">Daftar Pendaftar</h2>
        <section class="table-card">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-3">
                <div class="filter-buttons d-flex flex-wrap gap-2" role="group" aria-label="Filter jalur">
                    <button type="button" class="btn active" data-jalur="all">Semua <span class="jml"><?= $fmt($r['total']) ?></span></button>
                    <button type="button" class="btn" data-jalur="TSANAWIYYAH">Tsanawiyyah <span class="jml"><?= $fmt($r['TSANAWIYYAH']) ?></span></button>
                    <button type="button" class="btn" data-jalur="MUALLIMIN">Mu'allimin <span class="jml"><?= $fmt($r['MUALLIMIN']) ?></span></button>
                </div>
                <div class="cari input-group">
                    <span class="input-group-text"><i class="icofont-search-1"></i></span>
                    <input type="search" class="form-control" id="cari" placeholder="Cari nama, nomor, sekolah..." aria-label="Cari pendaftar">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="dataTable">
                    <thead>
                        <tr>
                            <th style="width: 56px">No</th>
                            <th>No. Pendaftaran</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Jalur</th>
                            <th>Asal Sekolah</th>
                            <th>Tgl. Daftar</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody"></tbody>
                </table>
            </div>

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
                <div class="small text-muted" id="infoTabel"></div>
                <nav aria-label="Halaman tabel"><ul class="pagination pagination-sm mb-0" id="paginasi"></ul></nav>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="container">
            <p class="mb-1">&copy; <?= date('Y') ?> <?= esc(nama_lembaga()) ?>. <?= esc(pengaturan('footer_teks')) ?></p>
            <p class="mb-0"><a href="<?= base_url('/') ?>">Beranda</a> &middot; <a href="<?= base_url('pengumuman') ?>">Pengumuman</a></p>
        </div>
    </footer>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        (function() {
            const GRAFIK = <?= json_encode($grafik, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
            const TABEL = <?= json_encode($tabel, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;

            // ---------- Warna mengikuti tema (CSS variable dari menu Pengaturan) ----------
            const css = getComputedStyle(document.documentElement);
            const v = (n, d) => (css.getPropertyValue(n) || d).trim();
            const W = {
                primer: v('--primary-green', '#1AB34A'),
                gelap: v('--dark-green', '#158a3a'),
                aksen: v('--secondary-yellow', '#F3C623'),
                laki: v('--laki', '#3B82F6'),
                perempuan: v('--perempuan', '#EC4899'),
            };
            const palet = [W.primer, W.aksen, '#3B82F6', '#F97316', '#8B5CF6', '#14B8A6', '#EF4444', '#64748B', '#EC4899', '#84CC16', '#0EA5E9'];
            const alpha = (hex, a) => {
                const h = hex.replace('#', '');
                if (h.length !== 6) return hex;
                return `rgba(${parseInt(h.substr(0, 2), 16)}, ${parseInt(h.substr(2, 2), 16)}, ${parseInt(h.substr(4, 2), 16)}, ${a})`;
            };
            const angka = (n) => new Intl.NumberFormat('id-ID').format(n);

            if (typeof Chart === 'undefined') {
                document.querySelectorAll('.chart-container').forEach((el) => {
                    el.classList.add('kosong');
                    el.querySelector('.chart-kosong').innerHTML = '<i class="icofont-warning-alt"></i>Grafik gagal dimuat. Periksa koneksi internet.';
                });
            } else {
                Chart.defaults.font.family = "'Poppins', 'Segoe UI', sans-serif";
                Chart.defaults.color = '#4b5563';
                Chart.defaults.plugins.tooltip.padding = 10;
                Chart.defaults.plugins.tooltip.cornerRadius = 8;
                Chart.defaults.plugins.legend.labels.usePointStyle = true;
            }

            function adaData(d) {
                return d && Array.isArray(d.values) && d.values.some((x) => x > 0);
            }

            function buat(id, cfg, data) {
                const wrap = document.getElementById('wrap-' + id);
                if (!adaData(data)) {
                    wrap.classList.add('kosong');
                    return;
                }
                new Chart(document.getElementById('chart-' + id), cfg);
            }

            const sumbuAngka = {
                beginAtZero: true,
                ticks: { precision: 0, callback: (x) => angka(x) },
                grid: { color: '#f1f5f9' },
            };
            const tanpaGrid = { grid: { display: false } };

            const tooltipPersen = {
                callbacks: {
                    label(ctx) {
                        const total = ctx.dataset.data.reduce((a, b) => a + b, 0);
                        const p = total ? Math.round(ctx.parsed / total * 100) : 0;
                        return ` ${ctx.label}: ${angka(ctx.parsed)} (${p}%)`;
                    },
                },
            };

            function batang(id, data, warna, opsi = {}) {
                const horizontal = !!opsi.horizontal;
                buat(id, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: opsi.label || 'Pendaftar',
                            data: data.values,
                            backgroundColor: warna,
                            borderRadius: 6,
                            maxBarThickness: 46,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        indexAxis: horizontal ? 'y' : 'x',
                        plugins: { legend: { display: false } },
                        scales: horizontal ? { x: sumbuAngka, y: { ...tanpaGrid, ticks: { autoSkip: false } } } : { y: sumbuAngka, x: tanpaGrid },
                    },
                }, data);
            }

            function donat(id, data, tipe = 'doughnut') {
                buat(id, {
                    type: tipe,
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.values,
                            backgroundColor: palet,
                            borderColor: '#fff',
                            borderWidth: 2,
                        }],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: tipe === 'doughnut' ? '58%' : 0,
                        plugins: {
                            legend: { position: 'right', labels: { boxWidth: 10, padding: 12 } },
                            tooltip: tooltipPersen,
                        },
                    },
                }, data);
            }

            function buatSemua() {
                // Jalur x jenis kelamin (bertumpuk)
                buat('jalur', {
                    type: 'bar',
                    data: {
                        labels: GRAFIK.jalur.labels,
                        datasets: [{
                                label: 'Laki-laki',
                                data: GRAFIK.jalur.laki,
                                backgroundColor: W.laki,
                                borderRadius: 6,
                                maxBarThickness: 70
                            },
                            {
                                label: 'Perempuan',
                                data: GRAFIK.jalur.perempuan,
                                backgroundColor: W.perempuan,
                                borderRadius: 6,
                                maxBarThickness: 70
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'bottom' },
                            tooltip: {
                                callbacks: {
                                    footer: (items) => 'Total: ' + angka(items.reduce((a, i) => a + i.parsed.y, 0)),
                                },
                            },
                        },
                        scales: {
                            x: { stacked: true, grid: { display: false } },
                            y: { ...sumbuAngka, stacked: true },
                        },
                    },
                }, GRAFIK.jalur);

                // Perkembangan harian + kumulatif
                buat('tanggal', {
                    type: 'bar',
                    data: {
                        labels: GRAFIK.tanggal.labels,
                        datasets: [{
                                type: 'bar',
                                label: 'Per hari',
                                data: GRAFIK.tanggal.values,
                                backgroundColor: alpha(W.primer, 0.75),
                                borderRadius: 4,
                                yAxisID: 'y',
                                order: 2
                            },
                            {
                                type: 'line',
                                label: 'Kumulatif',
                                data: GRAFIK.tanggal.kumulatif,
                                borderColor: W.aksen,
                                backgroundColor: alpha(W.aksen, 0.15),
                                tension: 0.3,
                                pointRadius: GRAFIK.tanggal.labels.length > 40 ? 0 : 3,
                                fill: true,
                                yAxisID: 'y1',
                                order: 1
                            },
                        ],
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { position: 'bottom' } },
                        scales: {
                            x: { grid: { display: false }, ticks: { maxRotation: 0, autoSkip: true, maxTicksLimit: 8 } },
                            y: { ...sumbuAngka, title: { display: true, text: 'Per hari' } },
                            y1: { ...sumbuAngka, position: 'right', grid: { display: false }, title: { display: true, text: 'Kumulatif' } },
                        },
                    },
                }, GRAFIK.tanggal);

                batang('usia', GRAFIK.usia, palet.slice(0, GRAFIK.usia.labels.length));
                batang('desa', GRAFIK.desa, '#3B82F6', { horizontal: true });
                donat('jarak', GRAFIK.jarak);
                batang('waktu', GRAFIK.waktu, '#F97316');
                donat('sekolah', GRAFIK.sekolah, 'pie');
                donat('jenjang', GRAFIK.jenjang);
                batang('penghasilan', GRAFIK.penghasilan, W.primer, { horizontal: true, label: 'Keluarga' });
            }

            // ---------- Tabel: filter, cari, paginasi ----------
            const PER_HALAMAN = 20;
            let jalur = 'all',
                kataKunci = '',
                halaman = 1;

            const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));

            function terfilter() {
                const q = kataKunci.toLowerCase();
                return TABEL.filter((r) => (jalur === 'all' || r.jalur === jalur) &&
                    (!q || [r.nomor_pendaftaran, r.nama_lengkap, r.asal_sekolah].some((x) => String(x).toLowerCase().includes(q))));
            }

            function render() {
                const data = terfilter();
                const totalHal = Math.max(1, Math.ceil(data.length / PER_HALAMAN));
                halaman = Math.min(halaman, totalHal);
                const mulai = (halaman - 1) * PER_HALAMAN;
                const isi = data.slice(mulai, mulai + PER_HALAMAN);
                const body = document.getElementById('tableBody');

                if (!isi.length) {
                    body.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4 td-kosong">${TABEL.length ? 'Tidak ada pendaftar yang cocok dengan pencarian.' : 'Belum ada pendaftar.'}</td></tr>`;
                } else {
                    body.innerHTML = isi.map((r, i) => `
                        <tr>
                            <td data-label="No">${mulai + i + 1}</td>
                            <td data-label="No. Pendaftaran"><span class="font-monospace">${esc(r.nomor_pendaftaran)}</span></td>
                            <td data-label="Nama" class="fw-semibold">${esc(r.nama_lengkap)}</td>
                            <td data-label="Jenis Kelamin"><span class="badge-soft ${r.jk === 'P' ? 'badge-p' : 'badge-l'}">${esc(r.jenis_kelamin)}</span></td>
                            <td data-label="Jalur"><span class="badge-soft ${r.jalur === 'MUALLIMIN' ? 'badge-mua' : 'badge-tsn'}">${esc(r.jalur_pendaftaran)}</span></td>
                            <td data-label="Asal Sekolah">${esc(r.asal_sekolah)}</td>
                            <td data-label="Tgl. Daftar">${esc(r.tanggal)}</td>
                        </tr>`).join('');
                }

                document.getElementById('infoTabel').textContent = data.length ?
                    `Menampilkan ${angka(mulai + 1)}–${angka(mulai + isi.length)} dari ${angka(data.length)} pendaftar` : '';

                // Paginasi ringkas: 1 … 4 5 6 … 10
                const pg = document.getElementById('paginasi');
                if (totalHal <= 1) {
                    pg.innerHTML = '';
                    return;
                }
                const item = (n, label, aktif = false, mati = false) =>
                    `<li class="page-item ${aktif ? 'active' : ''} ${mati ? 'disabled' : ''}"><a class="page-link" href="#" data-hal="${n}">${label}</a></li>`;
                let html = item(halaman - 1, '&laquo;', false, halaman === 1);
                const hal = new Set([1, totalHal, halaman - 1, halaman, halaman + 1].filter((n) => n >= 1 && n <= totalHal));
                let sebelum = 0;
                [...hal].sort((a, b) => a - b).forEach((n) => {
                    if (n - sebelum > 1) html += item(0, '…', false, true);
                    html += item(n, n, n === halaman);
                    sebelum = n;
                });
                html += item(halaman + 1, '&raquo;', false, halaman === totalHal);
                pg.innerHTML = html;
            }

            document.getElementById('paginasi').addEventListener('click', (e) => {
                const a = e.target.closest('a[data-hal]');
                if (!a) return;
                e.preventDefault();
                const n = parseInt(a.dataset.hal, 10);
                if (n >= 1) {
                    halaman = n;
                    render();
                    document.getElementById('dataTable').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });

            document.querySelectorAll('.filter-buttons .btn').forEach((btn) => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.filter-buttons .btn').forEach((b) => b.classList.remove('active'));
                    btn.classList.add('active');
                    jalur = btn.dataset.jalur;
                    halaman = 1;
                    render();
                });
            });

            let tunda;
            document.getElementById('cari').addEventListener('input', (e) => {
                clearTimeout(tunda);
                tunda = setTimeout(() => {
                    kataKunci = e.target.value.trim();
                    halaman = 1;
                    render();
                }, 200);
            });

            render();
            if (typeof Chart !== 'undefined') {
                buatSemua();
            }
        })();
    </script>
</body>

</html>
