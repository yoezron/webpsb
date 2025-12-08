<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?></title>
    <meta name="description" content="<?= esc(substr(strip_tags($announcement['konten']), 0, 160)) ?>">
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

        .content-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }

        .announcement-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .announcement-title {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 15px;
        }

        .announcement-meta {
            color: #666;
            margin-bottom: 20px;
        }

        .announcement-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #444;
        }

        .like-section {
            display: flex;
            gap: 20px;
            align-items: center;
            padding: 20px 0;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }

        .like-btn {
            background: none;
            border: 2px solid #e74c3c;
            color: #e74c3c;
            padding: 10px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .like-btn:hover,
        .like-btn.liked {
            background: #e74c3c;
            color: white;
        }

        .reply-section {
            margin-top: 30px;
        }

        .reply-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid var(--primary-green);
        }

        .reply-card.admin-reply {
            background: #e8f5e9;
            border-left-color: var(--dark-green);
            margin-left: 40px;
        }

        .reply-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .reply-author {
            font-weight: 600;
            color: #333;
        }

        .badge-admin {
            background: var(--primary-green);
            color: white;
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 0.75rem;
        }

        .reply-date {
            color: #999;
            font-size: 0.85rem;
        }

        .reply-content {
            color: #555;
            line-height: 1.6;
        }

        .reply-footer {
            margin-top: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .reply-like-btn {
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .reply-like-btn:hover,
        .reply-like-btn.liked {
            color: #e74c3c;
        }

        .reply-form {
            background: #f8f9fa;
            padding: 25px;
            border-radius: 10px;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
        }

        .btn-submit:hover {
            background: var(--dark-green);
            color: white;
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

        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
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

    <!-- Toast Container -->
    <div class="toast-container"></div>

    <div class="container py-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= base_url('/') ?>">Beranda</a></li>
                <li class="breadcrumb-item"><a href="<?= base_url('/pengumuman') ?>">Pengumuman</a></li>
                <li class="breadcrumb-item active"><?= esc(substr($announcement['judul'], 0, 30)) ?>...</li>
            </ol>
        </nav>

        <!-- Announcement Content -->
        <div class="content-card">
            <?php if ($announcement['gambar']) : ?>
                <img src="<?= base_url('uploads/pengumuman/' . $announcement['gambar']) ?>" class="announcement-image" alt="<?= esc($announcement['judul']) ?>">
            <?php endif; ?>

            <h1 class="announcement-title"><?= esc($announcement['judul']) ?></h1>

            <div class="announcement-meta">
                <i class="icofont-user-alt-4 me-1"></i> <?= esc($announcement['admin_nama']) ?>
                <span class="mx-2">|</span>
                <i class="icofont-calendar me-1"></i> <?= date('d M Y, H:i', strtotime($announcement['created_at'])) ?>
            </div>

            <div class="announcement-content">
                <?= nl2br(esc($announcement['konten'])) ?>
            </div>

            <div class="like-section">
                <button type="button" class="like-btn <?= $user_liked ? 'liked' : '' ?>" id="mainLikeBtn" onclick="toggleMainLike()">
                    <i class="icofont-heart"></i>
                    <span id="mainLikeCount"><?= $likes_count ?></span> Mengerti
                </button>
                <span class="text-muted">
                    <i class="icofont-comment me-1"></i>
                    <?= count($replies) ?> Pertanyaan/Balasan
                </span>
            </div>
        </div>

        <!-- Reply Form -->
        <div class="content-card">
            <h5 class="mb-4"><i class="icofont-comment me-2"></i>Ajukan Pertanyaan atau Berikan Tanggapan</h5>

            <form id="replyForm" class="reply-form">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_pengirim" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="nama_pengirim" name="nama_pengirim" required placeholder="Masukkan nama Anda">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email_pengirim" class="form-label">Email (Opsional)</label>
                        <input type="email" class="form-control" id="email_pengirim" name="email_pengirim" placeholder="email@contoh.com">
                    </div>
                </div>
                <div class="mb-3">
                    <label for="isi_balasan" class="form-label">Pertanyaan/Tanggapan <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="isi_balasan" name="isi_balasan" rows="4" required placeholder="Tulis pertanyaan atau tanggapan Anda..."></textarea>
                </div>
                <button type="submit" class="btn btn-submit">
                    <i class="icofont-paper-plane me-2"></i>Kirim
                </button>
            </form>
        </div>

        <!-- Replies Section -->
        <div class="content-card reply-section">
            <h5 class="mb-4"><i class="icofont-chat me-2"></i>Pertanyaan & Jawaban</h5>

            <div id="repliesContainer">
                <?php if (empty($replies)) : ?>
                    <p class="text-muted text-center py-4" id="noRepliesMsg">
                        <i class="icofont-chat fs-1 d-block mb-2"></i>
                        Belum ada pertanyaan atau tanggapan. Jadilah yang pertama bertanya!
                    </p>
                <?php else : ?>
                    <?php foreach ($replies as $reply) : ?>
                        <!-- Public Reply -->
                        <div class="reply-card">
                            <div class="reply-header">
                                <span class="reply-author">
                                    <i class="icofont-user me-1"></i>
                                    <?= esc($reply['nama_pengirim']) ?>
                                </span>
                                <span class="reply-date"><?= date('d M Y, H:i', strtotime($reply['created_at'])) ?></span>
                            </div>
                            <p class="reply-content"><?= nl2br(esc($reply['isi_balasan'])) ?></p>
                            <div class="reply-footer">
                                <button type="button" class="reply-like-btn <?= $reply['user_liked'] ? 'liked' : '' ?>" onclick="toggleReplyLike(<?= $reply['id_balasan'] ?>, this)">
                                    <i class="icofont-heart"></i>
                                    <span><?= $reply['likes_count'] ?></span>
                                </button>
                            </div>
                        </div>

                        <!-- Admin Replies -->
                        <?php if (!empty($reply['admin_replies'])) : ?>
                            <?php foreach ($reply['admin_replies'] as $adminReply) : ?>
                                <div class="reply-card admin-reply">
                                    <div class="reply-header">
                                        <span class="reply-author">
                                            <i class="icofont-user-suited me-1"></i>
                                            <?= esc($adminReply['nama_pengirim']) ?>
                                            <span class="badge-admin ms-2">Admin</span>
                                        </span>
                                        <span class="reply-date"><?= date('d M Y, H:i', strtotime($adminReply['created_at'])) ?></span>
                                    </div>
                                    <p class="reply-content"><?= nl2br(esc($adminReply['isi_balasan'])) ?></p>
                                    <div class="reply-footer">
                                        <button type="button" class="reply-like-btn <?= $adminReply['user_liked'] ? 'liked' : '' ?>" onclick="toggleReplyLike(<?= $adminReply['id_balasan'] ?>, this)">
                                            <i class="icofont-heart"></i>
                                            <span><?= $adminReply['likes_count'] ?></span>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <a href="<?= base_url('/pengumuman') ?>" class="btn btn-secondary mt-3">
            <i class="icofont-arrow-left me-2"></i>Kembali ke Daftar Pengumuman
        </a>
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
        const announcementId = <?= $announcement['id_pengumuman'] ?>;
        const csrfName = '<?= csrf_token() ?>';
        let csrfHash = '<?= csrf_hash() ?>';

        // Update CSRF token from response
        function updateCsrfToken(newToken) {
            if (newToken) {
                csrfHash = newToken;
            }
        }

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastHtml = `
                <div class="toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="icofont-${type === 'success' ? 'check-circled' : 'warning'} me-2"></i>
                            ${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            const container = document.querySelector('.toast-container');
            container.insertAdjacentHTML('beforeend', toastHtml);
            const toastEl = container.lastElementChild;
            const toast = new bootstrap.Toast(toastEl);
            toast.show();
            toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
        }

        // Toggle main announcement like
        function toggleMainLike() {
            const formData = new URLSearchParams();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('/api/pengumuman/like/') ?>' + announcementId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    updateCsrfToken(data.csrf_token);
                    if (data.success) {
                        document.getElementById('mainLikeCount').textContent = data.count;
                        const btn = document.getElementById('mainLikeBtn');
                        if (data.action === 'liked') {
                            btn.classList.add('liked');
                        } else {
                            btn.classList.remove('liked');
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }

        // Toggle reply like
        function toggleReplyLike(replyId, btn) {
            const formData = new URLSearchParams();
            formData.append(csrfName, csrfHash);

            fetch('<?= base_url('/api/pengumuman/like-reply/') ?>' + replyId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    updateCsrfToken(data.csrf_token);
                    if (data.success) {
                        const countEl = btn.querySelector('span');
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

        // Submit reply form
        document.getElementById('replyForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new URLSearchParams();
            formData.append(csrfName, csrfHash);
            formData.append('nama_pengirim', document.getElementById('nama_pengirim').value);
            formData.append('email_pengirim', document.getElementById('email_pengirim').value);
            formData.append('isi_balasan', document.getElementById('isi_balasan').value);

            fetch('<?= base_url('/api/pengumuman/reply/') ?>' + announcementId, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    updateCsrfToken(data.csrf_token);
                    if (data.success) {
                        showToast(data.message, 'success');
                        // Clear form
                        document.getElementById('replyForm').reset();
                        // Reload page to show new reply
                        setTimeout(() => location.reload(), 1500);
                    } else {
                        showToast(data.message || 'Terjadi kesalahan', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Terjadi kesalahan, silakan coba lagi', 'danger');
                });
        });
    </script>
</body>

</html>
