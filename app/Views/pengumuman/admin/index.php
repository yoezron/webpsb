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

        .btn-custom-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-custom-primary:hover {
            background: var(--dark-green);
            color: white;
        }

        .badge-active {
            background: var(--primary-green);
            color: white;
        }

        .badge-inactive {
            background: #6c757d;
            color: white;
        }

        .announcement-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .btn-logout {
            background: #e74c3c;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
        }

        .btn-logout:hover {
            background: #c0392b;
            color: white;
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
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('/admin/pengumuman') ?>">
                            <i class="icofont-megaphone me-1"></i> Pengumuman
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="icofont-user-alt-4 me-1"></i> <?= esc($user['nama_lengkap']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><span class="dropdown-item-text text-muted"><?= esc($user['role_panitia']) ?></span></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('/logout') ?>"><i class="icofont-logout me-2"></i>Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="icofont-check-circled me-2"></i><?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="icofont-warning me-2"></i><?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0"><i class="icofont-megaphone me-2"></i>Kelola Pengumuman</h4>
            <a href="<?= base_url('/admin/pengumuman/create') ?>" class="btn btn-custom-primary">
                <i class="icofont-plus me-2"></i>Buat Pengumuman
            </a>
        </div>

        <!-- Announcements Table -->
        <div class="table-card">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th width="80">Gambar</th>
                            <th>Judul</th>
                            <th>Pembuat</th>
                            <th width="100" class="text-center">Status</th>
                            <th width="100" class="text-center"><i class="icofont-heart"></i> Likes</th>
                            <th width="100" class="text-center"><i class="icofont-comment"></i> Balasan</th>
                            <th>Tanggal</th>
                            <th width="150" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($announcements)) : ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">
                                    <i class="icofont-info-circle fs-1 d-block mb-2"></i>
                                    Belum ada pengumuman
                                </td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($announcements as $announcement) : ?>
                                <tr>
                                    <td>
                                        <?php if ($announcement['gambar']) : ?>
                                            <img src="<?= base_url('uploads/pengumuman/' . $announcement['gambar']) ?>" class="announcement-thumb" alt="Gambar">
                                        <?php else : ?>
                                            <div class="announcement-thumb bg-light d-flex align-items-center justify-content-center">
                                                <i class="icofont-image text-muted"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?= esc($announcement['judul']) ?></strong>
                                        <br><small class="text-muted"><?= esc(substr(strip_tags($announcement['konten']), 0, 80)) ?>...</small>
                                    </td>
                                    <td><?= esc($announcement['admin_nama']) ?></td>
                                    <td class="text-center">
                                        <?php if ($announcement['is_active']) : ?>
                                            <span class="badge badge-active">Aktif</span>
                                        <?php else : ?>
                                            <span class="badge badge-inactive">Nonaktif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center"><?= $announcement['likes_count'] ?></td>
                                    <td class="text-center"><?= $announcement['replies_count'] ?></td>
                                    <td><?= date('d M Y H:i', strtotime($announcement['created_at'])) ?></td>
                                    <td class="text-center">
                                        <div class="btn-group btn-group-sm">
                                            <a href="<?= base_url('/admin/pengumuman/view/' . $announcement['id_pengumuman']) ?>" class="btn btn-info" title="Lihat">
                                                <i class="icofont-eye"></i>
                                            </a>
                                            <a href="<?= base_url('/admin/pengumuman/edit/' . $announcement['id_pengumuman']) ?>" class="btn btn-warning" title="Edit">
                                                <i class="icofont-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger" title="Hapus" onclick="confirmDelete(<?= $announcement['id_pengumuman'] ?>)">
                                                <i class="icofont-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus pengumuman ini?</p>
                    <p class="text-muted small">Semua balasan dan likes juga akan dihapus.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="deleteLink" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        function confirmDelete(id) {
            document.getElementById('deleteLink').href = '<?= base_url('/admin/pengumuman/delete/') ?>' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
</body>

</html>
