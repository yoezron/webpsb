<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bukti Pendaftaran - <?= esc($pendaftar['nomor_pendaftaran']) ?></title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 3px solid #1AB34A;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #1AB34A;
            padding-bottom: 20px;
            margin-bottom: 30px;
            position: relative;
        }

        .header-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 15px;
        }

        .header-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            color: #1AB34A;
            font-size: 24pt;
            margin-bottom: 5px;
        }

        .header h2 {
            color: #158a3a;
            font-size: 18pt;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 11pt;
            color: #666;
        }

        .nomor-pendaftaran {
            background: #e8f5e9;
            border: 2px dashed #1AB34A;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
            border-radius: 10px;
        }

        .nomor-pendaftaran .label {
            font-size: 11pt;
            color: #666;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .nomor-pendaftaran .value {
            font-size: 28pt;
            font-weight: bold;
            color: #158a3a;
            letter-spacing: 3px;
        }

        .data-section {
            margin: 30px 0;
        }

        .data-section h3 {
            background: #1AB34A;
            color: white;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        table td:first-child {
            font-weight: bold;
            width: 40%;
            color: #666;
        }

        table td:last-child {
            width: 60%;
        }

        .footer {
            margin-top: 50px;
            border-top: 2px solid #1AB34A;
            padding-top: 20px;
            text-align: center;
            font-size: 10pt;
            color: #666;
        }

        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature-box {
            text-align: center;
            width: 45%;
        }

        .signature-box .label {
            margin-bottom: 80px;
            font-weight: bold;
        }

        .signature-box .name {
            border-top: 1px solid #333;
            padding-top: 5px;
            display: inline-block;
            min-width: 200px;
        }

        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            font-size: 10pt;
        }

        .note strong {
            color: #856404;
        }

        @media print {
            .container {
                border: 2px solid #1AB34A;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-logo">
                <img src="<?= base_url('assets/images/logo/01.png') ?>" alt="Logo Pesantren Persatuan Islam 31 Banjaran">
            </div>
            <h1>PESANTREN PERSATUAN ISLAM 31</h1>
            <h2>BANJARAN</h2>
            <p>Jl. Raya Banjaran No. 123, Bandung, Jawa Barat 40377</p>
            <p>Telp: (022) 1234567 | Email: info@persis31.com | Website: www.persis31.com</p>
        </div>

        <h2 style="text-align: center; color: #1AB34A; margin: 20px 0;">KARTU BUKTI PENDAFTARAN</h2>
        <p style="text-align: center; margin-bottom: 20px;">Tahun Ajaran <?= date('Y') ?>/<?= date('Y') + 1 ?></p>

        <!-- Nomor Pendaftaran -->
        <div class="nomor-pendaftaran">
            <div class="label">Nomor Pendaftaran</div>
            <div class="value"><?= esc($pendaftar['nomor_pendaftaran']) ?></div>
        </div>

        <!-- Data Pendaftar -->
        <div class="data-section">
            <h3>Data Calon Santri</h3>
            <table>
                <tr>
                    <td>Nama Lengkap</td>
                    <td><?= esc($pendaftar['nama_lengkap']) ?></td>
                </tr>
                <tr>
                    <td>NISN</td>
                    <td><?= esc($pendaftar['nisn']) ?: '-' ?></td>
                </tr>
                <tr>
                    <td>NIK</td>
                    <td><?= esc($pendaftar['nik']) ?: '-' ?></td>
                </tr>
                <tr>
                    <td>Tempat, Tanggal Lahir</td>
                    <td>
                        <?= esc($pendaftar['tempat_lahir']) ?>,
                        <?= $pendaftar['tanggal_lahir'] ? date('d F Y', strtotime($pendaftar['tanggal_lahir'])) : '-' ?>
                    </td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td><?= $pendaftar['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></td>
                </tr>
                <tr>
                    <td>Jalur Pendaftaran</td>
                    <td><strong><?= esc($pendaftar['jalur_pendaftaran']) ?></strong></td>
                </tr>
                <?php if (isset($sekolah) && $sekolah): ?>
                <tr>
                    <td>Asal Sekolah</td>
                    <td><?= esc($sekolah['nama_asal_sekolah']) ?></td>
                </tr>
                <tr>
                    <td>NPSN</td>
                    <td><?= esc($sekolah['npsn']) ?: '-' ?></td>
                </tr>
                <?php endif; ?>
                <tr>
                    <td>Tanggal Pendaftaran</td>
                    <td><?= date('d F Y, H:i', strtotime($pendaftar['tanggal_daftar'])) ?> WIB</td>
                </tr>
            </table>
        </div>

        <!-- Note -->
        <div class="note">
            <p><strong>CATATAN PENTING:</strong></p>
            <ul style="margin-left: 20px; margin-top: 10px;">
                <li>Simpan kartu ini dengan baik sebagai bukti pendaftaran</li>
                <li>Bawa kartu ini saat mengikuti tes dan wawancara</li>
                <li>Nomor pendaftaran akan digunakan untuk proses selanjutnya</li>
                <li>Pantau terus pengumuman di website resmi kami</li>
            </ul>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="label">Pendaftar,</div>
                <div class="name"><?= esc($pendaftar['nama_lengkap']) ?></div>
            </div>
            <div class="signature-box">
                <div class="label">Panitia PSB,</div>
                <div class="name">(__________________)</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Dokumen ini dicetak otomatis oleh sistem dan sah tanpa tanda tangan</p>
            <p>Dicetak pada: <?= date('d F Y, H:i:s') ?> WIB</p>
            <p style="margin-top: 10px; color: #1AB34A; font-weight: bold;">
                Pesantren Persatuan Islam 31 Banjaran
            </p>
        </div>
    </div>
</body>
</html>
