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
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            text-align: center;
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 3rem;
            color: white;
            margin: 0 auto 20px;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 700;
            color: var(--primary-green);
            margin: 0;
        }

        .stat-label {
            color: #666;
            font-size: 1.1rem;
            font-weight: 600;
            margin-top: 10px;
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
                        <a class="nav-link" href="<?= base_url('/dashboard') ?>">
                            <i class="icofont-home me-1"></i> Dashboard
                        </a>
                    </li>
                    <?php if ($user['role_panitia'] === 'superadmin' || $user['role_panitia'] === 'tsanawiyyah'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $jalur === 'Tsanawiyyah' ? 'active' : '' ?>"
                           href="<?= base_url('/dashboard/tsanawiyyah') ?>">
                            <i class="icofont-book-alt me-1"></i> Tsanawiyyah
                        </a>
                    </li>
                    <?php endif; ?>
                    <?php if ($user['role_panitia'] === 'superadmin' || $user['role_panitia'] === 'muallimin'): ?>
                    <li class="nav-item">
                        <a class="nav-link <?= $jalur === 'Mu\'allimin' ? 'active' : '' ?>"
                           href="<?= base_url('/dashboard/muallimin') ?>">
                            <i class="icofont-graduate-alt me-1"></i> Mu'allimin
                        </a>
                    </li>
                    <?php endif; ?>
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
            <h2>
                <i class="<?= $jalur === 'Tsanawiyyah' ? 'icofont-book-alt' : 'icofont-graduate-alt' ?> me-2"></i>
                Dashboard <?= esc($jalur) ?>
            </h2>
            <p class="mb-0">Selamat datang, <strong><?= esc($user['nama_lengkap']) ?></strong> - Role: <?= ucfirst(esc($user['role_panitia'])) ?></p>
        </div>

        <!-- Statistics Card -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="stat-card">
                    <div class="stat-icon">
                        <i class="<?= $jalur === 'Tsanawiyyah' ? 'icofont-book-alt' : 'icofont-graduate-alt' ?>"></i>
                    </div>
                    <h3 class="stat-number"><?= esc($total_registrations) ?></h3>
                    <p class="stat-label">Total Pendaftar <?= esc($jalur) ?></p>
                </div>
            </div>
        </div>

        <!-- Registrations Table -->
        <div class="row">
            <div class="col-12">
                <div class="table-card">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <h5 class="mb-0">
                            <i class="icofont-users-alt-2 me-2"></i>
                            Daftar Pendaftar <?= esc($jalur) ?>
                        </h5>
                        <div class="d-flex gap-2 align-items-center flex-wrap">
                            <form method="get" class="d-flex gap-2">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama, NISN, NIK..."
                                       value="<?= esc($search) ?>" style="width: 300px;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="icofont-search me-1"></i> Cari
                                </button>
                                <?php if (!empty($search)): ?>
                                    <a href="<?= current_url() ?>" class="btn btn-secondary">
                                        <i class="icofont-close me-1"></i> Reset
                                    </a>
                                <?php endif; ?>
                            </form>
                            <a href="<?= base_url('dashboard/export-csv?jalur=' . strtolower($jalur) . (!empty($search) ? '&search=' . urlencode($search) : '') . '&sort=' . $sortBy . '&dir=' . $sortDir) ?>"
                               class="btn btn-success">
                                <i class="icofont-file-excel me-1"></i> Export CSV
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>
                                        <a href="?sort=nama_lengkap&dir=<?= $sortBy === 'nama_lengkap' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                           class="text-white text-decoration-none">
                                            Nama Lengkap
                                            <?php if ($sortBy === 'nama_lengkap'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?sort=jenis_kelamin&dir=<?= $sortBy === 'jenis_kelamin' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                           class="text-white text-decoration-none">
                                            Jenis Kelamin
                                            <?php if ($sortBy === 'jenis_kelamin'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?sort=tempat_lahir&dir=<?= $sortBy === 'tempat_lahir' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                           class="text-white text-decoration-none">
                                            Tempat Lahir
                                            <?php if ($sortBy === 'tempat_lahir'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>Tanggal Lahir</th>
                                    <th>Asal Sekolah</th>
                                    <th>
                                        <a href="?sort=kecamatan&dir=<?= $sortBy === 'kecamatan' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
                                           class="text-white text-decoration-none">
                                            Lokasi
                                            <?php if ($sortBy === 'kecamatan'): ?>
                                                <i class="icofont-<?= $sortDir === 'ASC' ? 'arrow-up' : 'arrow-down' ?>"></i>
                                            <?php endif; ?>
                                        </a>
                                    </th>
                                    <th>
                                        <a href="?sort=tanggal_daftar&dir=<?= $sortBy === 'tanggal_daftar' && $sortDir === 'ASC' ? 'DESC' : 'ASC' ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?>"
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
                                <?php if (!empty($registrations)): ?>
                                    <?php
                                    $startIndex = ($pager->getCurrentPage() - 1) * $pager->getPerPage();
                                    foreach ($registrations as $index => $reg):
                                    ?>
                                        <tr>
                                            <td><?= $startIndex + $index + 1 ?></td>
                                            <td><strong><?= esc($reg['nama_lengkap']) ?></strong></td>
                                            <td>
                                                <i class="<?= $reg['jenis_kelamin'] === 'L' ? 'icofont-boy' : 'icofont-girl' ?> me-1"></i>
                                                <?= esc($reg['jenis_kelamin']) === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                                            </td>
                                            <td><?= esc($reg['tempat_lahir'] ?? '-') ?></td>
                                            <td><?= !empty($reg['tanggal_lahir']) ? date('d/m/Y', strtotime($reg['tanggal_lahir'])) : '-' ?></td>
                                            <td><?= esc($reg['asal_sekolah'] ?? '-') ?></td>
                                            <td><?= esc($reg['kecamatan'] ?? '-') ?></td>
                                            <td><?= date('d/m/Y', strtotime($reg['tanggal_daftar'])) ?></td>
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
                                        <td colspan="9" class="text-center py-4">
                                            <i class="icofont-info-circle me-2"></i> Belum ada pendaftar untuk jalur <?= esc($jalur) ?>
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
                                sampai <?= min($pager->getCurrentPage() * $pager->getPerPage(), $total_registrations) ?>
                                dari <?= $total_registrations ?> data
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
