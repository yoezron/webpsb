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

        .form-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .form-label {
            font-weight: 600;
            color: #333;
        }

        .btn-custom-primary {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
        }

        .btn-custom-primary:hover {
            background: var(--dark-green);
            color: white;
        }

        .current-image {
            max-width: 300px;
            max-height: 200px;
            border-radius: 10px;
            object-fit: cover;
        }

        .preview-image {
            max-width: 300px;
            max-height: 200px;
            border-radius: 10px;
            object-fit: cover;
            margin-top: 10px;
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
                <li class="breadcrumb-item active">Edit</li>
            </ol>
        </nav>

        <!-- Flash Messages -->
        <?php if (session()->getFlashdata('errors')) : ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="form-card">
            <h4 class="mb-4"><i class="icofont-edit me-2"></i>Edit Pengumuman</h4>

            <form action="<?= base_url('/admin/pengumuman/update/' . $announcement['id_pengumuman']) ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="mb-4">
                    <label for="judul" class="form-label">Judul Pengumuman <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg" id="judul" name="judul" value="<?= old('judul', $announcement['judul']) ?>" required>
                </div>

                <div class="mb-4">
                    <label for="konten" class="form-label">Konten Pengumuman <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="konten" name="konten" rows="10" required><?= old('konten', $announcement['konten']) ?></textarea>
                </div>

                <div class="mb-4">
                    <label class="form-label">Gambar</label>

                    <?php if ($announcement['gambar']) : ?>
                        <div class="mb-3">
                            <p class="text-muted mb-2">Gambar saat ini:</p>
                            <img src="<?= base_url('uploads/pengumuman/' . $announcement['gambar']) ?>" class="current-image" alt="Current image">
                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image" value="1">
                                <label class="form-check-label text-danger" for="remove_image">
                                    Hapus gambar ini
                                </label>
                            </div>
                        </div>
                    <?php endif; ?>

                    <input type="file" class="form-control" id="gambar" name="gambar" accept="image/jpeg,image/png,image/gif,image/webp" onchange="previewImage(this)">
                    <small class="text-muted">Format: JPG, PNG, GIF, WebP. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    <img id="imagePreview" class="preview-image d-none" alt="Preview">
                </div>

                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" <?= $announcement['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">Aktifkan pengumuman (tampilkan ke publik)</label>
                    </div>
                </div>

                <hr>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-custom-primary">
                        <i class="icofont-check me-2"></i>Update Pengumuman
                    </button>
                    <a href="<?= base_url('/admin/pengumuman') ?>" class="btn btn-secondary">
                        <i class="icofont-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script>
        function previewImage(input) {
            const preview = document.getElementById('imagePreview');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('d-none');
            }
        }
    </script>
</body>

</html>
