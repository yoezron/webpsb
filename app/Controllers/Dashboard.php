<?php

namespace App\Controllers;

use App\Models\PendaftarModel;
use App\Models\AlamatModel;
use App\Models\AyahModel;
use App\Models\IbuModel;
use App\Models\SekolahModel;

class Dashboard extends BaseController
{
    protected $session;
    protected $pendaftarModel;
    protected $alamatModel;
    protected $ayahModel;
    protected $ibuModel;
    protected $sekolahModel;

    public function __construct()
    {
        $this->session = session();
        $this->pendaftarModel = new PendaftarModel();
        $this->alamatModel = new AlamatModel();
        $this->ayahModel = new AyahModel();
        $this->ibuModel = new IbuModel();
        $this->sekolahModel = new SekolahModel();
    }

    /**
     * Main dashboard - redirect based on role
     */
    public function index()
    {
        $role = $this->session->get('role');

        switch ($role) {
            case 'tsanawiyyah':
                return redirect()->to('/dashboard/tsanawiyyah');
            case 'muallimin':
                return redirect()->to('/dashboard/muallimin');
            case 'superadmin':
            default:
                return $this->superadmin();
        }
    }

    /**
     * Dashboard Tsanawiyyah
     */
    public function tsanawiyyah()
    {
        return $this->showDashboard('TSANAWIYYAH');
    }

    /**
     * Dashboard Muallimin
     */
    public function muallimin()
    {
        return $this->showDashboard('MUALLIMIN');
    }

    /**
     * Superadmin dashboard - shows all data
     */
    public function superadmin()
    {
        return $this->showDashboard(null);
    }

    /**
     * Show dashboard with data
     */
    protected function showDashboard($jalur = null)
    {
        $request = $this->request;

        // Get query parameters
        $page = (int) ($request->getGet('page') ?? 1);
        $perPage = (int) ($request->getGet('perPage') ?? 10);
        $search = $request->getGet('search') ?? '';
        $sortBy = $request->getGet('sortBy') ?? 'tanggal_daftar';
        $sortOrder = $request->getGet('sortOrder') ?? 'DESC';

        // Validate sort parameters
        $allowedSortColumns = [
            'nomor_pendaftaran', 'nama_lengkap', 'jenis_kelamin',
            'tanggal_lahir', 'tanggal_daftar', 'no_hp'
        ];
        if (!in_array($sortBy, $allowedSortColumns)) {
            $sortBy = 'tanggal_daftar';
        }
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';

        // Build query
        $builder = $this->pendaftarModel;

        // Filter by jalur if specified
        if ($jalur) {
            $builder = $builder->where('jalur_pendaftaran', $jalur);
        }

        // Search functionality
        if (!empty($search)) {
            $builder = $builder->groupStart()
                ->like('nama_lengkap', $search)
                ->orLike('nomor_pendaftaran', $search)
                ->orLike('nisn', $search)
                ->orLike('nik', $search)
                ->orLike('no_hp', $search)
                ->groupEnd();
        }

        // Get total count for pagination
        $totalRecords = $builder->countAllResults(false);

        // Apply sorting and pagination
        $pendaftar = $builder->orderBy($sortBy, $sortOrder)
            ->paginate($perPage, 'default', $page);

        // Get statistics
        $stats = $this->getStatistics($jalur);

        // Prepare view data
        $data = [
            'title' => $jalur ? "Dashboard {$jalur}" : 'Dashboard Admin',
            'jalur' => $jalur,
            'pendaftar' => $pendaftar,
            'pager' => $this->pendaftarModel->pager,
            'stats' => $stats,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortOrder' => $sortOrder,
            'perPage' => $perPage,
            'totalRecords' => $totalRecords,
            'user' => [
                'nama' => $this->session->get('nama_lengkap'),
                'role' => $this->session->get('role'),
                'username' => $this->session->get('username'),
            ],
        ];

        // Determine which view to show
        if ($jalur === 'TSANAWIYYAH') {
            return view('dashboard/tsanawiyyah', $data);
        } elseif ($jalur === 'MUALLIMIN') {
            return view('dashboard/muallimin', $data);
        } else {
            return view('dashboard/superadmin', $data);
        }
    }

