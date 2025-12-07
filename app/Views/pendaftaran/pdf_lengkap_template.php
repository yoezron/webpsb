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
            /* Margin sedikit diperkecil agar muat lebih banyak */
            size: A4 portrait;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            /* Font standar PDF aman */
            font-size: 10pt;
            color: #333;
            line-height: 1.4;
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

        .mb-2 {
            margin-bottom: 10px;
        }

        /* ============================================
           HEADER (Table Based for Safety)
        ============================================ */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1AB34A;
            padding-bottom: 10px;
            margin-bottom: 20px;
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

        /* Kotak Nomer Pendaftaran */
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
            margin-bottom: 15px;
            /* Mencegah section terpotong jelek di perpindahan halaman */
            page-break-inside: avoid;
        }

        .section-header {
            background-color: #1AB34A;
            color: white;
            padding: 6px 10px;
            font-weight: bold;
            font-size: 11pt;
            border-radius: 4px 4px 0 0;
            border-bottom: 2px solid #148f3b;
        }

        .sub-header {
            background-color: #eee;
            color: #333;
            padding: 4px 10px;
            font-weight: bold;
            font-size: 9pt;
            border-left: 4px solid #F3C623;
            margin-top: 10px;
            margin-bottom: 5px;
        }

        /* ============================================
           DATA TABLES
        ============================================ */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .table-data td {
            padding: 5px 8px;
            vertical-align: top;
            border-bottom: 1px solid #eee;
        }

        /* Kolom Label */
        .td-label {
            width: 35%;
            font-weight: bold;
            color: #444;
            background-color: #fdfdfd;
        }

        /* Kolom Titik Dua */
        .td-sep {
            width: 2%;
            text-align: center;
        }

        /* Kolom Isi */
        .td-value {
            width: 63%;
            color: #000;
        }

        /* Highlight */
        .val-highlight {
            color: #1AB34A;
            font-weight: bold;
        }

        /* ============================================
           SIGNATURE
        ============================================ */
        .signature-table {
            width: 100%;
            margin-top: 30px;
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
            margin-bottom: 50px;
        }

        .sign-name {
            border-top: 1px solid #333;
            display: inline-block;
            padding-top: 5px;
            width: 80%;
        }

        /* ============================================
           FOOTER
        ============================================ */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
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
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 8pt;
            font-weight: bold;
            display: inline-block;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="logo-cell">
                <img src="<?= FCPATH . 'assets/images/logo/01.png' ?>" width="70" alt="Logo">
            </td>
            <td class="info-cell">
                <div class="school-name">PESANTREN PERSIS 31 BANJARAN</div>
                <div class="school-address">Jl. Raya Banjaran No. 123, Banjaran, Bandung, Jawa Barat</div>
                <div class="form-title">FORMULIR PENDAFTARAN SANTRI BARU</div>
                <div style="font-size: 9pt;">Tahun Ajaran <?= date('Y') ?>/<?= date('Y') + 1 ?></div>
            </td>
            <td class="badge-cell">
                <div class="reg-box">
                    <span class="reg-label">NOMOR PENDAFTARAN</span>
                    <span class="reg-number"><?= esc($pendaftar['nomor_pendaftaran']) ?></span>
                </div>
                <div style="font-size: 8pt; margin-top: 5px; color: #666;">
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
                <td class="td-value">
                    <?= esc($pendaftar['nisn'] ?? '-') ?> / <?= esc($pendaftar['nik'] ?? '-') ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">Nama Lengkap</td>
                <td class="td-sep">:</td>
                <td class="td-value val-highlight uppercase"><?= esc($pendaftar['nama_lengkap']) ?></td>
            </tr>
            <tr>
                <td class="td-label">Jenis Kelamin</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    <?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?>
                </td>
            </tr>
            <tr>
                <td class="td-label">Tempat, Tanggal Lahir</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    <?= esc($pendaftar['tempat_lahir']) ?>, <?= date('d F Y', strtotime($pendaftar['tanggal_lahir'])) ?>
                    (Usia: <?= date('Y') - date('Y', strtotime($pendaftar['tanggal_lahir'])) ?> Th)
                </td>
            </tr>
            <tr>
                <td class="td-label">Jalur Pendaftaran</td>
                <td class="td-sep">:</td>
                <td class="td-value text-bold"><?= strtoupper($pendaftar['jalur_pendaftaran']) ?></td>
            </tr>
            <tr>
                <td class="td-label">Anak Ke / Jumlah Sdr</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    <?= esc($pendaftar['anak_ke'] ?? '-') ?> dari <?= esc($pendaftar['jumlah_saudara'] ?? '-') ?> bersaudara
                </td>
            </tr>
            <tr>
                <td class="td-label">Nomor Handphone</td>
                <td class="td-sep">:</td>
                <td class="td-value"><?= esc($pendaftar['no_hp'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="td-label">Minat/Hobi/Cita-cita</td>
                <td class="td-sep">:</td>
                <td class="td-value">
                    <?= esc($pendaftar['hobi'] ?? '-') ?> / <?= esc($pendaftar['cita_cita'] ?? '-') ?>
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
                    <td class="td-label">Alamat Lengkap</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($alamat['alamat'] ?? '-') ?><br>
                        Desa <?= esc($alamat['desa'] ?? '-') ?>, Kec. <?= esc($alamat['kecamatan'] ?? '-') ?><br>
                        <?= esc($alamat['kabupaten'] ?? '-') ?>, Prov. <?= esc($alamat['provinsi'] ?? '-') ?>
                        <?= !empty($alamat['kode_pos']) ? ' - ' . esc($alamat['kode_pos']) : '' ?>
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Jarak ke Sekolah</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($alamat['jarak_ke_sekolah'] ?? '-') ?> KM
                        (Transportasi: <?= esc($alamat['transportasi'] ?? '-') ?>)
                    </td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <div class="section-container">
        <div class="section-header">C. DATA ORANG TUA / WALI</div>

        <?php if ($ayah): ?>
            <div class="sub-header">DATA AYAH</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Ayah</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($ayah['nama_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">NIK</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ayah['nik_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Pekerjaan & Penghasilan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($ayah['pekerjaan_ayah'] ?? '-') ?> (<?= esc($ayah['penghasilan_ayah'] ?? '-') ?>)
                    </td>
                </tr>
                <tr>
                    <td class="td-label">No. Handphone</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ayah['hp_ayah'] ?? '-') ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php if ($ibu): ?>
            <div class="sub-header">DATA IBU</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Ibu</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($ibu['nama_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">NIK</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ibu['nik_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">Pekerjaan & Penghasilan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($ibu['pekerjaan_ibu'] ?? '-') ?> (<?= esc($ibu['penghasilan_ibu'] ?? '-') ?>)
                    </td>
                </tr>
                <tr>
                    <td class="td-label">No. Handphone</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($ibu['hp_ibu'] ?? '-') ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php if ($wali && !empty($wali['nama_wali'])): ?>
            <div class="sub-header">DATA WALI</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Wali</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($wali['nama_wali']) ?></td>
                </tr>
                <tr>
                    <td class="td-label">Hubungan</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($wali['hubungan_wali'] ?? '-') ?></td>
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
            <div class="section-header">D. DATA ASAL SEKOLAH</div>
            <table class="table-data">
                <tr>
                    <td class="td-label">Nama Sekolah</td>
                    <td class="td-sep">:</td>
                    <td class="td-value text-bold"><?= esc($sekolah['nama_asal_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="td-label">NPSN / Jenjang</td>
                    <td class="td-sep">:</td>
                    <td class="td-value">
                        <?= esc($sekolah['npsn'] ?? '-') ?> (<?= esc($sekolah['jenjang_sekolah'] ?? '-') ?>)
                    </td>
                </tr>
                <tr>
                    <td class="td-label">Lokasi</td>
                    <td class="td-sep">:</td>
                    <td class="td-value"><?= esc($sekolah['lokasi_sekolah'] ?? '-') ?></td>
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
        <br><br>
        Dicetak otomatis oleh Sistem Informasi PSB Pesantren Persis 31 Banjaran<br>
        pada tanggal <?= date('d F Y, H:i') ?> WIB
    </div>

</body>

</html>