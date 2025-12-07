<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - PSB Persis 31</title>
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">

    <style>
        :root {
            --primary-green: #1AB34A;
            --dark-green: #158a3a;
        }

        body {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .error-container {
            text-align: center;
            color: white;
            padding: 40px;
        }

        .error-icon {
            font-size: 120px;
            margin-bottom: 30px;
            animation: shake 0.5s;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-10px); }
            20%, 40%, 60%, 80% { transform: translateX(10px); }
        }

        .error-title {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .error-message {
            font-size: 1.3rem;
            margin-bottom: 40px;
            opacity: 0.95;
        }

        .btn-home {
            background: white;
            color: var(--primary-green);
            padding: 15px 40px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3);
            color: var(--dark-green);
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-icon">
            <i class="icofont-ban"></i>
        </div>
        <h1 class="error-title">Akses Ditolak</h1>
        <p class="error-message">
            <?= esc($message ?? 'Anda tidak memiliki akses ke halaman ini.') ?>
        </p>
        <a href="<?= base_url('/dashboard') ?>" class="btn-home">
            <i class="icofont-home me-2"></i> Kembali ke Dashboard
        </a>
    </div>
</body>

</html>
