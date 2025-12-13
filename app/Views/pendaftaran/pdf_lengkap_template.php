<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Lengkap Pendaftaran - <?= esc($pendaftar['nomor_pendaftaran']) ?></title>
    <style>
        /* ============================================
           RESET & SETUP
        ============================================ */
        @page {
            margin: 1cm 1.5cm;
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            color: #333;
            line-height: 1.3;
        }

        /* Helper Classes */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-bold {
            font-weight: bold;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .mb-1 {
            margin-bottom: 5px;
        }

        /* ============================================
           HEADER
        ============================================ */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1AB34A;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .logo-cell {
            width: 15%;
            vertical-align: top;
        }

        .info-cell {
            width: 55%;
            vertical-align: top;
            padding-left: 10px;
        }

        .badge-cell {
            width: 30%;
            vertical-align: top;
            text-align: right;
        }

        .school-name {
            font-size: 14pt;
            font-weight: bold;
            color: #1AB34A;
            margin: 0;
        }

        .form-title {
            font-size: 12pt;
            font-weight: bold;
            color: #333;
            margin: 5px 0;
        }

        .school-address {
            font-size: 8pt;
            color: #555;
        }

        .reg-box {
            border: 2px solid #F3C623;
            background-color: #fcfcfc;
            padding: 8px;
            border-radius: 4px;
            text-align: center;
        }

        .reg-label {
            font-size: 8pt;
            color: #555;
            display: block;
        }

        .reg-number {
            font-size: 14pt;
            font-weight: bold;
            color: #1AB34A;
            display: block;
            margin-top: 2px;
        }

        /* ============================================
           SECTIONS
        ============================================ */
        .section-container {
            margin-bottom: 10px;
            page-break-inside: avoid;
        }

        .section-header {
            background-color: #1AB34A;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 10pt;
            border-radius: 4px 4px 0 0;
            border-bottom: 1px solid #148f3b;
        }

        .sub-header {
            background-color: #eee;
            color: #333;
            padding: 3px 10px;
            font-weight: bold;
            font-size: 9pt;
            border-left: 4px solid #F3C623;
            margin-top: 8px;
            margin-bottom: 4px;
        }

        /* ============================================
           DATA TABLES
        ============================================ */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
        }

        .table-data td {
            padding: 3px 5px;
            vertical-align: top;
            border-bottom: 1px solid #f0f0f0;
        }

        .td-label {
            width: 30%;
            font-weight: bold;
            color: #444;
            background-color: #fdfdfd;
        }

        .td-sep {
            width: 2%;
            text-align: center;
        }

        .td-value {
            width: 68%;
            color: #000;
        }

        .val-highlight {
            color: #1AB34A;
            font-weight: bold;
        }

        /* ============================================
           SIGNATURE & FOOTER
        ============================================ */
        .signature-table {
            width: 100%;
            margin-top: 20px;
            page-break-inside: avoid;
        }

        .sign-cell {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding: 0 20px;
        }

        .sign-title {
            font-size: 9pt;
            margin-bottom: 4px;
        }

        .sign-role {
            font-weight: bold;
            font-size: 10pt;
            margin-bottom: 40px;
        }

        .sign-name {
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 5px;
            width: 80%;
        }

        .footer {
            margin-top: 15px;
            padding-top: 5px;
            border-top: 1px dotted #ccc;
            font-size: 8pt;
            color: #777;
            text-align: center;
            font-style: italic;
        }

        .valid-badge {
            background-color: #e8f5e9;
            color: #1AB34A;
            border: 1px solid #1AB34A;
            padding: 3px 8px;
            border-radius: 10px;
            font-size: 7pt;
            font-weight: bold;
            display: inline-block;
            margin-top: 3px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="<?= FCPATH . 'assets/images/logo/01.png' ?>" width="60" alt="Logo">
            </td>
            <td class="info-cell">
                <div class="school-name">PESANTREN PERSIS 31 BANJARAN</div>
                <div class="school-address">Jl. Pajagalan No. 115, Banjaran, Bandung, Jawa Barat</div>
                <div class="form-title">FORMULIR PENDAFTARAN SANTRI BARU</div>
                <div style="font-size: 9pt;">Tahun Ajaran <?= date('Y') ?>/<?= date('Y') + 1 ?></div>
            </td>
            <td class="badge-cell">
                <div class="reg-box">
                    <span class="reg-label">NOMOR PENDAFTARAN</span>
                    <span class="reg-number"><?= esc($pendaftar['nomor_pendaftaran']) ?></span>
                </div>
                <div style="font-size: 8pt; margin-top: 5px; color: #666;">
                    Jalur: <strong><?= esc($pendaftar['jalur_pendaftaran'] ?? '-') ?></strong><br>
                    Tgl: <?= date('d/m/Y', strtotime($pendaftar['tanggal_daftar'])) ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="section-container">
        <div class="section-header">A. DATA PRIBADI SANTRI</div>
        <table class="table-data">
            <tr>
                <td class="td-label">NISN / NIK</td>
                <td class="td-sep">:</td>
                <td class="td-value"><?= esc($pendaftar['nisn'] ?? '-') ?> / <?= esc($pendaftar['nik'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="td-label">Nama Lengkap</td>
                <td class="td-sep">:</td>
                <td class="td-value val-highlight uppercase"><?= esc($pendaftar['nama_lengkap']) ?></td>
            </tr>
            <tr>
                <td class="td-label">Jenis Kelamin</td>
                <td class="td-sep">:</td>
                <td class="td-value"><?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td class="td-label">TTL (Usia)</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    <?= esc($pendaftar['tempat_lahir']) ?>, <?= !empty($pendaftar['tanggal_lahir']) ? date('d F Y', strtotime($pendaftar['tanggal_lahir'])) : '-' ?>
                    <?= !empty($pendaftar['tanggal_lahir']) ? '(Usia: ' . (date('Y') - date('Y', strtotime($pendaftar['tanggal_lahir']))) . ' Th)' : '' ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">Status dalam Keluarga</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    <?= esc($pendaftar['status_keluarga'] ?? '-') ?>
                    (Anak ke-<?= esc($pendaftar['anak_ke'] ?? '-') ?> dari <?= esc($pendaftar['jumlah_saudara'] ?? '-') ?> bersaudara)
                </td>
            </tr>
            <tr>
                <td class="td-label">No. Handphone</td>
                <td class="td-sep">:</td>
                <td class="td-value"><?= esc($pendaftar['no_hp'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="td-label">Minat & Cita-cita</td>
                <td class="td-sep">:</td>
                <td class="td-value">Hobi: <?= esc($pendaftar['hobi'] ?? '-') ?> / Cita-cita: <?= esc($pendaftar['cita_cita'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="td-label">Minat & Bakat</td>
                <td class="td-sep">:</td>
                <td class="td-value"><?= esc($pendaftar['minat_bakat'] ?: '-') ?></td>
            </tr>
            <tr>
                <td class="td-label">Yang Membiayai Sekolah</td>
                <td class="td-sep">:</td>
                <td class="td-value"><?= esc($pendaftar['yang_membiayai_sekolah'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="td-label">Riwayat Pendidikan Awal</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    Pernah PAUD: <?= !empty($pendaftar['pernah_paud']) ? 'Ya' : 'Tidak' ?> /
                    Pernah TK: <?= !empty($pendaftar['pernah_tk']) ? 'Ya' : 'Tidak' ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">Data Kesehatan</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    Disabilitas: <?= esc($pendaftar['kebutuhan_disabilitas'] ?: 'Tidak ada') ?> <br>
                    Kebutuhan Khusus: <?= esc($pendaftar['kebutuhan_khusus'] ?: 'Tidak ada') ?> <br>
                    Riwayat Imunisasi: <?= esc($pendaftar['imunisasi'] ?: '-') ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">Lainnya</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    Ukuran Baju: <strong><?= esc($pendaftar['ukuran_baju'] ?? '-') ?></strong> <br>
                    Prestasi: <?= esc($pendaftar['prestasi'] ?: '-') ?>
                </td>
            </tr>
        </table>
    </div>

    <?php if ($alamat): ?>
        <div class="section-container">
            <div class="section-header">B. DATA TEMPAT TINGGAL</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">No. Kartu Keluarga</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($alamat['nomor_kk'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Nama Kepala Keluarga</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($alamat['nama_kepala_keluarga'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Jenis Tempat Tinggal</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($alamat['jenis_tempat_tinggal'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Tinggal Bersama</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($alamat['tinggal_bersama'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Alamat Lengkap</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($alamat['alamat'] ?? '-') ?>
                        <?= !empty($alamat['rt_rw']) ? ' RT/RW: ' . esc($alamat['rt_rw']) : '' ?><br>
                        Desa <?= esc($alamat['desa'] ?? '-') ?>, Kec. <?= esc($alamat['kecamatan'] ?? '-') ?><br>
                        <?= esc($alamat['kabupaten'] ?? '-') ?>, Prov. <?= esc($alamat['provinsi'] ?? '-') ?>
                        <?= !empty($alamat['kode_pos']) ? ' - ' . esc($alamat['kode_pos']) : '' ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Jarak & Transportasi</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($alamat['jarak_ke_sekolah'] ?? '-') ?> (Waktu Tempuh: <?= esc($alamat['waktu_tempuh'] ?? '-') ?>)<br>
                        Transportasi: <?= esc($alamat['transportasi'] ?? '-') ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Kontak Digital</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        Email: <?= esc($alamat['email'] ?: '-') ?> <br>
                        Medsos: <?= esc($alamat['media_sosial'] ?: '-') ?>
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <?php if (isset($bansos) && (!empty($bansos['no_kks']) || !empty($bansos['no_pkh']) || !empty($bansos['no_kip']))): ?>
        <div class="section-container">
            <div class="section-header">C. DATA BANTUAN SOSIAL</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">No. KKS (Kartu Keluarga Sejahtera)</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($bansos['no_kks'] ?: '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">No. PKH (Program Keluarga Harapan)</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($bansos['no_pkh'] ?: '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">No. KIP (Kartu Indonesia Pintar)</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($bansos['no_kip'] ?: '-') ?></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <div class="section-container">
        <div class="section-header">D. DATA ORANG TUA / WALI</div>

        <?php if ($ayah): ?>
            <div class="sub-header">1. DATA AYAH KANDUNG</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Lengkap & NIK</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($ayah['nama_ayah'] ?? '-') ?> <span style="font-weight:normal">(NIK: <?= esc($ayah['nik_ayah'] ?? '-') ?>)</span></td>
                </tr>
                <tr>
                    <td class="td-label">Tempat, Tanggal Lahir</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($ayah['tempat_lahir_ayah'] ?? '-') ?>, <?= !empty($ayah['tanggal_lahir_ayah']) ? date('d-m-Y', strtotime($ayah['tanggal_lahir_ayah'])) : '-' ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Status & Pendidikan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($ayah['status_ayah'] ?? '-') ?> / Pendidikan Terakhir: <?= esc($ayah['pendidikan_ayah'] ?? '-') ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Pekerjaan & Penghasilan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ayah['pekerjaan_ayah'] ?? '-') ?> (Rp <?= esc($ayah['penghasilan_ayah'] ?? '-') ?>)</td>
                </tr>
                <tr>
                    <td class="td-label">No. Handphone</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ayah['hp_ayah'] ?? '-') ?></td>
                </tr>
                <?php if (!empty($ayah['alamat_ayah'])): ?>
                    <tr>
                        <td class="td-label">Alamat Khusus</td>
                        <td class="td-sep">:</td>
                        <td class="td-value"><?= esc($ayah['alamat_ayah']) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        <?php endif; ?>

        <?php if ($ibu): ?>
            <div class="sub-header">2. DATA IBU KANDUNG</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Lengkap & NIK</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($ibu['nama_ibu'] ?? '-') ?> <span style="font-weight:normal">(NIK: <?= esc($ibu['nik_ibu'] ?? '-') ?>)</span></td>
                </tr>
                <tr>
                    <td class="td-label">Tempat, Tanggal Lahir</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($ibu['tempat_lahir_ibu'] ?? '-') ?>, <?= !empty($ibu['tanggal_lahir_ibu']) ? date('d-m-Y', strtotime($ibu['tanggal_lahir_ibu'])) : '-' ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Status & Pendidikan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($ibu['status_ibu'] ?? '-') ?> / Pendidikan Terakhir: <?= esc($ibu['pendidikan_ibu'] ?? '-') ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Pekerjaan & Penghasilan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ibu['pekerjaan_ibu'] ?? '-') ?> (Rp <?= esc($ibu['penghasilan_ibu'] ?? '-') ?>)</td>
                </tr>
                <tr>
                    <td class="td-label">No. Handphone</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ibu['hp_ibu'] ?? '-') ?></td>
                </tr>
                <?php if (!empty($ibu['alamat_ibu'])): ?>
                    <tr>
                        <td class="td-label">Alamat Khusus</td>
                        <td class="td-sep">:</td>
                        <td class="td-value"><?= esc($ibu['alamat_ibu']) ?></td>
                    </tr>
                <?php endif; ?>
            </table>
        <?php endif; ?>

        <?php if ($wali && !empty($wali['nama_wali'])): ?>
            <div class="sub-header">3. DATA WALI</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Lengkap & NIK</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($wali['nama_wali']) ?> <span style="font-weight:normal">(NIK: <?= esc($wali['nik_wali'] ?? '-') ?>)</span></td>
                </tr>
                <tr>
                    <td class="td-label">Hubungan dengan Santri</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($wali['hubungan_wali'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Tempat, Tanggal Lahir</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($wali['tempat_lahir_wali'] ?? '-') ?>, <?= !empty($wali['tanggal_lahir_wali']) ? date('d-m-Y', strtotime($wali['tanggal_lahir_wali'])) : '-' ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Pendidikan Terakhir</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($wali['pendidikan_wali'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Pekerjaan & Penghasilan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($wali['pekerjaan_wali'] ?? '-') ?> (Rp <?= esc($wali['penghasilan_wali'] ?? '-') ?>)
                    </td>
                </tr>
                <tr>
                    <td class="td-label">No. Handphone</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($wali['hp_wali'] ?? '-') ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <?php if ($sekolah): ?>
        <div class="section-container">
            <div class="section-header">E. DATA ASAL SEKOLAH</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Sekolah</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($sekolah['nama_asal_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Detail Sekolah</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        Jenjang: <?= esc($sekolah['jenjang_sekolah'] ?? '-') ?> /
                        Status: <?= esc($sekolah['status_sekolah'] ?? '-') ?> /
                        NPSN: <?= esc($sekolah['npsn'] ?? '-') ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Lokasi Sekolah</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($sekolah['lokasi_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Alamat Sekolah</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($sekolah['alamat_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Tahun Lulus</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($sekolah['tahun_lulus'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Nilai Akademik</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        Rata-rata Rapor: <?= esc($sekolah['rata_rata_rapor'] ?? '-') ?> /
                        Nilai TKA: <?= esc($sekolah['nilai_tka'] ?? '-') ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Sekolah MD (Madrasah Diniyah)</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($sekolah['sekolah_md'] ?: '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Asal Jenjang</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($sekolah['asal_jenjang'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <table class="signature-table">
        <tr>
            <td class="sign-cell">
                <div class="sign-title">Mengetahui,</div>
                <div class="sign-role">PANITIA PSB</div>
                <br><br>
                <span class="sign-name">( ........................................ )</span>
            </td>
            <td class="sign-cell">
                <div class="sign-title">Banjaran, <?= date('d F Y') ?></div>
                <div class="sign-role">ORANG TUA / WALI</div>
                <br><br>
                <span class="sign-name">( ........................................ )</span>
            </td>
        </tr>
    </table>

    <div class="footer">
        <div class="valid-badge">
            &#10003; DOKUMEN SAH BUKTI PENDAFTARAN
        </div>
        <br>
        Dicetak otomatis oleh Sistem Informasi PSB Pesantren Persis 31 Banjaran<br>
        pada tanggal <?= date('d F Y, H:i') ?> WIB
    </div>

</body>

</html>