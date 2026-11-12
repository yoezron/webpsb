<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title) ?> - PSB Persis 31 Banjaran</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= logo_aset('favicon.ico') ?>">

    <!-- Hafsa Template CSS -->
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
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fa;
            padding: 30px;
        }

        .detail-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: white;
            color: #333;
            text-decoration: none;
            border-radius: 10px;
            font-weight: 500;
            margin-bottom: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .back-btn:hover {
            background: var(--primary-green);
            color: white;
            transform: translateX(-5px);
        }

        .detail-header {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
            padding: 30px;
            border-radius: 15px 15px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .header-info h1 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .header-info p {
            font-size: 1rem;
            color: var(--secondary-yellow);
            margin: 0;
        }

        .registration-number {
            background: rgba(255, 255, 255, 0.2);
            padding: 15px 25px;
            border-radius: 10px;
            text-align: center;
        }

        .registration-number span {
            font-size: 0.8rem;
            display: block;
            margin-bottom: 5px;
        }

        .registration-number strong {
            font-size: 1.5rem;
        }

        .detail-body {
            background: white;
            padding: 30px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-green);
            border-bottom: 2px solid var(--light-green);
            padding-bottom: 10px;
            margin-bottom: 20px;
            margin-top: 30px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .section-title i {
            font-size: 1.4rem;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
        }

        .info-item {
            display: flex;
            flex-direction: column;
        }

        .info-label {
            font-size: 0.8rem;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        .info-value {
            font-size: 1rem;
            color: #333;
            font-weight: 500;
        }

        .info-value.empty {
            color: #ccc;
            font-style: italic;
        }

        .badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .badge-tsanawiyyah {
            background: var(--light-green);
            color: var(--dark-green);
        }

        .badge-muallimin {
            background: #fef3c7;
            color: #d97706;
        }

        .badge-male {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-female {
            background: #fce7f3;
            color: #be185d;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            flex-wrap: wrap;
        }

        .btn-action {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-primary-custom {
            background: linear-gradient(135deg, var(--primary-green), var(--dark-green));
            color: white;
        }

        .btn-primary-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26, 179, 74, 0.3);
            color: white;
        }

        .btn-danger-custom {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
        }

        .btn-danger-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 38, 38, 0.3);
            color: white;
        }

        @media (max-width: 768px) {
            body {
                padding: 15px;
            }

            .detail-header {
                padding: 20px;
            }

            .header-info h1 {
                font-size: 1.4rem;
            }

            .detail-body {
                padding: 20px;
            }

            .info-grid {
                grid-template-columns: 1fr;
            }
        }

        @media print {
            body {
                background: white;
                padding: 0;
            }

            .back-btn,
            .action-buttons {
                display: none;
            }

            .detail-header {
                background: var(--primary-green);
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>

    <div class="detail-container animate__animated animate__fadeIn">
        <a href="javascript:history.back()" class="back-btn">
            <i class="icofont-arrow-left"></i>
            Kembali ke Dashboard
        </a>

        <div class="detail-card">
            <!-- Header -->
            <div class="detail-header">
                <div class="header-info">
                    <h1><?= esc($pendaftar['nama_lengkap']) ?></h1>
                    <p>
                        <span class="badge <?= $pendaftar['jalur_pendaftaran'] === 'TSANAWIYYAH' ? 'badge-tsanawiyyah' : 'badge-muallimin' ?>">
                            <?= esc($pendaftar['jalur_pendaftaran']) ?>
                        </span>
                    </p>
                </div>
                <div class="registration-number">
                    <span>Nomor Pendaftaran</span>
                    <strong><?= esc($pendaftar['nomor_pendaftaran']) ?></strong>
                </div>
            </div>

            <!-- Body -->
            <div class="detail-body">
                <!-- Data Diri -->
                <h3 class="section-title">
                    <i class="icofont-user"></i>
                    Data Diri Calon Santri
                </h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">NISN</span>
                        <span class="info-value"><?= esc($pendaftar['nisn'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIK</span>
                        <span class="info-value"><?= esc($pendaftar['nik'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value"><?= esc($pendaftar['nama_lengkap']) ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-value">
                            <span class="badge <?= $pendaftar['jenis_kelamin'] === 'L' ? 'badge-male' : 'badge-female' ?>">
                                <?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                            </span>
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Agama</span>
                        <span class="info-value"><?= esc($pendaftar['agama'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tempat, Tanggal Lahir</span>
                        <span class="info-value"><?= esc($pendaftar['tempat_lahir'] ?? '-') ?>, <?= !empty($pendaftar['tanggal_lahir']) ? date('d F Y', strtotime($pendaftar['tanggal_lahir'])) : '-' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">No. HP</span>
                        <span class="info-value"><?= esc($pendaftar['no_hp'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status dalam Keluarga</span>
                        <span class="info-value"><?= esc($pendaftar['status_keluarga'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Anak ke / Jumlah Saudara</span>
                        <span class="info-value"><?= esc($pendaftar['anak_ke'] ?? '-') ?> dari <?= esc($pendaftar['jumlah_saudara'] ?? '-') ?> bersaudara</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Yang Membiayai Sekolah</span>
                        <span class="info-value"><?= esc($pendaftar['yang_membiayai_sekolah'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Hobi</span>
                        <span class="info-value"><?= esc($pendaftar['hobi'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Cita-cita</span>
                        <span class="info-value"><?= esc($pendaftar['cita_cita'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pernah PAUD</span>
                        <span class="info-value"><?= isset($pendaftar['pernah_paud']) && $pendaftar['pernah_paud'] == 1 ? 'Ya' : 'Tidak' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Pernah TK</span>
                        <span class="info-value"><?= isset($pendaftar['pernah_tk']) && $pendaftar['pernah_tk'] == 1 ? 'Ya' : 'Tidak' ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kebutuhan Disabilitas</span>
                        <span class="info-value"><?= esc($pendaftar['kebutuhan_disabilitas'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kebutuhan Khusus</span>
                        <span class="info-value"><?= esc($pendaftar['kebutuhan_khusus'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Imunisasi</span>
                        <span class="info-value"><?= esc($pendaftar['imunisasi'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Ukuran Baju</span>
                        <span class="info-value"><?= esc($pendaftar['ukuran_baju'] ?? '-') ?></span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Prestasi</span>
                        <span class="info-value"><?= esc($pendaftar['prestasi'] ?? '-') ?></span>
                    </div>
                    <?php if (!empty($pendaftar['minat_bakat'])): ?>
                    <div class="info-item" style="grid-column: 1 / -1;">
                        <span class="info-label">Minat & Bakat</span>
                        <span class="info-value"><?= nl2br(esc($pendaftar['minat_bakat'])) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="info-item">
                        <span class="info-label">Tanggal Daftar</span>
                        <span class="info-value"><?= date('d F Y, H:i', strtotime($pendaftar['tanggal_daftar'])) ?> WIB</span>
                    </div>
                </div>

                <!-- Alamat -->
                <?php if ($alamat): ?>
                    <h3 class="section-title">
                        <i class="icofont-location-pin"></i>
                        Alamat Domisili
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nomor KK</span>
                            <span class="info-value"><?= esc($alamat['nomor_kk'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nama Kepala Keluarga</span>
                            <span class="info-value"><?= esc($alamat['nama_kepala_keluarga'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenis Tempat Tinggal</span>
                            <span class="info-value"><?= esc($alamat['jenis_tempat_tinggal'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alamat</span>
                            <span class="info-value"><?= esc($alamat['alamat'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">RT/RW</span>
                            <span class="info-value"><?= esc($alamat['rt_rw'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Desa/Kelurahan</span>
                            <span class="info-value"><?= esc($alamat['desa'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kecamatan</span>
                            <span class="info-value"><?= esc($alamat['kecamatan'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kabupaten/Kota</span>
                            <span class="info-value"><?= esc($alamat['kabupaten'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Provinsi</span>
                            <span class="info-value"><?= esc($alamat['provinsi'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Kode Pos</span>
                            <span class="info-value"><?= esc($alamat['kode_pos'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status Tempat Tinggal Santri</span>
                            <span class="info-value"><?= esc($alamat['tinggal_bersama'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jarak ke Pesantren</span>
                            <span class="info-value"><?= esc($alamat['jarak_ke_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Waktu Tempuh</span>
                            <span class="info-value"><?= esc($alamat['waktu_tempuh'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Transportasi</span>
                            <span class="info-value"><?= esc($alamat['transportasi'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Email Santri</span>
                            <span class="info-value"><?= esc($alamat['email'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Media Sosial</span>
                            <span class="info-value"><?= esc($alamat['media_sosial'] ?? '-') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Kartu Keluarga -->
                <h3 class="section-title">
                    <i class="icofont-id-card"></i>
                    Kartu Keluarga
                </h3>
                <?php if (\App\Libraries\FormPendaftaran::pathKk($pendaftar['file_kk'] ?? null)): ?>
                    <div class="mb-4">
                        <a href="<?= base_url('dashboard/kk/' . $pendaftar['id_pendaftar']) ?>" target="_blank" rel="noopener">
                            <img src="<?= base_url('dashboard/kk/' . $pendaftar['id_pendaftar']) ?>" alt="Kartu Keluarga" style="max-width: 100%; max-height: 360px; border-radius: 10px; border: 1px solid #ddd;">
                        </a>
                        <div class="mt-2">
                            <a href="<?= base_url('dashboard/kk/' . $pendaftar['id_pendaftar'] . '?unduh=1') ?>" class="btn btn-sm btn-success">
                                <i class="icofont-download"></i> Unduh (<?= esc(\App\Libraries\FormPendaftaran::namaFileUnduhKk($pendaftar)) ?>)
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-muted mb-4"><i class="icofont-warning"></i> Pendaftar belum mengunggah Kartu Keluarga.</p>
                <?php endif; ?>

                <?php
                $daftarOrang = [
                    'ayah' => ['Data Ayah Kandung', 'icofont-user-male', $ayah ?? null],
                    'ibu'  => ['Data Ibu Kandung', 'icofont-user-female', $ibu ?? null],
                    'wali' => ['Data Wali', 'icofont-users', (!empty($wali) && !empty($wali['nama_wali'])) ? $wali : null],
                ];
                ?>
                <?php foreach ($daftarOrang as $p => [$judul, $ikon, $o]): ?>
                    <?php if (!$o) {
                        if ($p === 'wali') {
                            echo '<h3 class="section-title"><i class="icofont-users"></i> Data Wali</h3><p class="text-muted mb-4">Tidak ada wali.</p>';
                        }
                        continue;
                    } ?>
                    <h3 class="section-title">
                        <i class="<?= $ikon ?>"></i>
                        <?= $judul ?>
                    </h3>
                    <div class="info-grid">
                        <?php
                        $item = [
                            $p === 'wali' ? 'Status / Hubungan Wali' : 'Status' => $p === 'wali' ? ($o['hubungan_wali'] ?? '') : ($o['status_' . $p] ?? ''),
                            'NIK'                   => $o['nik_' . $p] ?? '',
                            'Nama Lengkap'          => $o['nama_' . $p] ?? '',
                            'Tempat, Tanggal Lahir' => trim(($o['tempat_lahir_' . $p] ?? '') . (!empty($o['tanggal_lahir_' . $p]) ? ', ' . date('d F Y', strtotime($o['tanggal_lahir_' . $p])) : ''), ', '),
                            'Pendidikan Terakhir'   => $o['pendidikan_' . $p] ?? '',
                            'Pekerjaan Utama'       => $o['pekerjaan_' . $p] ?? '',
                            'Domisili'              => $o['domisili_' . $p] ?? '',
                            'No. HP'                => $o['hp_' . $p] ?? '',
                            'Penghasilan / Bulan'   => $o['penghasilan_' . $p] ?? '',
                            'Status Tempat Tinggal' => $o['status_tempat_tinggal_' . $p] ?? '',
                            'Alamat'                => $o['alamat_' . $p] ?? '',
                        ];
                        ?>
                        <?php foreach ($item as $lbl => $nilai): ?>
                            <div class="info-item"<?= $lbl === 'Alamat' ? ' style="grid-column: 1 / -1;"' : '' ?>>
                                <span class="info-label"><?= esc($lbl) ?></span>
                                <span class="info-value"><?= ($nilai !== '' && $nilai !== null) ? nl2br(esc($nilai)) : '-' ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endforeach; ?>

                <!-- Data Bansos -->
                <h3 class="section-title">
                    <i class="icofont-card"></i>
                    Bantuan Sosial
                </h3>
                <div class="info-grid">
                    <?php foreach (['no_kks' => 'No. KKS', 'no_pkh' => 'No. PKH', 'no_kip' => 'No. KIP'] as $k => $lbl): ?>
                        <div class="info-item">
                            <span class="info-label"><?= $lbl ?></span>
                            <span class="info-value"><?= esc(!empty($bansos[$k]) ? $bansos[$k] : '-') ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Data Sekolah -->
                <?php if ($sekolah): ?>
                    <h3 class="section-title">
                        <i class="icofont-education"></i>
                        Asal Sekolah
                    </h3>
                    <div class="info-grid">
                        <div class="info-item">
                            <span class="info-label">Nama Sekolah</span>
                            <span class="info-value"><?= esc($sekolah['nama_asal_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">NPSN</span>
                            <span class="info-value"><?= esc($sekolah['npsn'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Jenjang</span>
                            <span class="info-value"><?= esc($sekolah['jenjang_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Status Sekolah</span>
                            <span class="info-value"><?= esc($sekolah['status_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Lokasi</span>
                            <span class="info-value"><?= esc($sekolah['lokasi_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Alamat Sekolah</span>
                            <span class="info-value"><?= esc($sekolah['alamat_sekolah'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tahun Lulus</span>
                            <span class="info-value"><?= esc($sekolah['tahun_lulus'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Rata-rata Nilai Rapor</span>
                            <span class="info-value"><?= esc($sekolah['rata_rata_rapor'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Nilai TKA</span>
                            <span class="info-value"><?= esc($sekolah['nilai_tka'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Sekolah MD (Madrasah Diniyah)</span>
                            <span class="info-value"><?= esc($sekolah['sekolah_md'] ?? '-') ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Asal Jenjang</span>
                            <span class="info-value"><?= esc($sekolah['asal_jenjang'] ?? '-') ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Action Buttons -->
                <div class="action-buttons">
                    <a href="<?= base_url('pendaftaran/download-pdf/' . $pendaftar['nomor_pendaftaran']) ?>" class="btn-action btn-primary-custom" target="_blank">
                        <i class="icofont-file-pdf"></i> Download PDF Lengkap
                    </a>
                    <a href="<?= base_url('pendaftaran/download-kartu/' . $pendaftar['nomor_pendaftaran']) ?>" class="btn-action btn-danger-custom" target="_blank">
                        <i class="icofont-id-card"></i> Download Kartu Pendaftaran
                    </a>
                    <button onclick="window.print()" class="btn-action btn-primary-custom">
                        <i class="icofont-print"></i> Cetak
                    </button>
                    <a href="<?= base_url('dashboard/edit/' . $pendaftar['id_pendaftar']) ?>" class="btn-action btn-primary-custom" style="background: #f0ad4e;">
                        <i class="icofont-edit"></i> Edit Data
                    </a>
                    <button type="button" class="btn-action btn-danger-custom btn-delete"
                        data-id="<?= $pendaftar['id_pendaftar'] ?>"
                        data-nama="<?= esc($pendaftar['nama_lengkap'], 'attr') ?>"
                        data-nomor="<?= esc($pendaftar['nomor_pendaftaran'], 'attr') ?>">
                        <i class="icofont-trash"></i> Hapus Pendaftar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <?php include APPPATH . 'Views/dashboard/_modal_hapus.php'; ?>
    <?php include APPPATH . 'Views/components/toast.php'; ?>

</body>

</html>
