<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= esc($title) ?></title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= base_url('assets/images/logo/favicon-32x32.png') ?>">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/all.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icofont.min.css') ?>">

    <style>
        :root {
            --primary-green: #1AB34A;
            --secondary-yellow: #F3C623;
            --dark-green: #158a3a;
            --light-yellow: #f5d565;
        }

        body {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 20px;
        }

        .login-container {
            max-width: 450px;
            width: 100%;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-logo {
            width: 100px;
            height: auto;
            margin-bottom: 20px;
            filter: drop-shadow(0 5px 10px rgba(0, 0, 0, 0.2));
        }

        .login-header h2 {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .login-header p {
            font-size: 0.95rem;
            opacity: 0.95;
            margin: 0;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 0.2rem rgba(26, 179, 74, 0.15);
        }

        .input-group-text {
            background: transparent;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 10px 0 0 10px;
            color: #666;
        }

        .input-group .form-control {
            border-left: none;
            border-radius: 0 10px 10px 0;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary-green);
            color: var(--primary-green);
        }

        .btn-login {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            border: none;
            color: white;
            padding: 14px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
            width: 100%;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(26, 179, 74, 0.3);
            background: linear-gradient(135deg, var(--dark-green) 0%, var(--primary-green) 100%);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 12px 15px;
            margin-bottom: 20px;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .form-check-input:checked {
            background-color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .form-check-label {
            font-size: 0.9rem;
            color: #666;
        }

        .back-home {
            text-align: center;
            margin-top: 25px;
            padding-top: 25px;
            border-top: 1px solid #e0e0e0;
        }

        .back-home a {
            color: var(--primary-green);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .back-home a:hover {
            color: var(--dark-green);
        }

        .is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Responsive */
        @media (max-width: 576px) {
            .login-body {
                padding: 30px 20px;
            }

            .login-header {
                padding: 30px 20px;
            }

            .login-header h2 {
                font-size: 1.5rem;
            }

            .login-logo {
                width: 80px;
            }
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="login-card">
            <!-- Login Header -->
            <div class="login-header">
                <img src="<?= base_url('assets/images/logo/01.png') ?>" alt="Logo Persis 31" class="login-logo">
                <h2>Login Panitia PSB</h2>
                <p>Pesantren Persatuan Islam 31 Banjaran</p>
            </div>

            <!-- Login Body -->
            <div class="login-body">

                <!-- Success Message -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <i class="icofont-check-circled me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <!-- Error Message -->
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger">
                        <i class="icofont-warning me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="<?= base_url('login') ?>" method="POST">
                    <?= csrf_field() ?>

                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">
                            <i class="icofont-user me-1"></i> Username
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="icofont-user-alt-4"></i>
                            </span>
                            <input
                                type="text"
                                class="form-control <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>"
                                id="username"
                                name="username"
                                placeholder="Masukkan username"
                                value="<?= old('username') ?>"
                                autofocus
                                required>
                            <?php if (isset($validation) && $validation->hasError('username')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('username') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="icofont-lock me-1"></i> Password
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="icofont-key"></i>
                            </span>
                            <input
                                type="password"
                                class="form-control <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>"
                                id="password"
                                name="password"
                                placeholder="Masukkan password"
                                required>
                            <?php if (isset($validation) && $validation->hasError('password')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('password') ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" value="1">
                            <label class="form-check-label" for="remember">
                                Ingat saya
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-login">
                        <i class="icofont-sign-in me-2"></i> Login
                    </button>

                    <!-- Back to Home -->
                    <div class="back-home">
                        <a href="<?= base_url('/') ?>">
                            <i class="icofont-rounded-left me-1"></i> Kembali ke Beranda
                        </a>
                    </div>
                </form>

            </div>
        </div>

        <!-- Footer Info -->
        <div class="text-center mt-4" style="color: white;">
            <small>
                &copy; <?= date('Y') ?> Pesantren Persatuan Islam 31 Banjaran
            </small>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>

</body>

</html>
