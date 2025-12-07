<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Lengkap Pendaftaran - <?= esc($pendaftar['nomor_pendaftaran']) ?></title>
    <style>
        @page {
            size: A4;
            margin: 10mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #333;
        }

        .container {
            max-width: 100%;
        }

        .header {
            text-align: center;
            background: #1AB34A;
            padding: 15px;
            color: white;
            margin-bottom: 15px;
        }

        .header h1 {
            font-size: 16pt;
            margin-bottom: 5px;
        }

        .header h2 {
            font-size: 12pt;
            font-weight: normal;
        }

        .section {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #1AB34A;
            color: white;
            padding: 8px 10px;
            font-size: 11pt;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table td {
            padding: 5px 8px;
            border-bottom: 1px solid #ddd;
        }

        .data-table td:first-child {
            width: 40%;
            font-weight: bold;
            color: #555;
        }

        .data-table td:nth-child(2) {
            width: 5%;
            text-align: center;
        }

        .data-table td:last-child {
            width: 55%;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8pt;
            color: #666;
            border-top: 2px solid #1AB34A;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>DATA PENDAFTARAN LENGKAP</h1>
            <h2>PSB PERSIS 31 CIAMIS</h2>
        </div>

        <!-- Data Pendaftar -->
        <div class="section">
            <div class="section-title">DATA PENDAFTAR</div>
            <table class="data-table">
                <tr>
                    <td>Nomor Pendaftaran</td>
                    <td>:</td>
                    <td><strong><?= esc($pendaftar['nomor_pendaftaran']) ?></strong></td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td>:</td>
                    <td><?= esc($pendaftar['nisn'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td><?= esc($pendaftar['nik'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><?= esc($pendaftar['nama_lengkap']) ?></td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>:</td>
                    <td><?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>:</td>
                    <td><?= esc($pendaftar['tempat_lahir']) ?>, <?= date('d F Y', strtotime($pendaftar['tanggal_lahir'])) ?></td>
                </tr>
                <tr>
                    <td>Jalur Pendaftaran</td>
                    <td>:</td>
                    <td><?= ucfirst($pendaftar['jalur_pendaftaran']) ?></td>
                </tr>
                <tr>
                    <td>Status Keluarga</td>
                    <td>:</td>
                    <td><?= esc($pendaftar['status_keluarga'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Anak Ke / Jumlah Saudara</td>
                    <td>:</td>
                    <td><?= esc($pendaftar['anak_ke'] ?? '-') ?> / <?= esc($pendaftar['jumlah_saudara'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>:</td>
                    <td><?= esc($pendaftar['no_hp'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Tanggal Pendaftaran</td>
                    <td>:</td>
                    <td><?= date('d F Y H:i', strtotime($pendaftar['tanggal_daftar'])) ?> WIB</td>
                </tr>
            </table>
        </div>

        <!-- Data Alamat -->
        <?php if ($alamat): ?>
        <div class="section">
            <div class="section-title">DATA ALAMAT</div>
            <table class="data-table">
                <tr>
                    <td>Nomor KK</td>
                    <td>:</td>
                    <td><?= esc($alamat['nomor_kk'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Jenis Tempat Tinggal</td>
                    <td>:</td>
                    <td><?= esc($alamat['jenis_tempat_tinggal'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Alamat Lengkap</td>
                    <td>:</td>
                    <td><?= esc($alamat['alamat'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Desa / Kelurahan</td>
                    <td>:</td>
                    <td><?= esc($alamat['desa'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Kecamatan</td>
                    <td>:</td>
                    <td><?= esc($alamat['kecamatan'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Kabupaten</td>
                    <td>:</td>
                    <td><?= esc($alamat['kabupaten'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Provinsi</td>
                    <td>:</td>
                    <td><?= esc($alamat['provinsi'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Kode Pos</td>
                    <td>:</td>
                    <td><?= esc($alamat['kode_pos'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Jarak ke Sekolah</td>
                    <td>:</td>
                    <td><?= esc($alamat['jarak_ke_sekolah'] ?? '-') ?> KM</td>
                </tr>
                <tr>
                    <td>Waktu Tempuh</td>
                    <td>:</td>
                    <td><?= esc($alamat['waktu_tempuh'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Transportasi</td>
                    <td>:</td>
                    <td><?= esc($alamat['transportasi'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>:</td>
                    <td><?= esc($alamat['email'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Data Ayah -->
        <?php if ($ayah): ?>
        <div class="section">
            <div class="section-title">DATA AYAH</div>
            <table class="data-table">
                <tr>
                    <td>Nama Ayah</td>
                    <td>:</td>
                    <td><?= esc($ayah['nama_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td><?= esc($ayah['nik_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Pendidikan</td>
                    <td>:</td>
                    <td><?= esc($ayah['pendidikan_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><?= esc($ayah['pekerjaan_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Penghasilan</td>
                    <td>:</td>
                    <td><?= esc($ayah['penghasilan_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>:</td>
                    <td><?= esc($ayah['hp_ayah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><?= esc($ayah['status_ayah'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Data Ibu -->
        <?php if ($ibu): ?>
        <div class="section">
            <div class="section-title">DATA IBU</div>
            <table class="data-table">
                <tr>
                    <td>Nama Ibu</td>
                    <td>:</td>
                    <td><?= esc($ibu['nama_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td><?= esc($ibu['nik_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Pendidikan</td>
                    <td>:</td>
                    <td><?= esc($ibu['pendidikan_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><?= esc($ibu['pekerjaan_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Penghasilan</td>
                    <td>:</td>
                    <td><?= esc($ibu['penghasilan_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>:</td>
                    <td><?= esc($ibu['hp_ibu'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><?= esc($ibu['status_ibu'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Data Wali -->
        <?php if ($wali): ?>
        <div class="section">
            <div class="section-title">DATA WALI</div>
            <table class="data-table">
                <tr>
                    <td>Nama Wali</td>
                    <td>:</td>
                    <td><?= esc($wali['nama_wali'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td>:</td>
                    <td><?= esc($wali['nik_wali'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Pendidikan</td>
                    <td>:</td>
                    <td><?= esc($wali['pendidikan_wali'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Pekerjaan</td>
                    <td>:</td>
                    <td><?= esc($wali['pekerjaan_wali'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Penghasilan</td>
                    <td>:</td>
                    <td><?= esc($wali['penghasilan_wali'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>:</td>
                    <td><?= esc($wali['hp_wali'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Data Bansos -->
        <?php if ($bansos): ?>
        <div class="section">
            <div class="section-title">DATA BANTUAN SOSIAL</div>
            <table class="data-table">
                <tr>
                    <td>No. KKS (Kartu Keluarga Sejahtera)</td>
                    <td>:</td>
                    <td><?= esc($bansos['no_kks'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>No. PKH (Program Keluarga Harapan)</td>
                    <td>:</td>
                    <td><?= esc($bansos['no_pkh'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>No. KIP (Kartu Indonesia Pintar)</td>
                    <td>:</td>
                    <td><?= esc($bansos['no_kip'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Data Asal Sekolah -->
        <?php if ($sekolah): ?>
        <div class="section">
            <div class="section-title">DATA ASAL SEKOLAH</div>
            <table class="data-table">
                <tr>
                    <td>NPSN</td>
                    <td>:</td>
                    <td><?= esc($sekolah['npsn'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Nama Sekolah</td>
                    <td>:</td>
                    <td><?= esc($sekolah['nama_asal_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Jenjang</td>
                    <td>:</td>
                    <td><?= esc($sekolah['jenjang_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td>:</td>
                    <td><?= esc($sekolah['status_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Lokasi</td>
                    <td>:</td>
                    <td><?= esc($sekolah['lokasi_sekolah'] ?? '-') ?></td>
                </tr>
                <tr>
                    <td>Asal Jenjang</td>
                    <td>:</td>
                    <td><?= esc($sekolah['asal_jenjang'] ?? '-') ?></td>
                </tr>
            </table>
        </div>
        <?php endif; ?>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak secara otomatis oleh Sistem PSB Persis 31 Ciamis</p>
            <p>Tanggal Cetak: <?= date('d F Y H:i:s') ?> WIB</p>
        </div>
    </div>
</body>
</html>
