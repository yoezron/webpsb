<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <title><?= esc($title) ?></title>
    <meta name="description" content="Form Pendaftaran <?= esc($jalur_label) ?> - Pesantren Persatuan Islam 31 Banjaran">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('favicon.ico') ?>">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">

    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-green: #e8f5e9;
            --danger-red: #dc3545;
        }

        body {
            background: linear-gradient(135deg, var(--light-green) 0%, #fff 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 30px 0;
        }

        .form-container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .form-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .form-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .form-header p {
            font-size: 1.1rem;
            margin: 0;
            opacity: 0.9;
        }

        .form-body {
            padding: 40px 30px;
        }

        .step-indicator {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 40px;
            position: relative;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            flex: 1;
            max-width: 200px;
        }

        .step-number {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 10px;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .step-item.active .step-number {
            background: var(--primary-green);
            color: white;
            box-shadow: 0 4px 15px rgba(26, 179, 74, 0.4);
        }

        .step-item.completed .step-number {
            background: var(--dark-green);
            color: white;
        }

        .step-label {
            font-size: 0.9rem;
            color: #666;
            text-align: center;
        }

        .step-item.active .step-label {
            color: var(--primary-green);
            font-weight: 600;
        }

        .step-line {
            position: absolute;
            top: 25px;
            left: 50%;
            right: -50%;
            height: 3px;
            background: #e0e0e0;
            z-index: 1;
        }

        .step-item:last-child .step-line {
            display: none;
        }

        .step-item.completed .step-line {
            background: var(--primary-green);
        }

        .section-card {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .section-card.active {
            border-color: var(--primary-green);
            box-shadow: 0 5px 20px rgba(26, 179, 74, 0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--dark-green);
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--primary-green);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--primary-green);
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
        }

        .required::after {
            content: ' *';
            color: var(--danger-red);
        }

        .form-control, .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(26, 179, 74, 0.25);
        }

        .form-control.is-invalid, .form-select.is-invalid {
            border-color: var(--danger-red);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-red);
            font-size: 0.875rem;
            margin-top: 5px;
        }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(26, 179, 74, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(26, 179, 74, 0.4);
            color: white;
        }

        .btn-secondary-custom {
            background: #6c757d;
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-secondary-custom:hover {
            background: #5a6268;
            transform: translateY(-2px);
            color: white;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background: #f8d7da;
            color: #721c24;
        }

        .hidden {
            display: none;
        }

        @media (max-width: 768px) {
            .form-header h1 {
                font-size: 1.5rem;
            }

            .form-header p {
                font-size: 1rem;
            }

            .form-body {
                padding: 30px 20px;
            }

            .section-card {
                padding: 20px;
            }

            .step-indicator {
                flex-direction: column;
            }

            .step-line {
                display: none;
            }

            .step-item {
                margin-bottom: 20px;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-container">
            <!-- Header -->
            <div class="form-header">
                <h1><i class="icofont-graduate-alt"></i> Form Pendaftaran <?= esc($jalur_label) ?></h1>
                <p>Tahun Ajaran <?= esc($year) ?>/<?= esc($year + 1) ?></p>
            </div>

            <!-- Body -->
            <div class="form-body">
                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step-item active" id="step-1-indicator">
                        <div class="step-number">1</div>
                        <div class="step-label">Data Diri</div>
                        <div class="step-line"></div>
                    </div>
                    <div class="step-item" id="step-2-indicator">
                        <div class="step-number">2</div>
                        <div class="step-label">Data Tempat Tinggal</div>
                    </div>
                </div>

                <!-- Alert Messages -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="icofont-check-circled"></i> <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="icofont-warning"></i> <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Section 1: Data Diri -->
                <div class="section-card active" id="section-data-diri">
                    <h2 class="section-title">
                        <i class="icofont-ui-user"></i> Data Diri Calon Santri
                    </h2>

                    <form id="form-data-diri" method="post" action="<?= base_url('pendaftaran/' . strtolower($jalur_label)) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="step" value="data_diri">

                        <div class="row">
                            <!-- NISN -->
                            <div class="col-md-6 mb-3">
                                <label for="nisn" class="form-label">NISN</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nisn') ? 'is-invalid' : '' ?>"
                                    id="nisn" name="nisn"
                                    value="<?= old('nisn', $session_data['data_diri']['nisn'] ?? '') ?>"
                                    placeholder="Masukkan NISN">
                                <?php if (isset($validation) && $validation->hasError('nisn')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('nisn') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- NIK -->
                            <div class="col-md-6 mb-3">
                                <label for="nik" class="form-label">NIK</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nik') ? 'is-invalid' : '' ?>"
                                    id="nik" name="nik"
                                    value="<?= old('nik', $session_data['data_diri']['nik'] ?? '') ?>"
                                    placeholder="Masukkan NIK">
                                <?php if (isset($validation) && $validation->hasError('nik')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('nik') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Nama Lengkap -->
                            <div class="col-md-12 mb-3">
                                <label for="nama_lengkap" class="form-label required">Nama Lengkap</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nama_lengkap') ? 'is-invalid' : '' ?>"
                                    id="nama_lengkap" name="nama_lengkap"
                                    value="<?= old('nama_lengkap', $session_data['data_diri']['nama_lengkap'] ?? '') ?>"
                                    placeholder="Masukkan nama lengkap sesuai akta kelahiran" required>
                                <?php if (isset($validation) && $validation->hasError('nama_lengkap')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('nama_lengkap') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Jenis Kelamin -->
                            <div class="col-md-6 mb-3">
                                <label for="jenis_kelamin" class="form-label required">Jenis Kelamin</label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('jenis_kelamin') ? 'is-invalid' : '' ?>"
                                    id="jenis_kelamin" name="jenis_kelamin" required>
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" <?= old('jenis_kelamin', $session_data['data_diri']['jenis_kelamin'] ?? '') === 'L' ? 'selected' : '' ?>>Laki-laki</option>
                                    <option value="P" <?= old('jenis_kelamin', $session_data['data_diri']['jenis_kelamin'] ?? '') === 'P' ? 'selected' : '' ?>>Perempuan</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('jenis_kelamin')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('jenis_kelamin') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Tempat Lahir -->
                            <div class="col-md-6 mb-3">
                                <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('tempat_lahir') ? 'is-invalid' : '' ?>"
                                    id="tempat_lahir" name="tempat_lahir"
                                    value="<?= old('tempat_lahir', $session_data['data_diri']['tempat_lahir'] ?? '') ?>"
                                    placeholder="Masukkan tempat lahir">
                                <?php if (isset($validation) && $validation->hasError('tempat_lahir')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tempat_lahir') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Tanggal Lahir -->
                            <div class="col-md-6 mb-3">
                                <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                                <input type="date" class="form-control <?= isset($validation) && $validation->hasError('tanggal_lahir') ? 'is-invalid' : '' ?>"
                                    id="tanggal_lahir" name="tanggal_lahir"
                                    value="<?= old('tanggal_lahir', $session_data['data_diri']['tanggal_lahir'] ?? '') ?>">
                                <?php if (isset($validation) && $validation->hasError('tanggal_lahir')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('tanggal_lahir') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Status Keluarga -->
                            <div class="col-md-6 mb-3">
                                <label for="status_keluarga" class="form-label">Status dalam Keluarga</label>
                                <select class="form-select" id="status_keluarga" name="status_keluarga">
                                    <option value="">Pilih Status</option>
                                    <option value="Anak Kandung" <?= old('status_keluarga', $session_data['data_diri']['status_keluarga'] ?? '') === 'Anak Kandung' ? 'selected' : '' ?>>Anak Kandung</option>
                                    <option value="Anak Tiri" <?= old('status_keluarga', $session_data['data_diri']['status_keluarga'] ?? '') === 'Anak Tiri' ? 'selected' : '' ?>>Anak Tiri</option>
                                    <option value="Anak Angkat" <?= old('status_keluarga', $session_data['data_diri']['status_keluarga'] ?? '') === 'Anak Angkat' ? 'selected' : '' ?>>Anak Angkat</option>
                                </select>
                            </div>

                            <!-- Anak Ke -->
                            <div class="col-md-6 mb-3">
                                <label for="anak_ke" class="form-label">Anak Ke-</label>
                                <input type="number" class="form-control" id="anak_ke" name="anak_ke"
                                    value="<?= old('anak_ke', $session_data['data_diri']['anak_ke'] ?? '') ?>"
                                    placeholder="Masukkan urutan anak" min="1" max="20">
                            </div>

                            <!-- Jumlah Saudara -->
                            <div class="col-md-6 mb-3">
                                <label for="jumlah_saudara" class="form-label">Jumlah Saudara Kandung</label>
                                <input type="number" class="form-control" id="jumlah_saudara" name="jumlah_saudara"
                                    value="<?= old('jumlah_saudara', $session_data['data_diri']['jumlah_saudara'] ?? '') ?>"
                                    placeholder="Masukkan jumlah saudara" min="0" max="20">
                            </div>

                            <!-- Hobi -->
                            <div class="col-md-6 mb-3">
                                <label for="hobi" class="form-label">Hobi</label>
                                <input type="text" class="form-control" id="hobi" name="hobi"
                                    value="<?= old('hobi', $session_data['data_diri']['hobi'] ?? '') ?>"
                                    placeholder="Masukkan hobi">
                            </div>

                            <!-- Cita-cita -->
                            <div class="col-md-6 mb-3">
                                <label for="cita_cita" class="form-label">Cita-cita</label>
                                <input type="text" class="form-control" id="cita_cita" name="cita_cita"
                                    value="<?= old('cita_cita', $session_data['data_diri']['cita_cita'] ?? '') ?>"
                                    placeholder="Masukkan cita-cita">
                            </div>

                            <!-- Pendidikan Sebelumnya -->
                            <div class="col-md-12 mb-3">
                                <label class="form-label">Pendidikan Sebelumnya</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pernah_paud" name="pernah_paud" value="1"
                                        <?= old('pernah_paud', $session_data['data_diri']['pernah_paud'] ?? '') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="pernah_paud">
                                        Pernah PAUD
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="pernah_tk" name="pernah_tk" value="1"
                                        <?= old('pernah_tk', $session_data['data_diri']['pernah_tk'] ?? '') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="pernah_tk">
                                        Pernah TK
                                    </label>
                                </div>
                            </div>

                            <!-- Kebutuhan Disabilitas -->
                            <div class="col-md-6 mb-3">
                                <label for="kebutuhan_disabilitas" class="form-label">Kebutuhan Khusus/Disabilitas</label>
                                <input type="text" class="form-control" id="kebutuhan_disabilitas" name="kebutuhan_disabilitas"
                                    value="<?= old('kebutuhan_disabilitas', $session_data['data_diri']['kebutuhan_disabilitas'] ?? '') ?>"
                                    placeholder="Kosongkan jika tidak ada">
                            </div>

                            <!-- Imunisasi -->
                            <div class="col-md-6 mb-3">
                                <label for="imunisasi" class="form-label">Riwayat Imunisasi</label>
                                <input type="text" class="form-control" id="imunisasi" name="imunisasi"
                                    value="<?= old('imunisasi', $session_data['data_diri']['imunisasi'] ?? '') ?>"
                                    placeholder="Contoh: Lengkap, Tidak Lengkap">
                            </div>

                            <!-- No HP -->
                            <div class="col-md-6 mb-3">
                                <label for="no_hp" class="form-label">Nomor HP/WhatsApp</label>
                                <input type="text" class="form-control" id="no_hp" name="no_hp"
                                    value="<?= old('no_hp', $session_data['data_diri']['no_hp'] ?? '') ?>"
                                    placeholder="Contoh: 08123456789">
                            </div>

                            <!-- Ukuran Baju -->
                            <div class="col-md-6 mb-3">
                                <label for="ukuran_baju" class="form-label">Ukuran Baju</label>
                                <select class="form-select" id="ukuran_baju" name="ukuran_baju">
                                    <option value="">Pilih Ukuran</option>
                                    <option value="S" <?= old('ukuran_baju', $session_data['data_diri']['ukuran_baju'] ?? '') === 'S' ? 'selected' : '' ?>>S</option>
                                    <option value="M" <?= old('ukuran_baju', $session_data['data_diri']['ukuran_baju'] ?? '') === 'M' ? 'selected' : '' ?>>M</option>
                                    <option value="L" <?= old('ukuran_baju', $session_data['data_diri']['ukuran_baju'] ?? '') === 'L' ? 'selected' : '' ?>>L</option>
                                    <option value="XL" <?= old('ukuran_baju', $session_data['data_diri']['ukuran_baju'] ?? '') === 'XL' ? 'selected' : '' ?>>XL</option>
                                    <option value="XXL" <?= old('ukuran_baju', $session_data['data_diri']['ukuran_baju'] ?? '') === 'XXL' ? 'selected' : '' ?>>XXL</option>
                                </select>
                            </div>

                            <!-- Prestasi -->
                            <div class="col-md-12 mb-3">
                                <label for="prestasi" class="form-label">Prestasi yang Pernah Diraih</label>
                                <textarea class="form-control" id="prestasi" name="prestasi" rows="3"
                                    placeholder="Tuliskan prestasi yang pernah diraih (jika ada)"><?= old('prestasi', $session_data['data_diri']['prestasi'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="<?= base_url('/') ?>" class="btn btn-secondary-custom">
                                <i class="icofont-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary-custom">
                                Simpan & Lanjutkan <i class="icofont-arrow-right"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Section 2: Data Alamat -->
                <div class="section-card <?= !empty($session_data['data_diri']) ? 'active' : 'hidden' ?>" id="section-data-alamat">
                    <h2 class="section-title">
                        <i class="icofont-location-pin"></i> Data Tempat Tinggal
                    </h2>

                    <form id="form-data-alamat" method="post" action="<?= base_url('pendaftaran/' . strtolower($jalur_label)) ?>">
                        <?= csrf_field() ?>
                        <input type="hidden" name="step" value="data_alamat">

                        <div class="row">
                            <!-- Nomor KK -->
                            <div class="col-md-6 mb-3">
                                <label for="nomor_kk" class="form-label">Nomor Kartu Keluarga (KK)</label>
                                <input type="text" class="form-control <?= isset($validation) && $validation->hasError('nomor_kk') ? 'is-invalid' : '' ?>"
                                    id="nomor_kk" name="nomor_kk"
                                    value="<?= old('nomor_kk', $session_data['data_alamat']['nomor_kk'] ?? '') ?>"
                                    placeholder="Masukkan nomor KK">
                                <?php if (isset($validation) && $validation->hasError('nomor_kk')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('nomor_kk') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Jenis Tempat Tinggal -->
                            <div class="col-md-6 mb-3">
                                <label for="jenis_tempat_tinggal" class="form-label">Jenis Tempat Tinggal</label>
                                <select class="form-select" id="jenis_tempat_tinggal" name="jenis_tempat_tinggal">
                                    <option value="">Pilih Jenis</option>
                                    <option value="Milik Sendiri" <?= old('jenis_tempat_tinggal', $session_data['data_alamat']['jenis_tempat_tinggal'] ?? '') === 'Milik Sendiri' ? 'selected' : '' ?>>Milik Sendiri</option>
                                    <option value="Rumah Orang Tua" <?= old('jenis_tempat_tinggal', $session_data['data_alamat']['jenis_tempat_tinggal'] ?? '') === 'Rumah Orang Tua' ? 'selected' : '' ?>>Rumah Orang Tua</option>
                                    <option value="Rumah Saudara" <?= old('jenis_tempat_tinggal', $session_data['data_alamat']['jenis_tempat_tinggal'] ?? '') === 'Rumah Saudara' ? 'selected' : '' ?>>Rumah Saudara</option>
                                    <option value="Rumah Dinas" <?= old('jenis_tempat_tinggal', $session_data['data_alamat']['jenis_tempat_tinggal'] ?? '') === 'Rumah Dinas' ? 'selected' : '' ?>>Rumah Dinas</option>
                                    <option value="Sewa/Kontrak" <?= old('jenis_tempat_tinggal', $session_data['data_alamat']['jenis_tempat_tinggal'] ?? '') === 'Sewa/Kontrak' ? 'selected' : '' ?>>Sewa/Kontrak</option>
                                    <option value="Lainnya" <?= old('jenis_tempat_tinggal', $session_data['data_alamat']['jenis_tempat_tinggal'] ?? '') === 'Lainnya' ? 'selected' : '' ?>>Lainnya</option>
                                </select>
                            </div>

                            <!-- Alamat -->
                            <div class="col-md-12 mb-3">
                                <label for="alamat" class="form-label">Alamat Lengkap</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                    placeholder="Masukkan alamat lengkap (Jalan, RT/RW, Nomor Rumah)"><?= old('alamat', $session_data['data_alamat']['alamat'] ?? '') ?></textarea>
                            </div>

                            <!-- Desa -->
                            <div class="col-md-6 mb-3">
                                <label for="desa" class="form-label">Desa/Kelurahan</label>
                                <input type="text" class="form-control" id="desa" name="desa"
                                    value="<?= old('desa', $session_data['data_alamat']['desa'] ?? '') ?>"
                                    placeholder="Masukkan desa/kelurahan">
                            </div>

                            <!-- Kecamatan -->
                            <div class="col-md-6 mb-3">
                                <label for="kecamatan" class="form-label">Kecamatan</label>
                                <input type="text" class="form-control" id="kecamatan" name="kecamatan"
                                    value="<?= old('kecamatan', $session_data['data_alamat']['kecamatan'] ?? '') ?>"
                                    placeholder="Masukkan kecamatan">
                            </div>

                            <!-- Kabupaten -->
                            <div class="col-md-6 mb-3">
                                <label for="kabupaten" class="form-label">Kabupaten/Kota</label>
                                <input type="text" class="form-control" id="kabupaten" name="kabupaten"
                                    value="<?= old('kabupaten', $session_data['data_alamat']['kabupaten'] ?? '') ?>"
                                    placeholder="Masukkan kabupaten/kota">
                            </div>

                            <!-- Provinsi -->
                            <div class="col-md-6 mb-3">
                                <label for="provinsi" class="form-label">Provinsi</label>
                                <input type="text" class="form-control" id="provinsi" name="provinsi"
                                    value="<?= old('provinsi', $session_data['data_alamat']['provinsi'] ?? '') ?>"
                                    placeholder="Masukkan provinsi">
                            </div>

                            <!-- Kode Pos -->
                            <div class="col-md-6 mb-3">
                                <label for="kode_pos" class="form-label">Kode Pos</label>
                                <input type="text" class="form-control" id="kode_pos" name="kode_pos"
                                    value="<?= old('kode_pos', $session_data['data_alamat']['kode_pos'] ?? '') ?>"
                                    placeholder="Masukkan kode pos">
                            </div>

                            <!-- Jarak ke Sekolah -->
                            <div class="col-md-6 mb-3">
                                <label for="jarak_ke_sekolah" class="form-label">Jarak ke Sekolah</label>
                                <select class="form-select" id="jarak_ke_sekolah" name="jarak_ke_sekolah">
                                    <option value="">Pilih Jarak</option>
                                    <option value="Kurang dari 5 km" <?= old('jarak_ke_sekolah', $session_data['data_alamat']['jarak_ke_sekolah'] ?? '') === 'Kurang dari 5 km' ? 'selected' : '' ?>>Kurang dari 5 km</option>
                                    <option value="5-10 km" <?= old('jarak_ke_sekolah', $session_data['data_alamat']['jarak_ke_sekolah'] ?? '') === '5-10 km' ? 'selected' : '' ?>>5-10 km</option>
                                    <option value="10-20 km" <?= old('jarak_ke_sekolah', $session_data['data_alamat']['jarak_ke_sekolah'] ?? '') === '10-20 km' ? 'selected' : '' ?>>10-20 km</option>
                                    <option value="Lebih dari 20 km" <?= old('jarak_ke_sekolah', $session_data['data_alamat']['jarak_ke_sekolah'] ?? '') === 'Lebih dari 20 km' ? 'selected' : '' ?>>Lebih dari 20 km</option>
                                </select>
                            </div>

                            <!-- Waktu Tempuh -->
                            <div class="col-md-6 mb-3">
                                <label for="waktu_tempuh" class="form-label">Waktu Tempuh ke Sekolah</label>
                                <input type="text" class="form-control" id="waktu_tempuh" name="waktu_tempuh"
                                    value="<?= old('waktu_tempuh', $session_data['data_alamat']['waktu_tempuh'] ?? '') ?>"
                                    placeholder="Contoh: 30 menit">
                            </div>

                            <!-- Transportasi -->
                            <div class="col-md-6 mb-3">
                                <label for="transportasi" class="form-label">Moda Transportasi</label>
                                <input type="text" class="form-control" id="transportasi" name="transportasi"
                                    value="<?= old('transportasi', $session_data['data_alamat']['transportasi'] ?? '') ?>"
                                    placeholder="Contoh: Motor, Mobil, Angkutan Umum">
                            </div>

                            <!-- Email -->
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control <?= isset($validation) && $validation->hasError('email') ? 'is-invalid' : '' ?>"
                                    id="email" name="email"
                                    value="<?= old('email', $session_data['data_alamat']['email'] ?? '') ?>"
                                    placeholder="Masukkan email">
                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                    <div class="invalid-feedback"><?= $validation->getError('email') ?></div>
                                <?php endif; ?>
                            </div>

                            <!-- Media Sosial -->
                            <div class="col-md-6 mb-3">
                                <label for="media_sosial" class="form-label">Media Sosial</label>
                                <input type="text" class="form-control" id="media_sosial" name="media_sosial"
                                    value="<?= old('media_sosial', $session_data['data_alamat']['media_sosial'] ?? '') ?>"
                                    placeholder="Contoh: @username_instagram">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-secondary-custom" id="btn-back-to-step-1">
                                <i class="icofont-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" class="btn btn-primary-custom">
                                <i class="icofont-check-circled"></i> Simpan & Submit Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            // Check if data_diri is saved in session
            var dataDiriSaved = <?= !empty($session_data['data_diri']) ? 'true' : 'false' ?>;

            if (dataDiriSaved) {
                // Show section 2
                $('#section-data-diri').removeClass('active').addClass('hidden');
                $('#section-data-alamat').removeClass('hidden').addClass('active');

                // Update step indicator
                $('#step-1-indicator').addClass('completed').removeClass('active');
                $('#step-2-indicator').addClass('active');
            }

            // Back to step 1
            $('#btn-back-to-step-1').click(function() {
                $('#section-data-alamat').removeClass('active').addClass('hidden');
                $('#section-data-diri').removeClass('hidden').addClass('active');

                $('#step-2-indicator').removeClass('active');
                $('#step-1-indicator').removeClass('completed').addClass('active');

                // Scroll to top
                $('html, body').animate({ scrollTop: 0 }, 500);
            });

            // Client-side validation for Data Diri
            $('#form-data-diri').submit(function(e) {
                var namaLengkap = $('#nama_lengkap').val().trim();
                var jenisKelamin = $('#jenis_kelamin').val();

                if (namaLengkap === '' || namaLengkap.length < 3) {
                    e.preventDefault();
                    alert('Nama lengkap harus diisi minimal 3 karakter!');
                    $('#nama_lengkap').focus();
                    return false;
                }

                if (jenisKelamin === '') {
                    e.preventDefault();
                    alert('Jenis kelamin harus dipilih!');
                    $('#jenis_kelamin').focus();
                    return false;
                }

                return true;
            });

            // Numeric validation for NISN, NIK, No KK, No HP
            $('#nisn, #nik, #nomor_kk, #no_hp, #kode_pos').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Number validation for anak_ke and jumlah_saudara
            $('#anak_ke, #jumlah_saudara').on('input', function() {
                var val = parseInt(this.value);
                if (val < 0) this.value = '';
                if (val > 20) this.value = 20;
            });

            // Auto dismiss alerts after 5 seconds
            setTimeout(function() {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>
