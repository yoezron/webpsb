<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title) ?> - PSB Persis 31 Banjaran</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/logo/favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/logo/favicon-32x32.png') ?>">

    <!-- Hafsa Template CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <style>
        :root {
            --primary-yellow: #F3C623;
            --dark-yellow: #d4a81d;
            --light-yellow: #fef3c7;
            --primary-green: #1AB34A;
            --dark-green: #158a3a;
            --danger-red: #dc3545;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--primary-yellow) 0%, var(--dark-yellow) 100%);
            color: #333;
            z-index: 1000;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 25px 20px;
            text-align: center;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }

        .sidebar-logo {
            width: 70px;
            height: auto;
            margin-bottom: 10px;
            filter: drop-shadow(0 3px 6px rgba(0, 0, 0, 0.2));
        }

        .sidebar-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
            color: #333;
        }

        .sidebar-subtitle {
            font-size: 0.75rem;
            color: var(--dark-green);
            font-weight: 600;
        }

        .sidebar-menu {
            padding: 20px 0;
        }

        .menu-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(0, 0, 0, 0.4);
            padding: 10px 20px;
            margin-top: 10px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: rgba(0, 0, 0, 0.7);
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .menu-item:hover {
            background: rgba(255, 255, 255, 0.3);
            color: #333;
            border-left-color: var(--dark-green);
        }

        .menu-item.active {
            background: rgba(255, 255, 255, 0.4);
            color: #333;
            border-left-color: var(--dark-green);
        }

        .menu-item i {
            width: 20px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
        }

        /* Top Navbar */
        .top-navbar {
            background: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .navbar-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #333;
        }

        .navbar-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 0.9rem;
        }

        .user-role {
            font-size: 0.75rem;
            color: #666;
            text-transform: capitalize;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
            font-weight: 600;
        }

        .btn-logout {
            background: none;
            border: 2px solid var(--danger-red);
            color: var(--danger-red);
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .btn-logout:hover {
            background: var(--danger-red);
            color: white;
        }

        /* Content Area */
        .content-area {
            padding: 30px;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            gap: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }

        .stat-icon.yellow {
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            color: #333;
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stat-icon.pink {
            background: linear-gradient(135deg, #ec4899, #be185d);
        }

        .stat-icon.green {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        }

        .stat-icon.purple {
            background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        }

        .stat-icon.cyan {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
        }

        .stat-info h3 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .stat-info p {
            font-size: 0.85rem;
            color: #666;
            margin: 0;
        }

        /* Table Card */
        .table-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .table-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .table-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #333;
        }

        .table-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .search-box {
            position: relative;
        }

        .search-box input {
            padding: 10px 15px 10px 40px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 0.9rem;
            width: 250px;
            transition: all 0.3s ease;
        }

        .search-box input:focus {
            border-color: var(--primary-yellow);
            outline: none;
            box-shadow: 0 0 0 3px rgba(243, 198, 35, 0.1);
        }

        .search-box i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
        }

        .btn-export {
            padding: 10px 20px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-export.csv {
            background: linear-gradient(135deg, var(--primary-yellow), var(--dark-yellow));
            color: #333;
            border: none;
        }

        .btn-export.excel {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            border: none;
        }

        .btn-export:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Table */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table th {
            background: #f8f9fa;
            padding: 15px;
            text-align: left;
            font-weight: 600;
            color: #333;
            font-size: 0.85rem;
            border-bottom: 2px solid #eee;
            white-space: nowrap;
        }

        .data-table th a {
            color: #333;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .data-table th a:hover {
            color: var(--primary-yellow);
        }

        .data-table td {
            padding: 15px;
            border-bottom: 1px solid #eee;
            font-size: 0.9rem;
            color: #555;
        }

        .data-table tbody tr:hover {
            background-color: #f8f9fa;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 500;
        }

        .badge-male {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-female {
            background: #fce7f3;
            color: #be185d;
        }

        .btn-action {
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            border: none;
        }

        .btn-action.view {
            background: #e0f2fe;
            color: #0284c7;
        }

        .btn-action.view:hover {
            background: #0284c7;
            color: white;
        }

        .btn-action.pdf {
            background: #fee2e2;
            color: #dc2626;
        }

        .btn-action.pdf:hover {
            background: #dc2626;
            color: white;
        }

        /* Pagination */
        .table-footer {
            padding: 20px 25px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .pagination-info {
            font-size: 0.9rem;
            color: #666;
        }

        .pagination {
            display: flex;
            gap: 5px;
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pagination li a,
        .pagination li span {
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 0.9rem;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .pagination li a {
            background: #f0f0f0;
            color: #333;
        }

        .pagination li a:hover {
            background: var(--primary-yellow);
            color: #333;
        }

        .pagination li.active span {
            background: var(--primary-yellow);
            color: #333;
        }

        .pagination li.disabled span {
            background: #f0f0f0;
            color: #999;
            cursor: not-allowed;
        }

        .per-page-select {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .per-page-select select {
            padding: 8px 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 0.9rem;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }

        .empty-state h3 {
            font-size: 1.3rem;
            color: #666;
            margin-bottom: 10px;
        }

        .empty-state p {
            color: #999;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .menu-toggle {
                display: block !important;
            }
        }

        @media (max-width: 768px) {
            .table-header {
                flex-direction: column;
                align-items: stretch;
            }

            .search-box input {
                width: 100%;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .top-navbar {
                padding: 15px;
            }

            .content-area {
                padding: 15px;
            }

            .data-table {
                display: block;
                overflow-x: auto;
            }
        }

        .menu-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #333;
            cursor: pointer;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        .sort-icon {
            font-size: 0.7rem;
            opacity: 0.5;
        }

        .sort-icon.active {
            opacity: 1;
            color: var(--primary-yellow);
        }
    </style>
</head>

<body>

    <!-- Overlay for mobile -->
    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <img src="<?= base_url('assets/images/logo/01.png') ?>" alt="Logo" class="sidebar-logo">
            <h1 class="sidebar-title">PSB Persis 31</h1>
            <p class="sidebar-subtitle">Panel Panitia</p>
        </div>

        <nav class="sidebar-menu">
            <div class="menu-label">Menu Utama</div>
            <?php if ($user['role'] === 'superadmin'): ?>
                <a href="<?= base_url('dashboard/tsanawiyyah') ?>" class="menu-item">
                    <i class="icofont-dashboard"></i>
                    <span>Dashboard Tsanawiyyah</span>
                </a>
            <?php endif; ?>

            <a href="<?= base_url('dashboard/muallimin') ?>" class="menu-item active">
                <i class="icofont-dashboard-web"></i>
                <span>Dashboard Muallimin</span>
            </a>

            <div class="menu-label">Aksi</div>
            <a href="<?= base_url('dashboard/export/csv') ?>?jalur=MUALLIMIN" class="menu-item">
                <i class="icofont-file-text"></i>
                <span>Export CSV</span>
            </a>
            <a href="<?= base_url('dashboard/export/excel') ?>?jalur=MUALLIMIN" class="menu-item">
                <i class="icofont-file-excel"></i>
                <span>Export Excel</span>
            </a>

            <div class="menu-label">Akun</div>
            <a href="<?= base_url('auth/logout') ?>" class="menu-item">
                <i class="icofont-logout"></i>
                <span>Logout</span>
            </a>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="menu-toggle" onclick="toggleSidebar()">
                    <i class="icofont-navigation-menu"></i>
                </button>
                <h2 class="navbar-title">Dashboard Mu'allimin</h2>
            </div>
            <div class="navbar-user">
                <div class="user-info">
                    <div class="user-name"><?= esc($user['nama']) ?></div>
                    <div class="user-role"><?= esc($user['role']) ?></div>
                </div>
                <div class="user-avatar">
                    <?= strtoupper(substr($user['nama'], 0, 1)) ?>
                </div>
                <a href="<?= base_url('auth/logout') ?>" class="btn-logout">
                    <i class="icofont-logout"></i> Logout
                </a>
            </div>
        </header>

        <!-- Content Area -->
        <div class="content-area">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger animate__animated animate__fadeIn">
                    <i class="icofont-warning-alt"></i>
                    <?= session()->getFlashdata('error') ?>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success animate__animated animate__fadeIn">
                    <i class="icofont-check-circled"></i>
                    <?= session()->getFlashdata('success') ?>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="stats-grid animate__animated animate__fadeIn">
                <div class="stat-card">
                    <div class="stat-icon yellow">
                        <i class="icofont-users-alt-5"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['total']) ?></h3>
                        <p>Total Pendaftar</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="icofont-user-male"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['laki_laki']) ?></h3>
                        <p>Laki-laki</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon pink">
                        <i class="icofont-user-female"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['perempuan']) ?></h3>
                        <p>Perempuan</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="icofont-calendar"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['hari_ini']) ?></h3>
                        <p>Hari Ini</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon purple">
                        <i class="icofont-ui-calendar"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['minggu_ini']) ?></h3>
                        <p>Minggu Ini</p>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-icon cyan">
                        <i class="icofont-chart-bar-graph"></i>
                    </div>
                    <div class="stat-info">
                        <h3><?= number_format($stats['bulan_ini']) ?></h3>
                        <p>Bulan Ini</p>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="table-card animate__animated animate__fadeInUp">
                <div class="table-header">
                    <h3 class="table-title">Data Pendaftar Mu'allimin</h3>
                    <div class="table-actions">
                        <form action="" method="GET" class="search-box">
                            <i class="icofont-search-1"></i>
                            <input type="text"
                                   name="search"
                                   placeholder="Cari nama, NISN, NIK..."
                                   value="<?= esc($search) ?>"
                                   id="searchInput">
                            <input type="hidden" name="sortBy" value="<?= esc($sortBy) ?>">
                            <input type="hidden" name="sortOrder" value="<?= esc($sortOrder) ?>">
                            <input type="hidden" name="perPage" value="<?= esc($perPage) ?>">
                        </form>
                        <a href="<?= base_url('dashboard/export/csv') ?>?jalur=MUALLIMIN" class="btn-export csv">
                            <i class="icofont-file-text"></i> CSV
                        </a>
                        <a href="<?= base_url('dashboard/export/excel') ?>?jalur=MUALLIMIN" class="btn-export excel">
                            <i class="icofont-file-excel"></i> Excel
                        </a>
                    </div>
                </div>

                <?php if (empty($pendaftar)): ?>
                    <div class="empty-state">
                        <i class="icofont-ui-search"></i>
                        <h3>Tidak Ada Data</h3>
                        <p>Belum ada pendaftar yang terdaftar atau tidak ditemukan hasil pencarian.</p>
                    </div>
                <?php else: ?>
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>
                                        <a href="?search=<?= esc($search) ?>&sortBy=nomor_pendaftaran&sortOrder=<?= $sortBy === 'nomor_pendaftaran' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>&perPage=<?= $perPage ?>">
                                            No. Pendaftaran
                                            <i class="icofont-arrow-<?= $sortBy === 'nomor_pendaftaran' ? ($sortOrder === 'ASC' ? 'up' : 'down') : 'down' ?> sort-icon <?= $sortBy === 'nomor_pendaftaran' ? 'active' : '' ?>"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?search=<?= esc($search) ?>&sortBy=nama_lengkap&sortOrder=<?= $sortBy === 'nama_lengkap' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>&perPage=<?= $perPage ?>">
                                            Nama Lengkap
                                            <i class="icofont-arrow-<?= $sortBy === 'nama_lengkap' ? ($sortOrder === 'ASC' ? 'up' : 'down') : 'down' ?> sort-icon <?= $sortBy === 'nama_lengkap' ? 'active' : '' ?>"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?search=<?= esc($search) ?>&sortBy=jenis_kelamin&sortOrder=<?= $sortBy === 'jenis_kelamin' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>&perPage=<?= $perPage ?>">
                                            Jenis Kelamin
                                            <i class="icofont-arrow-<?= $sortBy === 'jenis_kelamin' ? ($sortOrder === 'ASC' ? 'up' : 'down') : 'down' ?> sort-icon <?= $sortBy === 'jenis_kelamin' ? 'active' : '' ?>"></i>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?search=<?= esc($search) ?>&sortBy=tanggal_lahir&sortOrder=<?= $sortBy === 'tanggal_lahir' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>&perPage=<?= $perPage ?>">
                                            TTL
                                            <i class="icofont-arrow-<?= $sortBy === 'tanggal_lahir' ? ($sortOrder === 'ASC' ? 'up' : 'down') : 'down' ?> sort-icon <?= $sortBy === 'tanggal_lahir' ? 'active' : '' ?>"></i>
                                        </a>
                                    </th>
                                    <th>No. HP</th>
                                    <th>
                                        <a href="?search=<?= esc($search) ?>&sortBy=tanggal_daftar&sortOrder=<?= $sortBy === 'tanggal_daftar' && $sortOrder === 'ASC' ? 'DESC' : 'ASC' ?>&perPage=<?= $perPage ?>">
                                            Tanggal Daftar
                                            <i class="icofont-arrow-<?= $sortBy === 'tanggal_daftar' ? ($sortOrder === 'ASC' ? 'up' : 'down') : 'down' ?> sort-icon <?= $sortBy === 'tanggal_daftar' ? 'active' : '' ?>"></i>
                                        </a>
                                    </th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = ($pager->getCurrentPage() - 1) * $perPage + 1;
                                foreach ($pendaftar as $p):
                                ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><strong><?= esc($p['nomor_pendaftaran']) ?></strong></td>
                                        <td><?= esc($p['nama_lengkap']) ?></td>
                                        <td>
                                            <span class="badge <?= $p['jenis_kelamin'] === 'L' ? 'badge-male' : 'badge-female' ?>">
                                                <?= $p['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                            </span>
                                        </td>
                                        <td><?= esc($p['tempat_lahir']) ?>, <?= date('d/m/Y', strtotime($p['tanggal_lahir'])) ?></td>
                                        <td><?= esc($p['no_hp']) ?></td>
                                        <td><?= date('d/m/Y H:i', strtotime($p['tanggal_daftar'])) ?></td>
                                        <td>
                                            <a href="<?= base_url('dashboard/detail/' . $p['id_pendaftar']) ?>" class="btn-action view" title="Lihat Detail">
                                                <i class="icofont-eye-alt"></i>
                                            </a>
                                            <a href="<?= base_url('pendaftaran/download-kartu/' . $p['id_pendaftar']) ?>" class="btn-action pdf" title="Download PDF" target="_blank">
                                                <i class="icofont-file-pdf"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="table-footer">
                        <div class="pagination-info">
                            Menampilkan <?= (($pager->getCurrentPage() - 1) * $perPage) + 1 ?> -
                            <?= min($pager->getCurrentPage() * $perPage, $totalRecords) ?>
                            dari <?= number_format($totalRecords) ?> data
                        </div>

                        <div class="per-page-select">
                            <label>Tampilkan:</label>
                            <select onchange="changePerPage(this.value)">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>

                        <?= $pager->links('default', 'custom_pagination') ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        let searchTimeout;
        document.getElementById('searchInput').addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                this.form.submit();
            }, 500);
        });

        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', value);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }
    </script>

</body>

</html>
