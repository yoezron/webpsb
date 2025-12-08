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

        .page-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 60px 0;
            text-align: center;
        }

        .announcement-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .announcement-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        }

        .announcement-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .announcement-body {
            padding: 20px;
        }

        .announcement-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 10px;
        }

        .announcement-title a {
            color: inherit;
            text-decoration: none;
        }

        .announcement-title a:hover {
            color: var(--primary-green);
        }

        .announcement-meta {
            color: #666;
            font-size: 0.85rem;
            margin-bottom: 10px;
        }

        .announcement-excerpt {
            color: #555;
            line-height: 1.6;
        }

        .announcement-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .like-btn {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            transition: color 0.3s ease;
            font-size: 1rem;
        }

        .like-btn:hover,
        .like-btn.liked {
            color: #e74c3c;
        }

        .btn-read-more {
            background: var(--primary-green);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .btn-read-more:hover {
            background: var(--dark-green);
            color: white;
        }

        .pagination-custom .page-link {
            color: var(--primary-green);
            border: 1px solid var(--primary-green);
        }

        .pagination-custom .page-item.active .page-link {
            background: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
        }

        .no-image-placeholder {
            width: 100%;
            height: 200px;
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 3rem;
        }

        .footer {
            background: #222;
            color: white;
            padding: 30px 0;
            text-align: center;
            margin-top: 50px;
        }

        .footer a {
            color: var(--secondary-yellow);
            text-decoration: none;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="icofont-education me-2"></i> PSB Persis 31
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">
                            <i class="icofont-home me-1"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('/pengumuman') ?>">
                            <i class="icofont-megaphone me-1"></i> Pengumuman
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/daftar/tsanawiyyah') ?>">
                            <i class="icofont-edit me-1"></i> Daftar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Header -->
    <div class="page-header">
        <div class="container">
            <h1><i class="icofont-megaphone me-3"></i>Pengumuman</h1>
            <p class="lead mb-0">Informasi terbaru dari Pesantren Persatuan Islam 31 Banjaran</p>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="container py-5">
        <?php if (empty($announcements)) : ?>
            <div class="text-center py-5">
                <i class="icofont-info-circle fs-1 text-muted mb-3 d-block"></i>
                <h4 class="text-muted">Belum ada pengumuman</h4>
                <p class="text-muted">Pengumuman akan ditampilkan di sini setelah dipublikasikan.</p>
                <a href="<?= base_url('/') ?>" class="btn btn-read-more mt-3">
                    <i class="icofont-arrow-left me-2"></i>Kembali ke Beranda
                </a>
            </div>
        <?php else : ?>
            <div class="row">
                <?php foreach ($announcements as $announcement) : ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="announcement-card">
                            <?php if ($announcement['gambar']) : ?>
                                <img src="<?= base_url('uploads/pengumuman/' . $announcement['gambar']) ?>" class="announcement-image" alt="<?= esc($announcement['judul']) ?>">
                            <?php else : ?>
                                <div class="no-image-placeholder">
                                    <i class="icofont-megaphone"></i>
                                </div>
                            <?php endif; ?>

                            <div class="announcement-body">
                                <h3 class="announcement-title">
                                    <a href="<?= base_url('/pengumuman/' . $announcement['id_pengumuman']) ?>">
                                        <?= esc($announcement['judul']) ?>
                                    </a>
                                </h3>
                                <div class="announcement-meta">
                                    <i class="icofont-calendar me-1"></i>
                                    <?= date('d M Y', strtotime($announcement['created_at'])) ?>
                                    <span class="mx-2">|</span>
                                    <i class="icofont-user me-1"></i>
                                    <?= esc($announcement['admin_nama']) ?>
                                </div>
                                <p class="announcement-excerpt">
                                    <?= esc(substr(strip_tags($announcement['konten']), 0, 120)) ?>...
                                </p>
                                <div class="announcement-footer">
                                    <div class="d-flex gap-3">
                                        <button type="button" class="like-btn <?= $announcement['user_liked'] ? 'liked' : '' ?>" onclick="toggleLike(<?= $announcement['id_pengumuman'] ?>, this)">
                                            <i class="icofont-heart"></i>
                                            <span class="like-count"><?= $announcement['likes_count'] ?></span>
                                        </button>
                                        <span class="text-muted">
                                            <i class="icofont-comment"></i>
                                            <?= $announcement['replies_count'] ?>
                                        </span>
                                    </div>
                                    <a href="<?= base_url('/pengumuman/' . $announcement['id_pengumuman']) ?>" class="btn-read-more">
                                        Baca <i class="icofont-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pager['total'] > 1) : ?>
                <nav class="mt-4">
                    <ul class="pagination pagination-custom justify-content-center">
                        <?php if ($pager['current'] > 1) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pager['current'] - 1 ?>">
                                    <i class="icofont-arrow-left"></i>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php for ($i = 1; $i <= $pager['total']; $i++) : ?>
                            <li class="page-item <?= $i == $pager['current'] ? 'active' : '' ?>">
                                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($pager['current'] < $pager['total']) : ?>
                            <li class="page-item">
                                <a class="page-link" href="?page=<?= $pager['current'] + 1 ?>">
                                    <i class="icofont-arrow-right"></i>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Pesantren Persatuan Islam 31 Banjaran. All Rights Reserved.</p>
            <p>
                <a href="<?= base_url('/') ?>">Beranda</a> |
                <a href="<?= base_url('/pengumuman') ?>">Pengumuman</a> |
                <a href="<?= base_url('/daftar/tsanawiyyah') ?>">Pendaftaran</a>
            </p>
        </div>
    </footer>

    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        function toggleLike(id, btn) {
            fetch('<?= base_url('/api/pengumuman/like/') ?>' + id, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const countEl = btn.querySelector('.like-count');
                        countEl.textContent = data.count;
                        if (data.action === 'liked') {
                            btn.classList.add('liked');
                        } else {
                            btn.classList.remove('liked');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
</body>

</html>
