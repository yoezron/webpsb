<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
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

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 70px;
            height: 70px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: white;
            margin-bottom: 15px;
        }

        .stat-icon.green {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        }

        .stat-icon.yellow {
            background: linear-gradient(135deg, var(--secondary-yellow), #e0b518);
        }

        .stat-icon.blue {
            background: linear-gradient(135deg, #3498db, #2980b9);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #333;
            margin: 0;
        }

        .stat-label {
            color: #666;
            font-size: 0.95rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .table-card h5 {
            color: #333;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .table thead {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
        }

        .table thead th {
            border: none;
            font-weight: 600;
            padding: 15px;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .badge-custom {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge-tsn {
            background: var(--primary-green);
            color: white;
        }

        .badge-mua {
            background: var(--secondary-yellow);
            color: #333;
        }

        .user-info {
            background: white;
            padding: 15px;
            border-radius: 10px;
        }

        .btn-logout {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-logout:hover {
            background: #c0392b;
            color: white;
        }

        .welcome-banner {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url('/dashboard') ?>">
                <i class="icofont-dashboard me-2"></i> PSB Persis 31
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('/dashboard') ?>">
                            <i class="icofont-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/dashboard/tsanawiyyah') ?>">
                            <i class="icofont-book-alt me-1"></i> Tsanawiyyah
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/dashboard/muallimin') ?>">
                            <i class="icofont-graduate-alt me-1"></i> Mu'allimin
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="icofont-user-alt-4 me-1"></i> <?= esc($user['nama_lengkap']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">
                                <i class="icofont-ui-settings me-2"></i> Pengaturan
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>">
                                <i class="icofont-logout me-2"></i> Logout
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">

        <!-- Welcome Banner -->
        <div class="welcome-banner">
            <h2><i class="icofont-crown me-2"></i> Selamat Datang, <?= esc($user['nama_lengkap']) ?>!</h2>
            <p class="mb-0">Role: <strong>Superadmin</strong> - Anda memiliki akses penuh ke seluruh sistem</p>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <!-- Total All -->
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon blue">
                        <i class="icofont-users-alt-2"></i>
                    </div>
                    <h3 class="stat-number"><?= esc($stats['total_all']) ?></h3>
                    <p class="stat-label">Total Pendaftar</p>
                </div>
            </div>

            <!-- Total Tsanawiyyah -->
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon green">
                        <i class="icofont-book-alt"></i>
                    </div>
                    <h3 class="stat-number"><?= esc($stats['total_tsn']) ?></h3>
                    <p class="stat-label">Tsanawiyyah</p>
                </div>
            </div>

            <!-- Total Muallimin -->
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="stat-icon yellow">
                        <i class="icofont-graduate-alt"></i>
                    </div>
                    <h3 class="stat-number"><?= esc($stats['total_mua']) ?></h3>
                    <p class="stat-label">Mu'allimin</p>
                </div>
            </div>
        </div>

        <!-- Recent Registrations Table -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">
                            <i class="icofont-clock-time me-2"></i> Semua Pendaftaran
                        </h5>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <form method="get" class="d-flex gap-2 flex-wrap">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, NISN, NIK..."
                                       value="<?= esc($search) ?>" style="width: 250px;">
                                <input type="date" name="start_date" class="form-control" placeholder="Dari Tanggal"
                                       value="<?= esc($startDate) ?>" title="Dari Tanggal" style="width: 150px;">
                                <input type="date" name="end_date" class="form-control" placeholder="Sampai Tanggal"
                                       value="<?= esc($endDate) ?>" title="Sampai Tanggal" style="width: 150px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icofont-search me-1"></i> Filter
                                </button>
                                <?php if (!empty($search) || !empty($startDate) || !empty($endDate)): ?>
                                    <a href="<?= current_url() ?>" class="btn btn-secondary">
                                        <i class="icofont-close me-1"></i> Reset
                                    </a>
                                <?php endif; ?>
                            </form>
                            <div class="btn-group">
                                <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="icofont-file-excel me-1"></i> Export
                                </button>
                                <ul class="dropdown-menu">
                                    <li><h6 class="dropdown-header">CSV Format</h6></li>
                                    <li><a class="dropdown-item" href="<?= base_url('dashboard/export-csv?jalur=all' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($startDate) ? '&start_date=' . $startDate : '') . (!empty($endDate) ? '&end_date=' . $endDate : '') . '&sort=' . $sortBy . '&dir=' . $sortDir) ?>">
                                        <i class="icofont-file-text me-1"></i> Export Semua (CSV)
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('dashboard/export-csv?jalur=tsanawiyyah' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($startDate) ? '&start_date=' . $startDate : '') . (!empty($endDate) ? '&end_date=' . $endDate : '') . '&sort=' . $sortBy . '&dir=' . $sortDir) ?>">
                                        <i class="icofont-file-text me-1"></i> Tsanawiyyah (CSV)
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('dashboard/export-csv?jalur=muallimin' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($startDate) ? '&start_date=' . $startDate : '') . (!empty($endDate) ? '&end_date=' . $endDate : '') . '&sort=' . $sortBy . '&dir=' . $sortDir) ?>">
                                        <i class="icofont-file-text me-1"></i> Mu'allimin (CSV)
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><h6 class="dropdown-header">Excel Format</h6></li>
                                    <li><a class="dropdown-item" href="<?= base_url('dashboard/export-excel?jalur=all' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($startDate) ? '&start_date=' . $startDate : '') . (!empty($endDate) ? '&end_date=' . $endDate : '') . '&sort=' . $sortBy . '&dir=' . $sortDir) ?>">
                                        <i class="icofont-file-excel me-1"></i> Export Semua (Excel)
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('dashboard/export-excel?jalur=tsanawiyyah' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($startDate) ? '&start_date=' . $startDate : '') . (!empty($endDate) ? '&end_date=' . $endDate : '') . '&sort=' . $sortBy . '&dir=' . $sortDir) ?>">
                                        <i class="icofont-file-excel me-1"></i> Tsanawiyyah (Excel)
                                    </a></li>
                                    <li><a class="dropdown-item" href="<?= base_url('dashboard/export-excel?jalur=muallimin' . (!empty($search) ? '&search=' . urlencode($search) : '') . (!empty($startDate) ? '&start_date=' . $startDate : '') . (!empty($endDate) ? '&end_date=' . $endDate : '') . '&sort=' . $sortBy . '&dir=' . $sortDir) ?>">
                                        <i class="icofont-file-excel me-1"></i> Mu'allimin (Excel)
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>
                                        <a href="?sort=nama_lengkap&dir=<?= $sortBy === 'nama_lengkap' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($startDate) ? '&start_date=' . $startDate : '' ?><?= !empty($endDate) ? '&end_date=' . $endDate : '' ?>"
                                           class="text-white text-decoration-none">
                                            Nama Lengkap
                                            <?php if ($sortBy === 'nama_lengkap'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?sort=jalur_pendaftaran&dir=<?= $sortBy === 'jalur_pendaftaran' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($startDate) ? '&start_date=' . $startDate : '' ?><?= !empty($endDate) ? '&end_date=' . $endDate : '' ?>"
                                           class="text-white text-decoration-none">
                                            Jalur
                                            <?php if ($sortBy === 'jalur_pendaftaran'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?sort=jenis_kelamin&dir=<?= $sortBy === 'jenis_kelamin' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($startDate) ? '&start_date=' . $startDate : '' ?><?= !empty($endDate) ? '&end_date=' . $endDate : '' ?>"
                                           class="text-white text-decoration-none">
                                            Jenis Kelamin
                                            <?php if ($sortBy === 'jenis_kelamin'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Asal Sekolah</th>
                                    <th>
                                        <a href="?sort=kecamatan&dir=<?= $sortBy === 'kecamatan' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($startDate) ? '&start_date=' . $startDate : '' ?><?= !empty($endDate) ? '&end_date=' . $endDate : '' ?>"
                                           class="text-white text-decoration-none">
                                            Lokasi
                                            <?php if ($sortBy === 'kecamatan'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?sort=tanggal_daftar&dir=<?= $sortBy === 'tanggal_daftar' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= !empty($startDate) ? '&start_date=' . $startDate : '' ?><?= !empty($endDate) ? '&end_date=' . $endDate : '' ?>"
                                           class="text-white text-decoration-none">
                                            Tanggal Daftar
                                            <?php if ($sortBy === 'tanggal_daftar'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($recent_registrations)): ?>
                                    <?php
                                    $startIndex = ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                    foreach ($recent_registrations as $index => $reg):
                                    ?>
                                        <tr>
                                            <td><?= $startIndex + $index + 1 ?></td>
                                            <td><strong><?= esc($reg['nama_lengkap']) ?></strong></td>
                                            <td>
                                                <span class="badge-custom <?= $reg['jalur_pendaftaran'] === 'tsanawiyyah' ? 'badge-tsn' : 'badge-mua' ?>">
                                                    <?= ucfirst(esc($reg['jalur_pendaftaran'])) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($reg['jenis_kelamin']) === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                                            <td><?= esc($reg['asal_sekolah'] ?? '-') ?></td>
                                            <td><?= esc($reg['kecamatan'] ?? '-') ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($reg['tanggal_daftar'])) ?></td>
                                            <td>
                                                <a href="<?= base_url('dashboard/detail/' . $reg['id_pendaftar']) ?>"
                                                   class="btn btn-sm btn-primary me-1" title="Lihat Detail">
                                                    <i class="icofont-eye"></i>
                                                </a>
                                                <a href="<?= base_url('pendaftaran/download-kartu/' . $reg['id_pendaftar']) ?>"
                                                   class="btn btn-sm btn-success" title="Download Kartu">
                                                    <i class="icofont-download"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="icofont-info-circle me-2"></i> Belum ada pendaftaran
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if ($pager->getPageCount() > 1): ?>
                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div>
                                Menampilkan <?= ($pager->getCurrentPage() - 1) * $pager->getPerPage() + 1 ?>
                                sampai <?= min($pager->getCurrentPage() * $pager->getPerPage(), $stats['total_all']) ?>
                                dari <?= $stats['total_all'] ?> data
                            </div>
                            <nav>
                                <?= $pager->links('default', 'default_full') ?>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
</body>

</html>
