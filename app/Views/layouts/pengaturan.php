<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Pengaturan') ?></title>
    <link rel="icon" type="image/x-icon" href="<?= logo_aset('favicon.ico') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">
    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-yellow: #f5d565;
        }

        body {
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 600;
        }

        .navbar-custom .nav-link:hover {
            color: var(--secondary-yellow) !important;
        }

        .settings-nav .list-group-item {
            border: none;
            border-radius: 10px !important;
            margin-bottom: 4px;
            font-weight: 600;
            color: #444;
        }

        .settings-nav .list-group-item i {
            width: 22px;
            display: inline-block;
        }

        .settings-nav .list-group-item.active {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: #fff;
        }

        .settings-nav .list-group-item:not(.active):hover {
            background: #eef7f1;
        }

        .card-setting {
            background: #fff;
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.06);
        }

        .card-setting .card-header {
            background: transparent;
            border-bottom: 1px solid #eee;
            font-weight: 700;
            padding: 16px 20px;
        }

        .card-setting .card-body {
            padding: 20px;
        }

        .page-title {
            font-weight: 700;
            color: #333;
        }

        .btn-psb {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: #fff;
            border: none;
            font-weight: 600;
        }

        .btn-psb:hover {
            color: #fff;
            filter: brightness(1.05);
        }

        .badge-aktif {
            background: var(--primary-green);
        }

        .form-text code {
            color: var(--dark-green);
        }

        .table thead th {
            background: #f1f5f2;
            font-weight: 600;
            white-space: nowrap;
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/dashboard') ?>">
                <i class="icofont-dashboard me-2"></i> <?= esc(pengaturan('nama_singkat', 'PSB')) ?>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/dashboard') ?>"><i class="icofont-home me-1"></i> Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/admin/pengumuman') ?>"><i class="icofont-megaphone me-1"></i> Pengumuman</a></li>
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url('/admin/pengaturan') ?>"><i class="icofont-gear me-1"></i> Pengaturan</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/') ?>" target="_blank"><i class="icofont-external-link me-1"></i> Lihat Situs</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('/logout') ?>"><i class="icofont-logout me-1"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <div class="row g-4">
            <div class="col-lg-3 col-xl-2">
                <div class="card-setting p-2">
                    <?php $menu = $menu ?? ''; ?>
                    <div class="list-group settings-nav">
                        <a href="<?= base_url('admin/pengaturan/periode') ?>" class="list-group-item list-group-item-action <?= $menu === 'periode' ? 'active' : '' ?>">
                            <i class="icofont-calendar"></i> Periode &amp; Nomor
                        </a>
                        <a href="<?= base_url('admin/pengaturan/arsip') ?>" class="list-group-item list-group-item-action <?= $menu === 'arsip' ? 'active' : '' ?>">
                            <i class="icofont-archive"></i> Arsip Data
                        </a>
                        <a href="<?= base_url('admin/users') ?>" class="list-group-item list-group-item-action <?= $menu === 'users' ? 'active' : '' ?>">
                            <i class="icofont-users-alt-3"></i> User Panitia
                        </a>
                        <a href="<?= base_url('admin/pengaturan/landing') ?>" class="list-group-item list-group-item-action <?= $menu === 'landing' ? 'active' : '' ?>">
                            <i class="icofont-web"></i> Halaman Utama
                        </a>
                        <a href="<?= base_url('admin/pengaturan/tampilan') ?>" class="list-group-item list-group-item-action <?= $menu === 'tampilan' ? 'active' : '' ?>">
                            <i class="icofont-paint"></i> Logo &amp; Warna
                        </a>
                    </div>
                </div>
                <?php $pa = periode_aktif(); ?>
                <div class="card-setting p-3 mt-3 small">
                    <div class="text-muted mb-1">Periode aktif</div>
                    <?php if ($pa): ?>
                        <div class="fw-bold fs-6">TA <?= esc($pa['tahun_ajaran']) ?></div>
                        <?php $st = (new \App\Models\PeriodeModel())->statusPendaftaran(); ?>
                        <span class="badge <?= $st['dibuka'] ? 'bg-success' : 'bg-secondary' ?> mt-1">
                            <?= $st['dibuka'] ? 'Pendaftaran dibuka' : 'Pendaftaran ditutup' ?>
                        </span>
                    <?php else: ?>
                        <div class="fw-bold text-danger">Belum ada</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-lg-9 col-xl-10">
                <h3 class="page-title mb-3"><?= esc($judul ?? '') ?></h3>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <i class="icofont-check-circled me-1"></i> <?= esc(session()->getFlashdata('success')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <i class="icofont-warning me-1"></i> <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>
