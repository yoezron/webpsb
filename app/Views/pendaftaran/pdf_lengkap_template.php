<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Lengkap Pendaftaran - <?= esc($pendaftar['nomor_pendaftaran']) ?></title>
    <style>
        /* Reset & Page Setup */
        @page {
            size: A4;
            margin: 2cm;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 10pt;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
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

        /* Header Section using Table for maximum PDF compatibility */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #1AB34A;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header-logo {
            width: 80px;
            /* Sesuaikan ukuran logo */
            vertical-align: middle;
        }

        .header-text {
            vertical-align: middle;
            padding-left: 15px;
        }

        .header-title {
            font-size: 18pt;
            font-weight: bold;
            color: #1AB34A;
            margin: 0;
        }

        .header-subtitle {
            font-size: 12pt;
            font-weight: bold;
            margin: 5px 0 0;
            color: #555;
        }

        .header-address {
            font-size: 9pt;
            color: #777;
            margin: 2px 0 0;
        }

        /* Section Styling */
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
            /* Mencegah tabel terpotong di tengah baris */
        }

        .section-header {
            background-color: #1AB34A;
            color: #fff;
            padding: 6px 10px;
            font-size: 10pt;
            font-weight: bold;
            border-radius: 4px 4px 0 0;
            /* Sedikit melengkung di atas */
            margin-bottom: 0;
        }

        /* Data Tables */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }

        .data-table td {
            padding: 6px 10px;
            vertical-align: top;
            /* Agar teks panjang tidak merusak alignment */
            border-bottom: 1px solid #eee;
            border-left: 1px solid #eee;
            border-right: 1px solid #eee;
        }

        /* Column Widths - Menjaga agar titik dua sejajar di semua tabel */
        .col-label {
            width: 35%;
            color: #555;
            font-weight: 600;
            background-color: #fcfcfc;
        }

        .col-separator {
            width: 2%;
            text-align: center;
        }

        .col-value {
            width: 63%;
            color: #000;
        }

        /* Zebra Striping - Memudahkan pembacaan */
        .data-table tr:nth-child(even) td {
            background-color: #f9fdfa;
            /* Hijau sangat muda */
        }

        /* Status Badges (Optional styling for empty data) */
        .text-muted {
            color: #999;
            font-style: italic;
        }

        /* Signature Section */
        .signature-section {
            margin-top: 40px;
            width: 100%;
            page-break-inside: avoid;
        }

        .sign-box {
            width: 40%;
            float: right;
            text-align: center;
        }

        .sign-name {
            margin-top: 70px;
            border-bottom: 1px solid #333;
            font-weight: bold;
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8pt;
            color: #888;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>

<body>

    <table class="header-table">
        <tr>
            <td class="header-text">
                <h1 class="header-title">DATA PENDAFTARAN</h1>
                <h2 class="header-subtitle">PSB PESANTREN PERSIS 31 BANJARAN</h2>
                <p class="header-address">Formulir Biodata Santri Baru Tahun Ajaran <?= date('Y') ?></p>
            </td>
            <td width="30%" style="text-align: right; vertical-align: bottom;">
                <div style="border: 2px solid #1AB34A; padding: 5px; display: inline-block; border-radius: 4px;">
                    <small style="color: #1AB34A; font-weight:bold;">NO. DAFTAR</small><br>
                    <span style="font-size: 14pt; font-weight: bold;"><?= esc($pendaftar['nomor_pendaftaran']) ?></span>
                </div>
            </td>
        </tr>
    </table>

    <div class="section">
        <div class="section-header">A. DATA PRIBADI SANTRI</div>
        <table class="data-table">
            <tr>
                <td class="col-label">NISN</td>
                <td class="col-separator">:</td>
                <td class="col-value"><?= esc($pendaftar['nisn'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="col-label">NIK</td>
                <td class="col-separator">:</td>
                <td class="col-value"><?= esc($pendaftar['nik'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="col-label">Nama Lengkap</td>
                <td class="col-separator">:</td>
                <td class="col-value text-bold uppercase"><?= esc($pendaftar['nama_lengkap']) ?></td>
            </tr>
            <tr>
                <td class="col-label">Jenis Kelamin</td>
                <td class="col-separator">:</td>
                <td class="col-value"><?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
            </tr>
            <tr>
                <td class="col-label">Tempat, Tanggal Lahir</td>
                <td class="col-separator">:</td>
                <td class="col-value">
                    <?= esc($pendaftar['tempat_lahir']) ?>, <?= date('d F Y', strtotime($pendaftar['tanggal_lahir'])) ?>
                </td>
            </tr>
            <tr>
                <td class="col-label">Jalur Pendaftaran</td>
                <td class="col-separator">:</td>
                <td class="col-value"><?= ucfirst($pendaftar['jalur_pendaftaran']) ?></td>
            </tr>
            <tr>
                <td class="col-label">Status Keluarga</td>
                <td class="col-separator">:</td>
                <td class="col-value"><?= esc($pendaftar['status_keluarga'] ?? '-') ?></td>
            </tr>
            <tr>
                <td class="col-label">Anak Ke / Jml Saudara</td>
                <td class="col-separator">:</td>
                <td class="col-value"><?= esc($pendaftar['anak_ke'] ?? '-') ?> dari <?= esc($pendaftar['jumlah_saudara'] ?? '-') ?> bersaudara</td>
            </tr>
            <tr>
                <td class="col-label">No. Handphone</td>
                <td class="col-separator">:</td>
                <td class="col-value"><?= esc($pendaftar['no_hp'] ?? '-') ?></td>
            </tr>
        </table>
    </div>

    <?php if ($alamat): ?>
        <div class="section">
            <div class="section-header">B. DATA TEMPAT TINGGAL</div>
            <table class="data-table">
                <tr>
                    <td class="col-label">Nomor KK</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($alamat['nomor_kk'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">Alamat Lengkap</td>
                    <td class="col-separator">:</td>
                    <td class="col-value">
                        <?= esc($alamat['alamat'] ?? '-') ?><br>
                        <small>
                            Desa/Kel. <?= esc($alamat['desa'] ?? '-') ?>,
                            Kec. <?= esc($alamat['kecamatan'] ?? '-') ?><br>
                            <?= esc($alamat['kabupaten'] ?? '-') ?> - <?= esc($alamat['provinsi'] ?? '-') ?>
                        </small>
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Kode Pos</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($alamat['kode_pos'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">Jarak & Waktu Tempuh</td>
                    <td class="col-separator">:</td>
                    <td class="col-value">
                        <?= esc($alamat['jarak_ke_sekolah'] ?? '-') ?> KM
                        (<?= esc($alamat['waktu_tempuh'] ?? '-') ?>)
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Transportasi</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($alamat['transportasi'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <div class="section">
        <div class="section-header">C. DATA ORANG TUA</div>

        <?php if ($ayah): ?>
            <div style="margin-bottom: 5px; font-weight:bold; padding: 5px 0; border-bottom: 2px solid #eee;">Data Ayah</div>
            <table class="data-table" style="margin-bottom: 15px;">
                <tr>
                    <td class="col-label">Nama Ayah</td>
                    <td class="col-separator">:</td>
                    <td class="col-value text-bold"><?= esc($ayah['nama_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">NIK / Pendidikan</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ayah['nik_ayah'] ?? '-') ?> / <?= esc($ayah['pendidikan_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">Pekerjaan</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ayah['pekerjaan_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">Penghasilan</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ayah['penghasilan_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">No. Handphone</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ayah['hp_ayah'] ?? '-') ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php if ($ibu): ?>
            <div style="margin-bottom: 5px; font-weight:bold; padding: 5px 0; border-bottom: 2px solid #eee;">Data Ibu</div>
            <table class="data-table">
                <tr>
                    <td class="col-label">Nama Ibu</td>
                    <td class="col-separator">:</td>
                    <td class="col-value text-bold"><?= esc($ibu['nama_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">NIK / Pendidikan</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ibu['nik_ibu'] ?? '-') ?> / <?= esc($ibu['pendidikan_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">Pekerjaan</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ibu['pekerjaan_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">Penghasilan</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ibu['penghasilan_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">No. Handphone</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($ibu['hp_ibu'] ?? '-') ?></td>
                </tr>
            </table>
        <?php endif; ?>
    </div>

    <?php if ($sekolah): ?>
        <div class="section">
            <div class="section-header">D. DATA ASAL SEKOLAH</div>
            <table class="data-table">
                <tr>
                    <td class="col-label">Nama Sekolah</td>
                    <td class="col-separator">:</td>
                    <td class="col-value text-bold">
                        <?= esc($sekolah['nama_asal_sekolah'] ?? '-') ?>
                        <span style="font-weight:normal; font-size:9pt;">(NPSN: <?= esc($sekolah['npsn'] ?? '-') ?>)</span>
                    </td>
                </tr>
                <tr>
                    <td class="col-label">Jenjang / Status</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($sekolah['jenjang_sekolah'] ?? '-') ?> / <?= esc($sekolah['status_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td class="col-label">Lokasi</td>
                    <td class="col-separator">:</td>
                    <td class="col-value"><?= esc($sekolah['lokasi_sekolah'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
    <?php endif; ?>

    <div class="signature-section">
        <div class="sign-box">
            <p>Banjaran, <?= date('d F Y') ?></p>
            <p>Orang Tua / Wali Santri</p>
            <div class="sign-name">
                ( ..................................................... )
            </div>
        </div>
        <div style="clear: both;"></div>
    </div>

    <div class="footer">
        Dicetak otomatis oleh Sistem PSB Persis 31 Banjaran pada <?= date('d/m/Y H:i') ?> WIB<br>
        Dokumen ini sah dan dapat digunakan sebagai bukti pendaftaran.
    </div>

</body>

</html>