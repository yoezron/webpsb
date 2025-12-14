<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- SEO Meta Tags -->
    <title><?= esc($title) ?></title>
    <meta name="description" content="<?= esc($meta_description) ?>">
    <meta name="keywords" content="<?= esc($meta_keywords) ?>">
    <meta name="robots" content="index, follow">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/logo/favicon.ico') ?>">

    <!-- CSS -->
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
            background: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar-custom {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: white !important;
            font-weight: 600;
        }

        .navbar-custom .nav-link:hover {
            color: var(--secondary-yellow) !important;
        }

        .hero-section {
            background: linear-gradient(135deg, var(--primary-green) 0%, var(--dark-green) 100%);
            color: white;
            padding: 60px 0;
            margin-bottom: 40px;
        }

        .hero-section h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .hero-section p {
            font-size: 1.2rem;
            opacity: 0.95;
        }

        .chart-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .chart-card h3 {
            font-size: 1.25rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark-green);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-card h3 i {
            color: var(--primary-green);
        }

        .chart-container {
            position: relative;
            height: 300px;
            margin-bottom: 10px;
        }

        .table-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
            margin-top: 30px;
        }

        .table-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--dark-green);
        }

        .filter-buttons {
            margin-bottom: 20px;
        }

        .filter-buttons .btn {
            margin-right: 10px;
            margin-bottom: 10px;
            border-radius: 25px;
            padding: 8px 20px;
            font-weight: 600;
        }

        .filter-buttons .btn-primary {
            background: var(--primary-green);
            border-color: var(--primary-green);
        }

        .filter-buttons .btn-primary:hover {
            background: var(--dark-green);
            border-color: var(--dark-green);
        }

        .filter-buttons .btn-outline-primary {
            color: var(--primary-green);
            border-color: var(--primary-green);
        }

        .filter-buttons .btn-outline-primary:hover {
            background: var(--primary-green);
            border-color: var(--primary-green);
            color: white;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: var(--primary-green);
            color: white;
            border: none;
            font-weight: 600;
            padding: 15px;
        }

        .table tbody td {
            padding: 12px 15px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background: #f8f9fa;
        }

        .loading-spinner {
            display: none;
            text-align: center;
            padding: 20px;
        }

        .loading-spinner.active {
            display: block;
        }

        .badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
        }

        .footer {
            background: #2c3e50;
            color: white;
            padding: 30px 0;
            margin-top: 60px;
            text-align: center;
        }

        @media (max-width: 768px) {
            .hero-section h1 {
                font-size: 2rem;
            }

            .chart-container {
                height: 250px;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-custom">
        <div class="container">
            <a class="navbar-brand" href="<?= base_url('/') ?>">
                <i class="icofont-building-alt"></i> Pesantren Persis 31
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('/') ?>">
                            <i class="icofont-home"></i> Beranda
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="<?= base_url('statistik') ?>">
                            <i class="icofont-chart-bar-graph"></i> Statistik
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('pengumuman') ?>">
                            <i class="icofont-megaphone"></i> Pengumuman
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container">
            <h1><i class="icofont-chart-bar-graph"></i> Statistik Pendaftar</h1>
            <p>Data dan statistik pendaftar santri baru Pesantren Persatuan Islam 31 Banjaran</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="container">
        <div class="row">
            <!-- Chart 1: Jalur Pendaftaran -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-chart-histogram"></i> Jalur Pendaftaran</h3>
                    <div class="chart-container">
                        <canvas id="chartJalur"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 2: Pendaftar per Tanggal -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-chart-line-alt"></i> Pendaftar per Tanggal</h3>
                    <div class="chart-container">
                        <canvas id="chartTanggal"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 3: Usia Pendaftar -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-users-alt-3"></i> Usia Pendaftar</h3>
                    <div class="chart-container">
                        <canvas id="chartUsia"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 4: Asal Desa -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-location-pin"></i> Top 10 Asal Desa</h3>
                    <div class="chart-container">
                        <canvas id="chartDesa"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 5: Jarak ke Sekolah -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-road"></i> Jarak ke Sekolah</h3>
                    <div class="chart-container">
                        <canvas id="chartJarak"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 6: Waktu Tempuh -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-clock-time"></i> Waktu Tempuh</h3>
                    <div class="chart-container">
                        <canvas id="chartWaktu"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 7: Status Asal Sekolah -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-graduate-alt"></i> Status Asal Sekolah</h3>
                    <div class="chart-container">
                        <canvas id="chartSekolah"></canvas>
                    </div>
                </div>
            </div>

            <!-- Chart 8: Penghasilan Keluarga -->
            <div class="col-lg-6 col-md-12">
                <div class="chart-card">
                    <h3><i class="icofont-money"></i> Penghasilan Keluarga</h3>
                    <div class="chart-container">
                        <canvas id="chartPenghasilan"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="table-card">
            <h3><i class="icofont-database"></i> Data Pendaftar</h3>

            <!-- Filter Buttons -->
            <div class="filter-buttons">
                <button class="btn btn-primary filter-btn active" data-jalur="all">Semua</button>
                <button class="btn btn-outline-primary filter-btn" data-jalur="tsanawiyyah">Tsanawiyyah</button>
                <button class="btn btn-outline-primary filter-btn" data-jalur="muallimin">Muallimin</button>
            </div>

            <!-- Loading Spinner -->
            <div class="loading-spinner">
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Memuat data...</p>
            </div>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-striped table-hover" id="dataTable">
                    <thead>
                        <tr>
                            <th>No. Pendaftaran</th>
                            <th>Nama Lengkap</th>
                            <th>Jenis Kelamin</th>
                            <th>Jalur</th>
                            <th>Asal Sekolah</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="container">
            <p class="mb-0">&copy; 2026 Pesantren Persatuan Islam 31 Banjaran. All rights reserved.</p>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url('assets/js/jquery-3.6.0.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // Chart.js default config
        Chart.defaults.font.family = "'Segoe UI', Tahoma, Geneva, Verdana, sans-serif";
        Chart.defaults.color = '#333';

        // Color palette
        const colors = {
            primary: '#1AB34A',
            secondary: '#F3C623',
            danger: '#e74c3c',
            info: '#3498db',
            warning: '#f39c12',
            success: '#27ae60',
            purple: '#9b59b6',
            pink: '#e91e63',
            teal: '#1abc9c',
            orange: '#ff6b6b'
        };

        const chartColors = [
            colors.primary,
            colors.secondary,
            colors.info,
            colors.warning,
            colors.danger,
            colors.success,
            colors.purple,
            colors.pink,
            colors.teal,
            colors.orange
        ];

        // Chart instances
        let charts = {};

        // Load chart data functions
        async function loadJalurChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/jalur') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartJalur').getContext('2d');
                    charts.jalur = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: result.data.values,
                                backgroundColor: [colors.primary, colors.secondary],
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading jalur chart:', error);
            }
        }

        async function loadTanggalChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/tanggal') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartTanggal').getContext('2d');
                    charts.tanggal = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: result.data.values,
                                borderColor: colors.primary,
                                backgroundColor: colors.primary + '20',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading tanggal chart:', error);
            }
        }

        async function loadUsiaChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/usia') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartUsia').getContext('2d');
                    charts.usia = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                data: result.data.values,
                                backgroundColor: chartColors
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading usia chart:', error);
            }
        }

        async function loadDesaChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/desa') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartDesa').getContext('2d');
                    charts.desa = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: result.data.values,
                                backgroundColor: colors.info,
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: 'y',
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                x: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading desa chart:', error);
            }
        }

        async function loadJarakChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/jarak') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartJarak').getContext('2d');
                    charts.jarak = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                data: result.data.values,
                                backgroundColor: chartColors.slice(0, 4)
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading jarak chart:', error);
            }
        }

        async function loadWaktuChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/waktu') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartWaktu').getContext('2d');
                    charts.waktu = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                label: 'Jumlah Pendaftar',
                                data: result.data.values,
                                backgroundColor: colors.warning,
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading waktu chart:', error);
            }
        }

        async function loadSekolahChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/sekolah') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartSekolah').getContext('2d');
                    charts.sekolah = new Chart(ctx, {
                        type: 'doughnut',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                data: result.data.values,
                                backgroundColor: chartColors
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading sekolah chart:', error);
            }
        }

        async function loadPenghasilanChart() {
            try {
                const response = await fetch('<?= base_url('api/statistik/penghasilan') ?>');
                const result = await response.json();

                if (result.success) {
                    const ctx = document.getElementById('chartPenghasilan').getContext('2d');
                    charts.penghasilan = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: result.data.labels,
                            datasets: [{
                                label: 'Jumlah Keluarga',
                                data: result.data.values,
                                backgroundColor: colors.success,
                                borderRadius: 8
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error loading penghasilan chart:', error);
            }
        }

        async function loadTableData(jalur = 'all') {
            try {
                const tableBody = document.getElementById('tableBody');
                const loadingSpinner = document.querySelector('.loading-spinner');

                // Show loading
                loadingSpinner.classList.add('active');
                tableBody.innerHTML = '';

                const response = await fetch('<?= base_url('api/statistik/table') ?>?jalur=' + jalur);
                const result = await response.json();

                // Hide loading
                loadingSpinner.classList.remove('active');

                if (result.success) {
                    if (result.data.length === 0) {
                        tableBody.innerHTML = '<tr><td colspan="5" class="text-center">Tidak ada data</td></tr>';
                    } else {
                        result.data.forEach(row => {
                            const tr = document.createElement('tr');
                            tr.innerHTML = `
                                <td>${row.nomor_pendaftaran}</td>
                                <td>${row.nama_lengkap}</td>
                                <td>
                                    <span class="badge ${row.jenis_kelamin === 'Laki-laki' ? 'bg-primary' : 'bg-danger'}">
                                        ${row.jenis_kelamin}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        ${row.jalur_pendaftaran}
                                    </span>
                                </td>
                                <td>${row.asal_sekolah}</td>
                            `;
                            tableBody.appendChild(tr);
                        });
                    }
                }
            } catch (error) {
                console.error('Error loading table data:', error);
                const tableBody = document.getElementById('tableBody');
                tableBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Error loading data</td></tr>';
            }
        }

        // Filter buttons event
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Update active button
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('btn-primary', 'active');
                    b.classList.add('btn-outline-primary');
                });
                this.classList.remove('btn-outline-primary');
                this.classList.add('btn-primary', 'active');

                // Load table data
                loadTableData(this.dataset.jalur);
            });
        });

        // Initialize all charts and table on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Load all charts
            loadJalurChart();
            loadTanggalChart();
            loadUsiaChart();
            loadDesaChart();
            loadJarakChart();
            loadWaktuChart();
            loadSekolahChart();
            loadPenghasilanChart();

            // Load table data
            loadTableData('all');
        });
    </script>
</body>

</html>
