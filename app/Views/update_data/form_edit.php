<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">

    <title><?= esc($title) ?></title>
    <meta name="description" content="Edit Data Pendaftar <?= esc($jalur_label) ?> - Pesantren Persatuan Islam 31 Banjaran">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/logo/favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/logo/favicon-32x32.png') ?>">
    <link rel="apple-touch-icon" href="<?= base_url('assets/images/logo/apple-touch-icon.png') ?>">

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
            --info-blue: #17a2b8;
            --warning-orange: #fd7e14;
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
            background: linear-gradient(135deg, var(--warning-orange) 0%, #e65c00 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }

        .form-header-logo {
            width: 150px;
            height: auto;
            margin: 0 auto 20px;
        }

        .form-header-logo img {
            width: 100%;
            height: auto;
            display: block;
            object-fit: contain;
        }

        .form-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0 0 10px 0;
        }

        .form-header p {
            font-size: 1rem;
            margin: 0;
            opacity: 0.95;
        }

        .registration-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 8px 20px;
            border-radius: 50px;
            margin-top: 15px;
            font-weight: 600;
        }

        .form-body {
            padding: 40px 30px;
        }

        /* Progress Bar */
        .progress-bar-container {
            background: #e0e0e0;
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 15px;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--warning-orange), #e65c00);
            border-radius: 10px;
            transition: width 0.5s ease;
        }

        .progress-percentage {
            text-align: center;
            font-size: 0.9rem;
            color: var(--warning-orange);
            font-weight: 700;
            margin-bottom: 20px;
        }

        .step-indicator {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .step-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            position: relative;
            min-width: 80px;
        }

        .step-number {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #e0e0e0;
            color: #999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 5px;
            transition: all 0.3s ease;
        }

        .step-item.active .step-number {
            background: var(--warning-orange);
            color: white;
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.4);
            transform: scale(1.1);
        }

        .step-item.completed .step-number {
            background: #e65c00;
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            color: #666;
            text-align: center;
            line-height: 1.2;
        }

        .step-item.active .step-label {
            color: var(--warning-orange);
            font-weight: 600;
        }

        .section-card {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .section-card.active {
            border-color: var(--warning-orange);
            box-shadow: 0 5px 20px rgba(253, 126, 20, 0.1);
        }

        .section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #e65c00;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid var(--warning-orange);
            display: flex;
            align-items: center;
        }

        .section-title i {
            margin-right: 10px;
            color: var(--warning-orange);
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

        .form-control,
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--warning-orange);
            box-shadow: 0 0 0 0.2rem rgba(253, 126, 20, 0.25);
        }

        .form-control.is-invalid,
        .form-select.is-invalid {
            border-color: var(--danger-red);
        }

        .invalid-feedback {
            display: block;
            color: var(--danger-red);
            font-size: 0.875rem;
            margin-top: 5px;
        }

        .form-check-input:checked {
            background-color: var(--warning-orange);
            border-color: var(--warning-orange);
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--warning-orange) 0%, #e65c00 100%);
            border: none;
            color: white;
            padding: 15px 40px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(253, 126, 20, 0.3);
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(253, 126, 20, 0.4);
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

        .btn-danger-custom {
            background: var(--danger-red);
            border: none;
            color: white;
            padding: 12px 25px;
            font-size: 0.95rem;
            font-weight: 600;
            border-radius: 50px;
            transition: all 0.3s ease;
        }

        .btn-danger-custom:hover {
            background: #c82333;
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

        .alert-warning {
            background: #fff3cd;
            color: #856404;
        }

        .hidden {
            display: none !important;
        }

        .review-table {
            width: 100%;
            border-collapse: collapse;
        }

        .review-table th {
            background: #fff3cd;
            padding: 12px;
            text-align: left;
            font-weight: 700;
            color: #856404;
            border-bottom: 2px solid var(--warning-orange);
        }

        .review-table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e0e0e0;
        }

        .review-table tr:last-child td {
            border-bottom: none;
        }

        .review-section {
            margin-bottom: 30px;
        }

        .review-section-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #e65c00;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--warning-orange);
        }

        .cancel-link {
            text-align: center;
            margin-top: 20px;
        }

        .cancel-link a {
            color: var(--danger-red);
            text-decoration: none;
        }

        .cancel-link a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .form-header h1 {
                font-size: 1.5rem;
            }

            .form-header p {
                font-size: 0.9rem;
            }

            .form-body {
                padding: 30px 20px;
            }

            .section-card {
                padding: 20px;
            }

            .step-indicator {
                justify-content: flex-start;
            }

            .step-item {
                min-width: 60px;
            }

            .step-number {
                width: 35px;
                height: 35px;
                font-size: 0.9rem;
            }

            .step-label {
                font-size: 0.7rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-container">
            <!-- Header -->
            <div class="form-header">
                <div class="form-header-logo">
                    <img src="<?= base_url('assets/images/logo/01.png') ?>" alt="Logo Pesantren Persatuan Islam 31 Banjaran">
                </div>
                <h1><i class="icofont-edit"></i> Edit Data Pendaftar</h1>
                <p>Pesantren Persatuan Islam 31 Banjaran</p>
                <p>Tahun Ajaran <?= esc($year) ?>/<?= esc($year + 1) ?></p>
                <div class="registration-badge">
                    <i class="icofont-id-card"></i> <?= esc($pendaftar['nomor_pendaftaran']) ?> - <?= esc($jalur_label) ?>
                </div>
            </div>

            <!-- Body -->
            <div class="form-body">
                <!-- Progress Bar -->
                <div class="progress-bar-container" role="progressbar" aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar-fill" id="progress-bar" style="width: 12.5%;"></div>
                </div>
                <div class="progress-percentage" id="progress-percentage">12.5% - Step 1 of 8</div>

                <!-- Step Indicator -->
                <div class="step-indicator">
                    <div class="step-item active" data-step="1">
                        <div class="step-number">1</div>
                        <div class="step-label">Data Diri</div>
                    </div>
                    <div class="step-item" data-step="2">
                        <div class="step-number">2</div>
                        <div class="step-label">Alamat</div>
                    </div>
                    <div class="step-item" data-step="3">
                        <div class="step-number">3</div>
                        <div class="step-label">Data Ayah</div>
                    </div>
                    <div class="step-item" data-step="4">
                        <div class="step-number">4</div>
                        <div class="step-label">Data Ibu</div>
                    </div>
                    <div class="step-item" data-step="5">
                        <div class="step-number">5</div>
                        <div class="step-label">Data Wali</div>
                    </div>
                    <div class="step-item" data-step="6">
                        <div class="step-number">6</div>
                        <div class="step-label">Bansos</div>
                    </div>
                    <div class="step-item" data-step="7">
                        <div class="step-number">7</div>
                        <div class="step-label">Asal Sekolah</div>
                    </div>
                    <div class="step-item" data-step="8">
                        <div class="step-number">8</div>
                        <div class="step-label">Review</div>
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
                        <i class="icofont-warning"></i> <strong><?= session()->getFlashdata('error') ?></strong>

                        <?php if (session()->getFlashdata('validation_errors')): ?>
                            <hr style="margin: 10px 0; border-color: rgba(114, 28, 36, 0.3);">
                            <small><strong>Detail Error:</strong></small>
                            <ul style="margin: 5px 0 0 0; padding-left: 20px;">
                                <?php foreach (session()->getFlashdata('validation_errors') as $field => $error): ?>
                                    <li><small><?= esc($error) ?></small></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Info Box -->
                <div class="alert alert-warning mb-4">
                    <i class="icofont-info-circle"></i> <strong>Mode Edit:</strong> Anda sedang mengedit data pendaftaran. Periksa kembali data Anda sebelum menyimpan perubahan.
                </div>

                <!-- Main Form -->
                <form id="main-form" method="post" action="<?= base_url('update-data/update') ?>">
                    <?= csrf_field() ?>

                    <!-- Section 1: Data Diri -->
                    <?php include __DIR__ . '/sections/section1_data_diri.php'; ?>

                    <!-- Section 2: Alamat -->
                    <?php include __DIR__ . '/sections/section2_alamat.php'; ?>

                    <!-- Section 3: Data Ayah -->
                    <?php include __DIR__ . '/sections/section3_ayah.php'; ?>

                    <!-- Section 4: Data Ibu -->
                    <?php include __DIR__ . '/sections/section4_ibu.php'; ?>

                    <!-- Section 5: Data Wali -->
                    <?php include __DIR__ . '/sections/section5_wali.php'; ?>

                    <!-- Section 6: Bansos -->
                    <?php include __DIR__ . '/sections/section6_bansos.php'; ?>

                    <!-- Section 7: Asal Sekolah -->
                    <?php include __DIR__ . '/sections/section7_sekolah.php'; ?>

                    <!-- Section 8: Review -->
                    <?php include __DIR__ . '/sections/section8_review.php'; ?>

                    <!-- Navigation Buttons -->
                    <div class="d-flex justify-content-between mt-4" id="nav-buttons">
                        <button type="button" class="btn btn-secondary-custom hidden" id="btn-prev">
                            <i class="icofont-arrow-left"></i> Sebelumnya
                        </button>
                        <button type="button" class="btn btn-primary-custom" id="btn-next">
                            Selanjutnya <i class="icofont-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn btn-primary-custom hidden" id="btn-submit">
                            <i class="icofont-check-circled"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

                <!-- Cancel Link -->
                <div class="cancel-link">
                    <a href="<?= base_url('update-data/batal') ?>" onclick="return confirm('Apakah Anda yakin ingin membatalkan proses update?')">
                        <i class="icofont-close-circled"></i> Batalkan Update
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Include Components -->
    <?php include APPPATH . 'Views/components/toast.php'; ?>
    <?php include APPPATH . 'Views/components/loading.php'; ?>

    <!-- JS -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        $(document).ready(function() {
            let currentStep = 1;
            const totalSteps = 8;

            // Initialize
            showStep(currentStep);

            // Next button
            $('#btn-next').click(function() {
                if (validateStep(currentStep)) {
                    if (currentStep < totalSteps) {
                        currentStep++;
                        showStep(currentStep);
                    }
                }
            });

            // Previous button
            $('#btn-prev').click(function() {
                if (currentStep > 1) {
                    currentStep--;
                    showStep(currentStep);
                }
            });

            // Show step
            function showStep(step) {
                // Hide all sections
                $('.section-card').addClass('hidden');

                // Show current section
                $('#section-' + step).removeClass('hidden').addClass('active');

                // Update progress bar
                const percentage = (step / totalSteps) * 100;
                $('#progress-bar').css('width', percentage + '%');
                $('#progress-bar').parent().attr('aria-valuenow', percentage);
                $('#progress-percentage').text(`${percentage.toFixed(1)}% - Step ${step} of ${totalSteps}`);

                // Update step indicators
                $('.step-item').each(function() {
                    const itemStep = $(this).data('step');
                    $(this).removeClass('active completed');

                    if (itemStep < step) {
                        $(this).addClass('completed');
                    } else if (itemStep === step) {
                        $(this).addClass('active');
                    }
                });

                // Update buttons
                if (step === 1) {
                    $('#btn-prev').addClass('hidden');
                } else {
                    $('#btn-prev').removeClass('hidden');
                }

                if (step === totalSteps) {
                    $('#btn-next').addClass('hidden');
                    $('#btn-submit').removeClass('hidden');
                    populateReview();
                } else {
                    $('#btn-next').removeClass('hidden');
                    $('#btn-submit').addClass('hidden');
                }

                // Scroll to top
                $('html, body').animate({
                    scrollTop: 0
                }, 500);
            }

            // Validate step
            function validateStep(step) {
                let isValid = true;
                const section = $('#section-' + step);

                // Clear previous errors
                section.find('.is-invalid').removeClass('is-invalid');
                section.find('.invalid-feedback').remove();

                // Validate required fields
                section.find('[required]').each(function() {
                    if (!$(this).val()) {
                        isValid = false;
                        $(this).addClass('is-invalid');
                        $(this).after('<div class="invalid-feedback">Field ini wajib diisi!</div>');
                    }
                });

                if (!isValid) {
                    showToast('Mohon lengkapi semua field yang wajib diisi!', 'warning', 5000);
                }

                return isValid;
            }

            // Populate review section
            function populateReview() {
                const formData = $('#main-form').serializeArray();
                let reviewHtml = '';

                const sections = {
                    'Data Diri': ['nisn', 'nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_keluarga', 'anak_ke', 'jumlah_saudara', 'yang_membiayai_sekolah', 'hobi', 'cita_cita', 'pernah_paud', 'pernah_tk', 'kebutuhan_disabilitas[]', 'kebutuhan_khusus', 'imunisasi[]', 'no_hp', 'ukuran_baju', 'prestasi', 'minat_bakat'],
                    'Data Alamat': ['nomor_kk', 'nama_kepala_keluarga', 'jenis_tempat_tinggal', 'alamat', 'rt_rw', 'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'tinggal_bersama', 'jarak_ke_sekolah', 'waktu_tempuh', 'transportasi', 'email', 'media_sosial'],
                    'Data Ayah': ['nama_ayah', 'nik_ayah', 'tempat_lahir_ayah', 'tanggal_lahir_ayah', 'status_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'hp_ayah', 'alamat_ayah'],
                    'Data Ibu': ['nama_ibu', 'nik_ibu', 'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'status_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'hp_ibu', 'alamat_ibu'],
                    'Data Wali': ['nama_wali', 'hubungan_wali', 'nik_wali', 'tempat_lahir_wali', 'tanggal_lahir_wali', 'pendidikan_wali', 'pekerjaan_wali', 'penghasilan_wali', 'hp_wali'],
                    'Data Bansos': ['no_kks', 'no_pkh', 'no_kip'],
                    'Data Asal Sekolah': ['nama_asal_sekolah', 'jenjang_sekolah', 'status_sekolah', 'npsn', 'lokasi_sekolah', 'alamat_sekolah', 'tahun_lulus', 'rata_rata_rapor', 'nilai_tka', 'sekolah_md', 'asal_jenjang']
                };

                Object.keys(sections).forEach(sectionTitle => {
                    reviewHtml += '<div class="review-section">';
                    reviewHtml += '<div class="review-section-title">' + sectionTitle + '</div>';
                    reviewHtml += '<table class="review-table"><tbody>';

                    sections[sectionTitle].forEach(fieldName => {
                        let value = '';
                        let label = '';

                        // Handle checkbox arrays (name ends with [])
                        if (fieldName.endsWith('[]')) {
                            const fields = formData.filter(f => f.name === fieldName);
                            if (fields.length > 0) {
                                const values = fields.map(f => f.value);
                                value = values.join(', ');
                                const $input = $('[name="' + fieldName + '"]').first();
                                label = $input.closest('.mb-3').find('label').first().text().replace('*', '').trim();
                            }
                        } else {
                            // Handle regular fields
                            const field = formData.find(f => f.name === fieldName);
                            if (field && field.value) {
                                const $input = $('[name="' + fieldName + '"]');
                                value = field.value;

                                // Get appropriate label based on input type
                                if ($input.attr('type') === 'checkbox' && $input.closest('.form-check').length) {
                                    // For single checkboxes in form-check, use the check label
                                    label = $input.closest('.form-check').find('.form-check-label').text().trim();
                                    if (value === '1') {
                                        value = 'Ya';
                                    }
                                } else {
                                    // For other inputs, use the main label
                                    label = $input.closest('.mb-3').find('label').first().text().replace('*', '').trim();
                                }

                                // Format special values
                                if (value === 'L') {
                                    value = 'Laki-laki';
                                } else if (value === 'P') {
                                    value = 'Perempuan';
                                }
                            }
                        }

                        if (value) {
                            reviewHtml += '<tr><td style="width: 40%; font-weight: 600;">' + label + '</td><td>' + value + '</td></tr>';
                        }
                    });

                    reviewHtml += '</tbody></table></div>';
                });

                $('#review-content').html(reviewHtml);
            }

            // Numeric validation
            $('.numeric-only').on('input', function() {
                this.value = this.value.replace(/[^0-9]/g, '');
            });

            // Form submission
            $('#main-form').on('submit', function(e) {
                if (currentStep === 8) {
                    if (!$('#confirm_data').is(':checked')) {
                        e.preventDefault();
                        showToast('Anda harus mencentang pernyataan konfirmasi data sebelum melanjutkan!', 'warning');
                        $('#confirm_data').focus();
                        return false;
                    }
                }

                showLoading('Menyimpan perubahan data...');
                setButtonLoading($('#btn-submit')[0], true, 'Menyimpan...');
            });

            // Auto dismiss alerts
            setTimeout(function() {
                $('.alert-dismissible').fadeOut('slow');
            }, 10000);
        });
    </script>
</body>

</html>
