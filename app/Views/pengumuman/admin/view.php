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

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .announcement-image {
            max-width: 100%;
            max-height: 400px;
            border-radius: 10px;
            object-fit: cover;
        }

        .reply-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary-green);
        }

        .reply-card.admin-reply {
            background: #e8f5e9;
            border-left-color: var(--dark-green);
            margin-left: 30px;
        }

        .reply-card.pending {
            opacity: 0.7;
            border-left-color: #ffc107;
        }

        .badge-admin {
            background: var(--primary-green);
            color: white;
        }

        .badge-pending {
            background: #ffc107;
            color: #333;
        }

        .btn-custom-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
        }

        .btn-custom-primary:hover {
            background: var(--dark-green);
            color: white;
        }

        .like-count {
            color: #e74c3c;
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
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('/admin/pengumuman') ?>">Pengumuman</a></li>
                <li class="breadcrumb-item active">Detail</li>
            </ol>
        </nav>

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

        <!-- Announcement Content -->
        <div class="content-card">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h3><?= esc($announcement['judul']) ?></h3>
                    <p class="text-muted mb-0">
                        <i class="icofont-user-alt-4 me-1"></i> <?= esc($announcement['admin_nama']) ?>
                        <span class="mx-2">|</span>
                        <i class="icofont-calendar me-1"></i> <?= date('d M Y H:i', strtotime($announcement['created_at'])) ?>
                        <span class="mx-2">|</span>
                        <?php if ($announcement['is_active']) : ?>
                            <span class="badge bg-success">Aktif</span>
                        <?php else : ?>
                            <span class="badge bg-secondary">Nonaktif</span>
                        <?php endif; ?>
                    </p>
                </div>
                <div>
                    <a href="<?= base_url('/admin/pengumuman/edit/' . $announcement['id_pengumuman']) ?>" class="btn btn-warning btn-sm">
                        <i class="icofont-edit me-1"></i> Edit
                    </a>
                </div>
            </div>

            <?php if ($announcement['gambar']) : ?>
                <div class="mb-4">
                    <img src="<?= base_url('uploads/pengumuman/' . $announcement['gambar']) ?>" class="announcement-image" alt="Gambar pengumuman">
                </div>
            <?php endif; ?>

            <div class="announcement-content">
                <?= nl2br(esc($announcement['konten'])) ?>
            </div>

            <hr>

            <div class="d-flex gap-3">
                <span class="like-count">
                    <i class="icofont-heart"></i> <?= $likes_count ?> Likes
                </span>
                <span class="text-muted">
                    <i class="icofont-comment"></i> <?= count($replies) ?> Balasan
                </span>
            </div>
        </div>

        <!-- Replies Section -->
        <div class="content-card">
            <h5 class="mb-4"><i class="icofont-comment me-2"></i>Balasan & Pertanyaan</h5>

            <?php if (empty($replies)) : ?>
                <p class="text-muted text-center py-4">
                    <i class="icofont-comment fs-1 d-block mb-2"></i>
                    Belum ada balasan atau pertanyaan
                </p>
            <?php else : ?>
                <?php foreach ($replies as $reply) : ?>
                    <!-- Public Reply -->
                    <div class="reply-card <?= !$reply['is_approved'] ? 'pending' : '' ?>">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong><?= esc($reply['nama_pengirim']) ?></strong>
                                <?php if ($reply['email_pengirim']) : ?>
                                    <small class="text-muted">(<?= esc($reply['email_pengirim']) ?>)</small>
                                <?php endif; ?>
                                <?php if (!$reply['is_approved']) : ?>
                                    <span class="badge badge-pending ms-2">Menunggu Persetujuan</span>
                                <?php endif; ?>
                            </div>
                            <small class="text-muted"><?= date('d M Y H:i', strtotime($reply['created_at'])) ?></small>
                        </div>
                        <p class="mt-2 mb-2"><?= nl2br(esc($reply['isi_balasan'])) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="like-count small">
                                <i class="icofont-heart"></i> <?= $reply['likes_count'] ?> likes
                            </span>
                            <div class="btn-group btn-group-sm">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#replyForm<?= $reply['id_balasan'] ?>">
                                    <i class="icofont-reply me-1"></i> Balas
                                </button>
                                <a href="<?= base_url('/admin/pengumuman/toggle-approval/' . $reply['id_balasan']) ?>" class="btn btn-outline-<?= $reply['is_approved'] ? 'warning' : 'success' ?>">
                                    <i class="icofont-<?= $reply['is_approved'] ? 'eye-blocked' : 'check' ?> me-1"></i>
                                    <?= $reply['is_approved'] ? 'Sembunyikan' : 'Setujui' ?>
                                </a>
                                <button type="button" class="btn btn-outline-danger" onclick="confirmDeleteReply(<?= $reply['id_balasan'] ?>)">
                                    <i class="icofont-trash"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Reply Form -->
                        <div class="collapse mt-3" id="replyForm<?= $reply['id_balasan'] ?>">
                            <form action="<?= base_url('/admin/pengumuman/reply/' . $reply['id_balasan']) ?>" method="POST">
                                <?= csrf_field() ?>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="isi_balasan" placeholder="Tulis balasan..." required>
                                    <button type="submit" class="btn btn-custom-primary">
                                        <i class="icofont-paper-plane"></i> Kirim
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Admin Replies -->
                    <?php if (!empty($reply['admin_replies'])) : ?>
                        <?php foreach ($reply['admin_replies'] as $adminReply) : ?>
                            <div class="reply-card admin-reply">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <strong><?= esc($adminReply['nama_pengirim']) ?></strong>
                                        <span class="badge badge-admin ms-2">Admin</span>
                                    </div>
                                    <small class="text-muted"><?= date('d M Y H:i', strtotime($adminReply['created_at'])) ?></small>
                                </div>
                                <p class="mt-2 mb-2"><?= nl2br(esc($adminReply['isi_balasan'])) ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="like-count small">
                                        <i class="icofont-heart"></i> <?= $adminReply['likes_count'] ?> likes
                                    </span>
                                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="confirmDeleteReply(<?= $adminReply['id_balasan'] ?>)">
                                        <i class="icofont-trash"></i> Hapus
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <a href="<?= base_url('/admin/pengumuman') ?>" class="btn btn-secondary">
            <i class="icofont-arrow-left me-2"></i>Kembali ke Daftar
        </a>
    </div>

    <!-- Delete Reply Modal -->
    <div class="modal fade" id="deleteReplyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus balasan ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <a href="#" id="deleteReplyLink" class="btn btn-danger">Hapus</a>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        function confirmDeleteReply(id) {
            document.getElementById('deleteReplyLink').href = '<?= base_url('/admin/pengumuman/delete-reply/') ?>' + id;
            new bootstrap.Modal(document.getElementById('deleteReplyModal')).show();
        }
    </script>
</body>

</html>
