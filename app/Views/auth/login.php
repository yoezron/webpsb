<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= esc($title) ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= base_url('assets/images/logo/favicon-16x16.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/logo/favicon-32x32.png') ?>">

    <!-- Hafsa Template CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/animate.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-green: #e8f5e9;
            --danger-red: #dc3545;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(26, 179, 74, 0.95) 0%, rgba(21, 138, 58, 0.95) 100%),
                url('<?= base_url('assets/images/banner/01.png') ?>') center/cover;
            padding: 20px;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .login-logo {
            width: 100px;
            height: auto;
            margin-bottom: 15px;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.3));
        }

        .login-title {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }

        .login-subtitle {
            color: var(--secondary-yellow);
            font-size: 0.9rem;
            margin-top: 5px;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(26, 179, 74, 0.1);
        }

        .input-group {
            position: relative;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            z-index: 10;
        }

        .input-group .form-control {
            padding-left: 45px;
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border: none;
            color: white;
            padding: 14px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border-radius: 10px;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(26, 179, 74, 0.4);
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
        }

        .btn-login:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .alert {
            border-radius: 10px;
            padding: 15px 20px;
            margin-bottom: 25px;
            border: none;
        }

        .alert-danger {
            background-color: #fef2f2;
            color: #dc2626;
        }

        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a;
        }

        .back-link {
            text-align: center;
            margin-top: 25px;
        }

        .back-link a {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .back-link a:hover {
            color: var(--primary-green);
        }

        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #999;
            cursor: pointer;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--primary-green);
        }

        @media (max-width: 576px) {
            .login-header {
                padding: 30px 20px;
            }

            .login-body {
                padding: 30px 20px;
            }

            .login-logo {
                width: 80px;
            }

            .login-title {
                font-size: 1.3rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-container animate__animated animate__fadeIn">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <img src="<?= base_url('assets/images/logo/01.png') ?>" alt="Logo" class="login-logo">
                <h1 class="login-title">Login Panitia PSB</h1>
                <p class="login-subtitle">Pesantren Persatuan Islam 31 Banjaran</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger animate__animated animate__shakeX">
                        <i class="icofont-warning-alt"></i> <?= $error ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($success)): ?>
                    <div class="alert alert-success animate__animated animate__fadeIn">
                        <i class="icofont-check-circled"></i> <?= esc($success) ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('auth/login') ?>" method="POST" id="loginForm">
                    <?= csrf_field() ?>

                    <!-- Username -->
                    <div class="form-group">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="icofont-user"></i></span>
                            <input type="text"
                                   class="form-control"
                                   id="username"
                                   name="username"
                                   placeholder="Masukkan username"
                                   value="<?= old('username') ?>"
                                   required
                                   autocomplete="username">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-icon"><i class="icofont-lock"></i></span>
                            <input type="password"
                                   class="form-control"
                                   id="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="icofont-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login" id="btnLogin">
                        <i class="icofont-login"></i> Masuk
                    </button>
                </form>

                <!-- Back Link -->
                <div class="back-link">
                    <a href="<?= base_url('/') ?>">
                        <i class="icofont-arrow-left"></i> Kembali ke Halaman Utama
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/jquery.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

    <script>
        // Toggle password visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('icofont-eye');
                toggleIcon.classList.add('icofont-eye-blocked');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('icofont-eye-blocked');
                toggleIcon.classList.add('icofont-eye');
            }
        }

        // Form submit loading state
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnLogin');
            btn.innerHTML = '<i class="icofont-spinner-alt-1 icofont-spin"></i> Memproses...';
            btn.disabled = true;
        });
    </script>

</body>

</html>