    /**
     * Get statistics for dashboard
     */
    protected function getStatistics($jalur = null)
    {
        $builder = $this->pendaftarModel;

        if ($jalur) {
            $total = $builder->where('jalur_pendaftaran', $jalur)->countAllResults(false);
            $lakiLaki = $builder->where('jalur_pendaftaran', $jalur)
                ->where('jenis_kelamin', 'L')
                ->countAllResults(false);
            $perempuan = $builder->where('jalur_pendaftaran', $jalur)
                ->where('jenis_kelamin', 'P')
                ->countAllResults(false);

            // Today's registrations
            $today = date('Y-m-d');
            $hariIni = $builder->where('jalur_pendaftaran', $jalur)
                ->where('DATE(tanggal_daftar)', $today)
                ->countAllResults(false);

            // This week's registrations
            $startOfWeek = date('Y-m-d', strtotime('monday this week'));
            $mingguIni = $builder->where('jalur_pendaftaran', $jalur)
                ->where('DATE(tanggal_daftar) >=', $startOfWeek)
                ->countAllResults(false);

            // This month's registrations
            $startOfMonth = date('Y-m-01');
            $bulanIni = $builder->where('jalur_pendaftaran', $jalur)
                ->where('DATE(tanggal_daftar) >=', $startOfMonth)
                ->countAllResults(false);
        } else {
            $total = $builder->countAllResults(false);
            $lakiLaki = $builder->where('jenis_kelamin', 'L')->countAllResults(false);
            $perempuan = $builder->where('jenis_kelamin', 'P')->countAllResults(false);

            $today = date('Y-m-d');
            $hariIni = $builder->where('DATE(tanggal_daftar)', $today)->countAllResults(false);

            $startOfWeek = date('Y-m-d', strtotime('monday this week'));
            $mingguIni = $builder->where('DATE(tanggal_daftar) >=', $startOfWeek)->countAllResults(false);

            $startOfMonth = date('Y-m-01');
            $bulanIni = $builder->where('DATE(tanggal_daftar) >=', $startOfMonth)->countAllResults(false);

            // Additional stats for superadmin
            $tsanawiyyah = $builder->where('jalur_pendaftaran', 'TSANAWIYYAH')->countAllResults(false);
            $muallimin = $builder->where('jalur_pendaftaran', 'MUALLIMIN')->countAllResults(false);

            return [
                'total' => $total,
                'laki_laki' => $lakiLaki,
                'perempuan' => $perempuan,
                'hari_ini' => $hariIni,
                'minggu_ini' => $mingguIni,
                'bulan_ini' => $bulanIni,
                'tsanawiyyah' => $tsanawiyyah,
                'muallimin' => $muallimin,
            ];
        }

        return [
            'total' => $total,
            'laki_laki' => $lakiLaki,
            'perempuan' => $perempuan,
            'hari_ini' => $hariIni,
            'minggu_ini' => $mingguIni,
            'bulan_ini' => $bulanIni,
        ];
    }

    /**
     * View detail of a pendaftar
     */
    public function detail($id)
    {
        $pendaftar = $this->pendaftarModel->find($id);

        if (!$pendaftar) {
            return redirect()->back()->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Check role access
        $role = $this->session->get('role');
        if ($role !== 'superadmin' && strtolower($role) !== strtolower($pendaftar['jalur_pendaftaran'])) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses ke data ini.');
        }

        // Get related data
        $alamat = $this->alamatModel->where('id_pendaftar', $id)->first();
        $ayah = $this->ayahModel->where('id_pendaftar', $id)->first();
        $ibu = $this->ibuModel->where('id_pendaftar', $id)->first();
        $sekolah = $this->sekolahModel->where('id_pendaftar', $id)->first();

        $data = [
            'title' => 'Detail Pendaftar - ' . $pendaftar['nama_lengkap'],
            'pendaftar' => $pendaftar,
            'alamat' => $alamat,
            'ayah' => $ayah,
            'ibu' => $ibu,
            'sekolah' => $sekolah,
            'user' => [
                'nama' => $this->session->get('nama_lengkap'),
                'role' => $this->session->get('role'),
            ],
        ];

        return view('dashboard/detail', $data);
    }

