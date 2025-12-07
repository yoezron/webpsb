<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Bukti Pendaftaran - <?= esc($pendaftar['nomor_pendaftaran']) ?></title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            font-size: 11pt;
            line-height: 1.5;
            color: #333;
        }

        .container {
            max-width: 100%;
            margin: 0 auto;
            padding: 15px;
            border: 4px solid #1AB34A;
            border-radius: 8px;
            background: #ffffff;
        }

        .header {
            text-align: center;
            background: #1AB34A;
            padding: 20px 20px 25px 20px;
            margin: -15px -15px 0 -15px;
            border-radius: 8px 8px 0 0;
            color: white;
            position: relative;
        }

        .header-logo {
            width: 100px;
            height: 100px;
            margin: 0 auto 15px;
        }

        .header-logo img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .header h1 {
            color: #ffffff;
            font-size: 20pt;
            margin: 0 0 4px 0;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        .header h2 {
            color: #ffffff;
            font-size: 16pt;
            margin: 0 0 12px 0;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .header p {
            font-size: 9pt;
            color: #ffffff;
            line-height: 1.5;
            margin: 2px 0;
        }

        .card-title {
            text-align: center;
            background: #F3C623;
            color: #333;
            padding: 15px 12px;
            margin: 0 -15px 20px -15px;
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        .nomor-qr-container {
            display: table;
            width: 100%;
            margin: 20px 0;
        }

        .nomor-section {
            display: table-cell;
            width: 70%;
            vertical-align: middle;
            padding-right: 15px;
        }

        .qr-section {
            display: table-cell;
            width: 30%;
            text-align: center;
            vertical-align: middle;
        }

        .nomor-pendaftaran {
            background: #e8f5e9;
            border: 3px dashed #1AB34A;
            padding: 18px 20px;
            text-align: center;
            border-radius: 8px;
        }

        .nomor-pendaftaran .label {
            font-size: 9pt;
            color: #555;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .nomor-pendaftaran .value {
            font-size: 26pt;
            font-weight: bold;
            color: #1AB34A;
            letter-spacing: 1px;
        }

        .qr-code {
            border: 3px solid #1AB34A;
            padding: 10px;
            background: white;
            border-radius: 8px;
            display: inline-block;
        }

        .qr-code img {
            display: block;
            width: 120px;
            height: 120px;
        }

        .qr-label {
            font-size: 8pt;
            color: #666;
            margin-top: 8px;
            text-align: center;
            font-style: italic;
        }

        .data-section {
            margin: 25px 0 20px 0;
        }

        .data-section h3 {
            background: #1AB34A;
            color: white;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
            font-size: 12pt;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            background: white;
        }

        table td {
            padding: 10px 12px;
            border-bottom: 1px solid #e8f5e9;
        }

        table tr:last-child td {
            border-bottom: none;
        }

        table tr:nth-child(odd) {
            background: #f9fef9;
        }

        table td:first-child {
            font-weight: bold;
            width: 42%;
            color: #1AB34A;
            font-size: 10pt;
        }

        table td:last-child {
            width: 58%;
            color: #333;
            font-size: 10pt;
        }

        .footer {
            margin-top: 25px;
            border-top: 3px solid #F3C623;
            padding-top: 12px;
            text-align: center;
            font-size: 8pt;
            color: #666;
            line-height: 1.6;
        }

        .footer p {
            margin: 4px 0;
        }

        .signature-section {
            margin-top: 25px;
            margin-bottom: 20px;
            display: table;
            width: 100%;
        }

        .signature-box {
            display: table-cell;
            text-align: center;
            width: 50%;
            padding: 10px;
        }

        .signature-box .label {
            margin-bottom: 55px;
            font-weight: bold;
            color: #1AB34A;
            font-size: 10pt;
        }

        .signature-box .name {
            border-top: 2px solid #333;
            padding-top: 8px;
            display: inline-block;
            min-width: 160px;
            font-size: 10pt;
            font-weight: 500;
        }

        .note {
            background: #fffef5;
            border-left: 5px solid #F3C623;
            padding: 15px;
            margin: 20px 0;
            font-size: 9pt;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .note strong {
            color: #856404;
            font-size: 10pt;
            display: block;
            margin-bottom: 8px;
        }

        .note ul {
            margin: 0;
            padding-left: 20px;
        }

        .note li {
            margin-bottom: 6px;
            color: #555;
            line-height: 1.6;
        }

        .watermark {
            position: fixed;
            bottom: 50%;
            left: 50%;
            transform: translate(-50%, 50%) rotate(-45deg);
            font-size: 90pt;
            color: rgba(26, 179, 74, 0.04);
            font-weight: bold;
            z-index: -1;
            pointer-events: none;
            letter-spacing: 8px;
        }

        @media print {
            .container {
                border: 3px solid #1AB34A;
            }
        }
    </style>
</head>

<body>
    <!-- Watermark -->
    <div class="watermark">PERSIS 31</div>

    <div class="container">
        <!-- Header -->
        <div class="header">
            <?php if (!empty($logo)): ?>
            <div class="header-logo">
                <img src="<?= $logo ?>" alt="Logo Pesantren Persatuan Islam 31 Banjaran">
            </div>
            <?php endif; ?>
            <h1>PESANTREN PERSATUAN ISLAM 31</h1>
            <h2>BANJARAN</h2>
            <p>Jl. Pajagalan No. 115, Banjaran, Kabupaten Bandung, Jawa Barat 40377</p>
            <p>Telp: (022) 5940303 | Email: pesantrenpersis31@gmail.com | Website: mapersis31banjaran.sch.id</p>
        </div>

        <!-- Card Title -->
        <div class="card-title">KARTU BUKTI PENDAFTARAN<br>
            <span style="font-size: 11pt; font-weight: normal;">Tahun Ajaran <?= date('Y') ?>/<?= date('Y') + 1 ?></span>
        </div>

        <!-- Nomor Pendaftaran & QR Code -->
        <div class="nomor-qr-container">
            <div class="nomor-section">
                <div class="nomor-pendaftaran">
                    <div class="label">Nomor Pendaftaran</div>
                    <div class="value"><?= esc($pendaftar['nomor_pendaftaran']) ?></div>
                </div>
            </div>
            <?php if (!empty($qrCode)): ?>
                <div class="qr-section">
                    <div class="qr-code">
                        <img src="<?= $qrCode ?>" alt="QR Code">
                    </div>
                    <div class="qr-label">Scan untuk verifikasi</div>
                </div>
            <?php endif; ?>
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
                <div class="label">Ketua Panitia PSB,</div>
                <div class="name">(Hilman Latief, M.Pd.)</div>
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