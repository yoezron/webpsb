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

        .form-header-logo {
            width: 150px;
            /* Ubah height menjadi auto agar rasio gambar tetap terjaga */
            height: auto;
            margin: 0 auto 20px;

            /* Properti berikut dihapus/dinonaktifkan agar tidak ada lingkaran putih */
            /* background: white; */
            /* padding: 10px; */
            /* border-radius: 50%; */
            /* box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); */

            /* Flexbox juga bisa dihapus karena container sekarang hanya pembungkus biasa */
            /* display: flex; */
            /* align-items: center; */
            /* justify-content: center; */
        }

        .form-header-logo img {
            width: 100%;
            height: auto;
            display: block;
            /* Menghilangkan celah baris pada elemen inline */
            object-fit: contain;
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
            background: linear-gradient(90deg, var(--primary-green), var(--dark-green));
            border-radius: 10px;
            transition: width 0.5s ease;
            position: relative;
        }

        .progress-percentage {
            text-align: center;
            font-size: 0.9rem;
            color: var(--dark-green);
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
            background: var(--primary-green);
            color: white;
            box-shadow: 0 4px 15px rgba(26, 179, 74, 0.4);
            transform: scale(1.1);
        }

        .step-item.completed .step-number {
            background: var(--dark-green);
            color: white;
        }

        .step-label {
            font-size: 0.75rem;
            color: #666;
            text-align: center;
            line-height: 1.2;
        }

        .step-item.active .step-label {
            color: var(--primary-green);
            font-weight: 600;
        }

        .section-card {
            border: 2px solid #e0e0e0;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
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

        .form-control,
        .form-select {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            transition: all 0.3s ease;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(26, 179, 74, 0.25);
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
            display: none !important;
        }

        .review-table {
            width: 100%;
            border-collapse: collapse;
        }

        .review-table th {
            background: var(--light-green);
            padding: 12px;
            text-align: left;
            font-weight: 700;
            color: var(--dark-green);
            border-bottom: 2px solid var(--primary-green);
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
            color: var(--dark-green);
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-green);
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
                <h1>Form Pendaftaran <?= esc($jalur_label) ?></h1>
                <p>Pesantren Persatuan Islam 31 Banjaran</p>
                <p>Tahun Ajaran <?= esc($year) ?>/<?= esc($year + 1) ?></p>
            </div>

            <!-- Body -->
            <div class="form-body">
                <!-- Progress Bar -->
                <div class="progress-bar-container" role="progressbar" aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100" aria-label="Form progress">
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

                <!-- Main Form -->
                <form id="main-form" method="post" action="<?= base_url('pendaftaran/submit/' . strtolower($jalur)) ?>">
                    <?= csrf_field() ?>
                    <input type="hidden" name="jalur" value="<?= esc($jalur) ?>">

                    <?php include __DIR__ . '/sections/section1_data_diri.php'; ?>
                    <?php include __DIR__ . '/sections/section2_alamat.php'; ?>
                    <?php include __DIR__ . '/sections/section3_ayah.php'; ?>
                    <?php include __DIR__ . '/sections/section4_ibu.php'; ?>
                    <?php include __DIR__ . '/sections/section5_wali.php'; ?>
                    <?php include __DIR__ . '/sections/section6_bansos.php'; ?>
                    <?php include __DIR__ . '/sections/section7_sekolah.php'; ?>
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
                            <i class="icofont-check-circled"></i> Submit Pendaftaran
                        </button>
                    </div>
                </form>
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
                        saveToLocalStorage();
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

                // Scroll to top with smooth animation
                $('html, body').animate({
                    scrollTop: 0
                }, 500);

                // Announce to screen readers
                const announcement = `Step ${step} of ${totalSteps}`;
                const srAnnounce = document.createElement('div');
                srAnnounce.setAttribute('role', 'status');
                srAnnounce.setAttribute('aria-live', 'polite');
                srAnnounce.className = 'sr-only';
                srAnnounce.textContent = announcement;
                document.body.appendChild(srAnnounce);
                setTimeout(() => document.body.removeChild(srAnnounce), 1000);
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

            // Save to localStorage
            function saveToLocalStorage() {
                const formData = $('#main-form').serializeArray();
                localStorage.setItem('pendaftaran_form', JSON.stringify(formData));
                console.log('Auto-save: Form data saved to localStorage');
            }

            // Load from localStorage
            function loadFromLocalStorage() {
                const saved = localStorage.getItem('pendaftaran_form');
                if (saved) {
                    const formData = JSON.parse(saved);
                    formData.forEach(item => {
                        const input = $('[name="' + item.name + '"]');
                        if (input.attr('type') === 'checkbox') {
                            input.prop('checked', item.value === '1');
                        } else {
                            input.val(item.value);
                        }
                    });
                }
            }

            // Populate review section
            function populateReview() {
                const formData = $('#main-form').serializeArray();
                let reviewHtml = '';

                // Group data by section
                const sections = {
                    'Data Diri': ['nisn', 'nik', 'nama_lengkap', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'status_keluarga', 'anak_ke', 'jumlah_saudara', 'hobi', 'cita_cita', 'pernah_paud', 'pernah_tk', 'kebutuhan_disabilitas', 'imunisasi', 'no_hp', 'ukuran_baju', 'prestasi'],
                    'Data Alamat': ['nomor_kk', 'jenis_tempat_tinggal', 'alamat', 'desa', 'kecamatan', 'kabupaten', 'provinsi', 'kode_pos', 'jarak_ke_sekolah', 'waktu_tempuh', 'transportasi', 'email', 'media_sosial'],
                    'Data Ayah': ['nama_ayah', 'nik_ayah', 'tempat_lahir_ayah', 'tanggal_lahir_ayah', 'status_ayah', 'pendidikan_ayah', 'pekerjaan_ayah', 'penghasilan_ayah', 'hp_ayah', 'alamat_ayah'],
                    'Data Ibu': ['nama_ibu', 'nik_ibu', 'tempat_lahir_ibu', 'tanggal_lahir_ibu', 'status_ibu', 'pendidikan_ibu', 'pekerjaan_ibu', 'penghasilan_ibu', 'hp_ibu', 'alamat_ibu'],
                    'Data Wali': ['nama_wali', 'nik_wali', 'tahun_lahir_wali', 'pendidikan_wali', 'pekerjaan_wali', 'penghasilan_wali', 'hp_wali'],
                    'Data Bansos': ['no_kks', 'no_pkh', 'no_kip'],
                    'Data Asal Sekolah': ['nama_asal_sekolah', 'jenjang_sekolah', 'status_sekolah', 'npsn', 'lokasi_sekolah', 'asal_jenjang']
                };

                Object.keys(sections).forEach(sectionTitle => {
                    reviewHtml += '<div class="review-section">';
                    reviewHtml += '<div class="review-section-title">' + sectionTitle + '</div>';
                    reviewHtml += '<table class="review-table"><tbody>';

                    sections[sectionTitle].forEach(fieldName => {
                        const field = formData.find(f => f.name === fieldName);
                        if (field && field.value) {
                            const label = $('[name="' + fieldName + '"]').closest('.mb-3').find('label').text().replace('*', '').trim();
                            let value = field.value;

                            // Format special values
                            if (value === '1' && $('[name="' + fieldName + '"]').attr('type') === 'checkbox') {
                                value = 'Ya';
                            } else if (value === 'L') {
                                value = 'Laki-laki';
                            } else if (value === 'P') {
                                value = 'Perempuan';
                            }

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

            // Load saved data on page load
            loadFromLocalStorage();

            // Clear localStorage on successful submit
            $('#main-form').on('submit', function(e) {
                // Validate confirm_data checkbox on step 8
                if (currentStep === 8) {
                    if (!$('#confirm_data').is(':checked')) {
                        e.preventDefault();
                        showToast('Anda harus mencentang pernyataan konfirmasi data sebelum melanjutkan!', 'warning');
                        $('#confirm_data').focus();
                        return false;
                    }
                }

                // Show loading state
                showLoading('Menyimpan data pendaftaran...');
                setButtonLoading($('#btn-submit')[0], true, 'Menyimpan...');

                // Clear localStorage on successful submission
                localStorage.removeItem('pendaftaran_form');
            });

            // Auto save every 30 seconds
            setInterval(function() {
                saveToLocalStorage();
                // Show subtle notification (optional - commented out to avoid annoyance)
                // showToast('Formulir tersimpan otomatis', 'info', 2000);
            }, 30000);

            // Save on input change
            $('#main-form input, #main-form select, #main-form textarea').on('change', function() {
                saveToLocalStorage();
            });

            // Highlight fields with validation errors
            <?php if (session()->getFlashdata('validation_errors')): ?>
                const validationErrors = <?= json_encode(array_keys(session()->getFlashdata('validation_errors'))) ?>;
                validationErrors.forEach(function(fieldName) {
                    const field = $('#' + fieldName);
                    if (field.length) {
                        field.addClass('is-invalid');
                        // Add error message below field if not exists
                        if (!field.next('.invalid-feedback').length) {
                            field.after('<div class="invalid-feedback" style="display: block;">' +
                                <?= json_encode(session()->getFlashdata('validation_errors')) ?>[fieldName] +
                                '</div>');
                        }
                    }
                });

                // Scroll to first error field
                if (validationErrors.length > 0) {
                    const firstErrorField = $('#' + validationErrors[0]);
                    if (firstErrorField.length) {
                        $('html, body').animate({
                            scrollTop: firstErrorField.offset().top - 100
                        }, 500);
                    }
                }
            <?php endif; ?>

            // Auto dismiss alerts (only dismissible alerts, not the confirmation checkbox)
            setTimeout(function() {
                $('.alert-dismissible').fadeOut('slow');
            }, 10000);

            // Screen reader only class for accessibility
            if (!$('.sr-only').length) {
                $('<style>.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border-width:0}</style>').appendTo('head');
            }

            // Keyboard navigation for step indicator
            $('.step-item').attr('tabindex', '0').on('keypress', function(e) {
                if (e.which === 13 || e.which === 32) { // Enter or Space
                    const targetStep = $(this).data('step');
                    if (targetStep < currentStep) {
                        currentStep = targetStep;
                        showStep(currentStep);
                    }
                }
            });

            // Add aria-labels to form inputs without labels
            $('input, select, textarea').each(function() {
                const $this = $(this);
                if (!$this.attr('aria-label') && !$this.attr('aria-labelledby')) {
                    const label = $this.closest('.mb-3').find('label').text().trim();
                    if (label) {
                        $this.attr('aria-label', label);
                    }
                }
            });

            console.log('Form initialized successfully');
        });
    </script>
</body>

</html>