    /**
     * Export data to Excel/CSV
     */
    public function export($format = 'csv')
    {
        $jalur = $this->request->getGet('jalur');
        $role = $this->session->get('role');

        // Determine which data to export based on role
        if ($role !== 'superadmin') {
            $jalur = strtoupper($role);
        }

        // Build query
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.alamat_jalan, alamat_pendaftar.desa_kelurahan,
                      alamat_pendaftar.kecamatan, alamat_pendaftar.kabupaten_kota, alamat_pendaftar.provinsi,
                      asal_sekolah.nama_sekolah, asal_sekolah.npsn,
                      data_ayah.nama_ayah, data_ayah.no_hp_ayah,
                      data_ibu.nama_ibu, data_ibu.no_hp_ibu')
            ->join('alamat_pendaftar', 'pendaftar.id_pendaftar = alamat_pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'pendaftar.id_pendaftar = asal_sekolah.id_pendaftar', 'left')
            ->join('data_ayah', 'pendaftar.id_pendaftar = data_ayah.id_pendaftar', 'left')
            ->join('data_ibu', 'pendaftar.id_pendaftar = data_ibu.id_pendaftar', 'left');

        if ($jalur) {
            $builder = $builder->where('pendaftar.jalur_pendaftaran', $jalur);
        }

        $data = $builder->orderBy('pendaftar.tanggal_daftar', 'DESC')->findAll();

        if ($format === 'csv') {
            return $this->exportToCsv($data, $jalur);
        }

        return $this->exportToExcel($data, $jalur);
    }

    /**
     * Export data to CSV
     */
    protected function exportToCsv($data, $jalur = null)
    {
        $filename = 'pendaftar_' . ($jalur ? strtolower($jalur) . '_' : '') . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');

        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        // Header row
        fputcsv($output, [
            'No',
            'No. Pendaftaran',
            'Jalur',
            'NISN',
            'NIK',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'No. HP',
            'Alamat',
            'Desa/Kelurahan',
            'Kecamatan',
            'Kabupaten/Kota',
            'Provinsi',
            'Asal Sekolah',
            'NPSN',
            'Nama Ayah',
            'No. HP Ayah',
            'Nama Ibu',
            'No. HP Ibu',
            'Tanggal Daftar',
        ]);

        // Data rows
        $no = 1;
        foreach ($data as $row) {
            fputcsv($output, [
                $no++,
                $row['nomor_pendaftaran'] ?? '',
                $row['jalur_pendaftaran'] ?? '',
                $row['nisn'] ?? '',
                $row['nik'] ?? '',
                $row['nama_lengkap'] ?? '',
                $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan',
                $row['tempat_lahir'] ?? '',
                $row['tanggal_lahir'] ?? '',
                $row['no_hp'] ?? '',
                $row['alamat_jalan'] ?? '',
                $row['desa_kelurahan'] ?? '',
                $row['kecamatan'] ?? '',
                $row['kabupaten_kota'] ?? '',
                $row['provinsi'] ?? '',
                $row['nama_sekolah'] ?? '',
                $row['npsn'] ?? '',
                $row['nama_ayah'] ?? '',
                $row['no_hp_ayah'] ?? '',
                $row['nama_ibu'] ?? '',
                $row['no_hp_ibu'] ?? '',
                $row['tanggal_daftar'] ?? '',
            ]);
        }

        fclose($output);
        exit;
    }

    /**
     * Export data to Excel (XLSX format using simple HTML table)
     */
    protected function exportToExcel($data, $jalur = null)
    {
        $filename = 'pendaftar_' . ($jalur ? strtolower($jalur) . '_' : '') . date('Y-m-d_His') . '.xls';

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $html = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
            <style>
                table { border-collapse: collapse; }
                th, td { border: 1px solid #000; padding: 5px; }
                th { background-color: #1AB34A; color: white; font-weight: bold; }
                .center { text-align: center; }
            </style>
        </head>
        <body>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Pendaftaran</th>
                        <th>Jalur</th>
                        <th>NISN</th>
                        <th>NIK</th>
                        <th>Nama Lengkap</th>
                        <th>Jenis Kelamin</th>
                        <th>Tempat Lahir</th>
                        <th>Tanggal Lahir</th>
                        <th>No. HP</th>
                        <th>Alamat</th>
                        <th>Desa/Kelurahan</th>
                        <th>Kecamatan</th>
                        <th>Kabupaten/Kota</th>
                        <th>Provinsi</th>
                        <th>Asal Sekolah</th>
                        <th>NPSN</th>
                        <th>Nama Ayah</th>
                        <th>No. HP Ayah</th>
                        <th>Nama Ibu</th>
                        <th>No. HP Ibu</th>
                        <th>Tanggal Daftar</th>
                    </tr>
                </thead>
                <tbody>';

        $no = 1;
        foreach ($data as $row) {
            $html .= '<tr>
                <td class="center">' . $no++ . '</td>
                <td>' . htmlspecialchars($row['nomor_pendaftaran'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['jalur_pendaftaran'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['nisn'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['nik'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['nama_lengkap'] ?? '') . '</td>
                <td class="center">' . ($row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan') . '</td>
                <td>' . htmlspecialchars($row['tempat_lahir'] ?? '') . '</td>
                <td class="center">' . htmlspecialchars($row['tanggal_lahir'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['no_hp'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['alamat_jalan'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['desa_kelurahan'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['kecamatan'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['kabupaten_kota'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['provinsi'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['nama_sekolah'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['npsn'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['nama_ayah'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['no_hp_ayah'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['nama_ibu'] ?? '') . '</td>
                <td>' . htmlspecialchars($row['no_hp_ibu'] ?? '') . '</td>
                <td class="center">' . htmlspecialchars($row['tanggal_daftar'] ?? '') . '</td>
            </tr>';
        }

        $html .= '</tbody></table></body></html>';

        echo $html;
        exit;
    }
}
