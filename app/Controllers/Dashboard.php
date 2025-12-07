<?php

namespace App\Controllers;

use App\Models\PendaftarModel;
use App\Models\AlamatModel;
use App\Models\AyahModel;
use App\Models\IbuModel;
use App\Models\WaliModel;
use App\Models\BansosModel;
use App\Models\SekolahModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class Dashboard extends BaseController
{
    protected $session;
    protected $pendaftarModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pendaftarModel = new PendaftarModel();
    }

    /**
     * Main Dashboard - Route based on user role
     */
    public function index()
    {
        $role = $this->session->get('role_panitia');

        // Route user to appropriate dashboard based on role
        switch ($role) {
            case 'superadmin':
                return $this->superadminDashboard();
            case 'tsanawiyyah':
                return redirect()->to('/dashboard/tsanawiyyah');
            case 'muallimin':
                return redirect()->to('/dashboard/muallimin');
            default:
                return redirect()->to('/login')
                    ->with('error', 'Role tidak valid');
        }
    }

    /**
     * Superadmin Dashboard - Full access to all data
     */
    private function superadminDashboard()
    {
        // Get search, sort, and pagination parameters
        $search = $this->request->getGet('search') ?? '';
        $sortBy = $this->request->getGet('sort') ?? 'tanggal_daftar';
        $sortDir = $this->request->getGet('dir') ?? 'DESC';
        $startDate = $this->request->getGet('start_date') ?? '';
        $endDate = $this->request->getGet('end_date') ?? '';
        $perPage = 20;

        // Get statistics for both programs (with date filter if applicable)
        // Use separate builders to avoid query mixing
        $db = \Config\Database::connect();

        // Tsanawiyyah count
        $tsnBuilder = $db->table('pendaftar')->where('jalur_pendaftaran', 'tsanawiyyah');
        if (!empty($startDate)) {
            $tsnBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $tsnBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $totalTsn = $tsnBuilder->countAllResults();

        // Muallimin count
        $muaBuilder = $db->table('pendaftar')->where('jalur_pendaftaran', 'muallimin');
        if (!empty($startDate)) {
            $muaBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $muaBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $totalMua = $muaBuilder->countAllResults();

        // All count
        $allBuilder = $db->table('pendaftar');
        if (!empty($startDate)) {
            $allBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $allBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $totalAll = $allBuilder->countAllResults();

        // Build query for registrations
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan, asal_sekolah.nama_asal_sekolah')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'asal_sekolah.id_pendaftar = pendaftar.id_pendaftar', 'left');

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('pendaftar.nama_lengkap', $search)
                ->orLike('pendaftar.nisn', $search)
                ->orLike('pendaftar.nik', $search)
                ->orLike('pendaftar.tempat_lahir', $search)
                ->orLike('alamat_pendaftar.kecamatan', $search)
                ->groupEnd();
        }

        // Apply date range filter
        if (!empty($startDate)) {
            $builder->where('pendaftar.tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $builder->where('pendaftar.tanggal_daftar <=', $endDate . ' 23:59:59');
        }

        // Validate sort column
        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan', 'jalur_pendaftaran'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal_daftar';
        }

        // Validate sort direction
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        // Apply sorting and get paginated results
        $recentRegistrations = $builder
            ->orderBy('pendaftar.' . $sortBy, $sortDir)
            ->paginate($perPage, 'default');

        // Get pager
        $pager = $this->pendaftarModel->pager;

        // Additional statistics
        // Gender statistics
        $maleCount = $db->table('pendaftar')->where('jenis_kelamin', 'L')->countAllResults();
        $femaleCount = $db->table('pendaftar')->where('jenis_kelamin', 'P')->countAllResults();

        // Registration time statistics
        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $monthStart = date('Y-m-01');

        $todayCount = $db->table('pendaftar')
            ->where('DATE(tanggal_daftar)', $today)
            ->countAllResults();
        $weekCount = $db->table('pendaftar')
            ->where('tanggal_daftar >=', $weekStart . ' 00:00:00')
            ->countAllResults();
        $monthCount = $db->table('pendaftar')
            ->where('tanggal_daftar >=', $monthStart . ' 00:00:00')
            ->countAllResults();

        // Parent income statistics (from data_ayah table)
        $incomeRanges = [
            'below_1m' => 0,
            '1m_3m' => 0,
            '3m_5m' => 0,
            'above_5m' => 0
        ];

        $incomeData = $db->table('data_ayah')
            ->select('penghasilan_ayah')
            ->get()
            ->getResultArray();

        foreach ($incomeData as $row) {
            $income = (int)$row['penghasilan_ayah'];
            if ($income < 1000000) {
                $incomeRanges['below_1m']++;
            } elseif ($income >= 1000000 && $income < 3000000) {
                $incomeRanges['1m_3m']++;
            } elseif ($income >= 3000000 && $income < 5000000) {
                $incomeRanges['3m_5m']++;
            } else {
                $incomeRanges['above_5m']++;
            }
        }

        // Distance statistics (from alamat_pendaftar table)
        $distanceRanges = [
            'below_5km' => 0,
            '5km_10km' => 0,
            '10km_20km' => 0,
            'above_20km' => 0
        ];

        $distanceData = $db->table('alamat_pendaftar')
            ->select('jarak_ke_sekolah')
            ->get()
            ->getResultArray();

        foreach ($distanceData as $row) {
            $distance = (float)$row['jarak_ke_sekolah'];
            if ($distance < 5) {
                $distanceRanges['below_5km']++;
            } elseif ($distance >= 5 && $distance < 10) {
                $distanceRanges['5km_10km']++;
            } elseif ($distance >= 10 && $distance < 20) {
                $distanceRanges['10km_20km']++;
            } else {
                $distanceRanges['above_20km']++;
            }
        }

        $data = [
            'title' => 'Dashboard Superadmin',
            'user' => $this->getUserData(),
            'stats' => [
                'total_all' => $totalAll,
                'total_tsn' => $totalTsn,
                'total_mua' => $totalMua,
                'male' => $maleCount,
                'female' => $femaleCount,
                'today' => $todayCount,
                'this_week' => $weekCount,
                'this_month' => $monthCount,
                'income_ranges' => $incomeRanges,
                'distance_ranges' => $distanceRanges,
            ],
            'recent_registrations' => $recentRegistrations,
            'pager' => $pager,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('dashboard/superadmin', $data);
    }

    /**
     * Tsanawiyyah Dashboard - Only Tsanawiyyah data
     */
    public function tsanawiyyah()
    {
        // Get search, sort, and pagination parameters
        $search = $this->request->getGet('search') ?? '';
        $sortBy = $this->request->getGet('sort') ?? 'tanggal_daftar';
        $sortDir = $this->request->getGet('dir') ?? 'DESC';
        $startDate = $this->request->getGet('start_date') ?? '';
        $endDate = $this->request->getGet('end_date') ?? '';
        $perPage = 20;

        // Get total for statistics (with date filter)
        $db = \Config\Database::connect();
        $totalBuilder = $db->table('pendaftar')->where('jalur_pendaftaran', 'tsanawiyyah');
        if (!empty($startDate)) {
            $totalBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $totalBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $total = $totalBuilder->countAllResults();

        // Build query
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan, asal_sekolah.nama_asal_sekolah')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'asal_sekolah.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->where('pendaftar.jalur_pendaftaran', 'tsanawiyyah');

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('pendaftar.nama_lengkap', $search)
                ->orLike('pendaftar.nisn', $search)
                ->orLike('pendaftar.nik', $search)
                ->orLike('pendaftar.tempat_lahir', $search)
                ->orLike('alamat_pendaftar.kecamatan', $search)
                ->groupEnd();
        }

        // Apply date range filter
        if (!empty($startDate)) {
            $builder->where('pendaftar.tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $builder->where('pendaftar.tanggal_daftar <=', $endDate . ' 23:59:59');
        }

        // Validate sort column
        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal_daftar';
        }

        // Validate sort direction
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        // Apply sorting and get paginated results
        $registrations = $builder
            ->orderBy('pendaftar.' . $sortBy, $sortDir)
            ->paginate($perPage, 'default');

        // Get pager
        $pager = $this->pendaftarModel->pager;

        $data = [
            'title' => 'Dashboard Tsanawiyyah',
            'user' => $this->getUserData(),
            'jalur' => 'Tsanawiyyah',
            'total_registrations' => $total,
            'registrations' => $registrations,
            'pager' => $pager,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('dashboard/jalur_dashboard', $data);
    }

    /**
     * Muallimin Dashboard - Only Muallimin data
     */
    public function muallimin()
    {
        // Get search, sort, and pagination parameters
        $search = $this->request->getGet('search') ?? '';
        $sortBy = $this->request->getGet('sort') ?? 'tanggal_daftar';
        $sortDir = $this->request->getGet('dir') ?? 'DESC';
        $startDate = $this->request->getGet('start_date') ?? '';
        $endDate = $this->request->getGet('end_date') ?? '';
        $perPage = 20;

        // Get total for statistics (with date filter)
        $db = \Config\Database::connect();
        $totalBuilder = $db->table('pendaftar')->where('jalur_pendaftaran', 'muallimin');
        if (!empty($startDate)) {
            $totalBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $totalBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $total = $totalBuilder->countAllResults();

        // Build query
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan, asal_sekolah.nama_asal_sekolah')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'asal_sekolah.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->where('pendaftar.jalur_pendaftaran', 'muallimin');

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('pendaftar.nama_lengkap', $search)
                ->orLike('pendaftar.nisn', $search)
                ->orLike('pendaftar.nik', $search)
                ->orLike('pendaftar.tempat_lahir', $search)
                ->orLike('alamat_pendaftar.kecamatan', $search)
                ->groupEnd();
        }

        // Apply date range filter
        if (!empty($startDate)) {
            $builder->where('pendaftar.tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $builder->where('pendaftar.tanggal_daftar <=', $endDate . ' 23:59:59');
        }

        // Validate sort column
        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal_daftar';
        }

        // Validate sort direction
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        // Apply sorting and get paginated results
        $registrations = $builder
            ->orderBy('pendaftar.' . $sortBy, $sortDir)
            ->paginate($perPage, 'default');

        // Get pager
        $pager = $this->pendaftarModel->pager;

        $data = [
            'title' => 'Dashboard Mu\'allimin',
            'user' => $this->getUserData(),
            'jalur' => 'Mu\'allimin',
            'total_registrations' => $total,
            'registrations' => $registrations,
            'pager' => $pager,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir,
            'startDate' => $startDate,
            'endDate' => $endDate
        ];

        return view('dashboard/jalur_dashboard', $data);
    }

    /**
     * Detail Pendaftar - Show all data from 7 tables
     */
    public function detail($id)
    {
        // Load models
        $alamatModel = new AlamatModel();
        $ayahModel = new AyahModel();
        $ibuModel = new IbuModel();
        $waliModel = new WaliModel();
        $bansosModel = new BansosModel();
        $sekolahModel = new SekolahModel();

        // Get data from all tables
        $pendaftar = $this->pendaftarModel->find($id);

        if (!$pendaftar) {
            return redirect()->to('/dashboard')->with('error', 'Data pendaftar tidak ditemukan');
        }

        // Get related data
        $alamat = $alamatModel->where('id_pendaftar', $id)->first();
        $ayah = $ayahModel->where('id_pendaftar', $id)->first();
        $ibu = $ibuModel->where('id_pendaftar', $id)->first();
        $wali = $waliModel->where('id_pendaftar', $id)->first();
        $bansos = $bansosModel->where('id_pendaftar', $id)->first();
        $sekolah = $sekolahModel->where('id_pendaftar', $id)->first();

        $data = [
            'title' => 'Detail Pendaftar - ' . $pendaftar['nama_lengkap'],
            'user' => $this->getUserData(),
            'pendaftar' => $pendaftar,
            'alamat' => $alamat,
            'ayah' => $ayah,
            'ibu' => $ibu,
            'wali' => $wali,
            'bansos' => $bansos,
            'sekolah' => $sekolah
        ];

        return view('dashboard/detail', $data);
    }

    /**
     * Export data to CSV
     */
    public function exportCsv()
    {
        // Get jalur from query parameter
        $jalur = $this->request->getGet('jalur') ?? 'all';
        $search = $this->request->getGet('search') ?? '';
        $sortBy = $this->request->getGet('sort') ?? 'tanggal_daftar';
        $sortDir = $this->request->getGet('dir') ?? 'DESC';
        $startDate = $this->request->getGet('start_date') ?? '';
        $endDate = $this->request->getGet('end_date') ?? '';

        // Build query with ALL related tables
        $builder = $this->pendaftarModel
            ->select('pendaftar.*,
                      alamat_pendaftar.nomor_kk, alamat_pendaftar.jenis_tempat_tinggal,
                      alamat_pendaftar.alamat, alamat_pendaftar.desa, alamat_pendaftar.kecamatan,
                      alamat_pendaftar.kabupaten, alamat_pendaftar.provinsi, alamat_pendaftar.kode_pos,
                      alamat_pendaftar.jarak_ke_sekolah, alamat_pendaftar.waktu_tempuh,
                      alamat_pendaftar.transportasi, alamat_pendaftar.email, alamat_pendaftar.media_sosial,
                      data_ayah.nama_ayah, data_ayah.nik_ayah, data_ayah.pendidikan_ayah,
                      data_ayah.pekerjaan_ayah, data_ayah.penghasilan_ayah,
                      data_ayah.hp_ayah, data_ayah.status_ayah,
                      data_ibu.nama_ibu, data_ibu.nik_ibu, data_ibu.pendidikan_ibu,
                      data_ibu.pekerjaan_ibu, data_ibu.penghasilan_ibu,
                      data_ibu.hp_ibu, data_ibu.status_ibu,
                      data_wali.nama_wali, data_wali.nik_wali, data_wali.pendidikan_wali,
                      data_wali.pekerjaan_wali, data_wali.penghasilan_wali,
                      data_wali.hp_wali,
                      bansos_pendaftar.no_kks, bansos_pendaftar.no_pkh, bansos_pendaftar.no_kip,
                      asal_sekolah.npsn, asal_sekolah.nama_asal_sekolah, asal_sekolah.jenjang_sekolah,
                      asal_sekolah.status_sekolah, asal_sekolah.lokasi_sekolah, asal_sekolah.asal_jenjang')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_ayah', 'data_ayah.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_ibu', 'data_ibu.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_wali', 'data_wali.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('bansos_pendaftar', 'bansos_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'asal_sekolah.id_pendaftar = pendaftar.id_pendaftar', 'left');

        // Filter by jalur if not 'all'
        if ($jalur !== 'all') {
            $builder->where('pendaftar.jalur_pendaftaran', $jalur);
        }

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('pendaftar.nama_lengkap', $search)
                ->orLike('pendaftar.nisn', $search)
                ->orLike('pendaftar.nik', $search)
                ->orLike('pendaftar.tempat_lahir', $search)
                ->orLike('alamat_pendaftar.kecamatan', $search)
                ->groupEnd();
        }

        // Apply date range filter
        if (!empty($startDate)) {
            $builder->where('pendaftar.tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $builder->where('pendaftar.tanggal_daftar <=', $endDate . ' 23:59:59');
        }

        // Validate sort column
        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan', 'jalur_pendaftaran'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal_daftar';
        }

        // Validate sort direction
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        // Get all data (no pagination for export)
        $data = $builder
            ->orderBy('pendaftar.' . $sortBy, $sortDir)
            ->findAll();

        // Generate CSV filename
        $filename = 'pendaftar_' . ($jalur !== 'all' ? $jalur . '_' : '') . date('Y-m-d_His') . '.csv';

        // Set headers for CSV download
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        // Create output stream
        $output = fopen('php://output', 'w');

        // Add BOM for Excel UTF-8 compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

        // Comprehensive CSV Headers - All fields from 7 tables
        fputcsv($output, [
            // Pendaftar Data
            'No', 'Nomor Pendaftaran', 'NISN', 'NIK', 'Nama Lengkap', 'Jenis Kelamin',
            'Tempat Lahir', 'Tanggal Lahir', 'Jalur Pendaftaran', 'Status Keluarga',
            'Anak Ke', 'Jumlah Saudara', 'No HP', 'Tanggal Daftar',
            // Alamat Data
            'Nomor KK', 'Jenis Tempat Tinggal', 'Alamat Lengkap', 'Desa/Kelurahan',
            'Kecamatan', 'Kabupaten', 'Provinsi', 'Kode Pos', 'Jarak ke Sekolah (KM)',
            'Waktu Tempuh', 'Transportasi', 'Email', 'Media Sosial',
            // Data Ayah
            'Nama Ayah', 'NIK Ayah', 'Pendidikan Ayah', 'Pekerjaan Ayah',
            'Penghasilan Ayah', 'No HP Ayah', 'Status Ayah',
            // Data Ibu
            'Nama Ibu', 'NIK Ibu', 'Pendidikan Ibu', 'Pekerjaan Ibu',
            'Penghasilan Ibu', 'No HP Ibu', 'Status Ibu',
            // Data Wali
            'Nama Wali', 'NIK Wali', 'Pendidikan Wali', 'Pekerjaan Wali',
            'Penghasilan Wali', 'No HP Wali',
            // Bansos
            'No. KKS', 'No. PKH', 'No. KIP',
            // Asal Sekolah
            'NPSN', 'Nama Asal Sekolah', 'Jenjang Sekolah', 'Status Sekolah',
            'Lokasi Sekolah', 'Asal Jenjang'
        ], ',', '"', '\\');

        // Comprehensive CSV Data
        $no = 1;
        foreach ($data as $row) {
            fputcsv($output, [
                // Pendaftar Data
                $no++,
                $row['nomor_pendaftaran'] ?? '-',
                $row['nisn'] ?? '-',
                $row['nik'] ?? '-',
                $row['nama_lengkap'] ?? '-',
                ($row['jenis_kelamin'] ?? '') === 'L' ? 'Laki-laki' : 'Perempuan',
                $row['tempat_lahir'] ?? '-',
                !empty($row['tanggal_lahir']) ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-',
                ucfirst($row['jalur_pendaftaran'] ?? '-'),
                $row['status_keluarga'] ?? '-',
                $row['anak_ke'] ?? '-',
                $row['jumlah_saudara'] ?? '-',
                $row['no_hp'] ?? '-',
                !empty($row['tanggal_daftar']) ? date('d/m/Y H:i', strtotime($row['tanggal_daftar'])) : '-',
                // Alamat Data
                $row['nomor_kk'] ?? '-',
                $row['jenis_tempat_tinggal'] ?? '-',
                $row['alamat'] ?? '-',
                $row['desa'] ?? '-',
                $row['kecamatan'] ?? '-',
                $row['kabupaten'] ?? '-',
                $row['provinsi'] ?? '-',
                $row['kode_pos'] ?? '-',
                $row['jarak_ke_sekolah'] ?? '-',
                $row['waktu_tempuh'] ?? '-',
                $row['transportasi'] ?? '-',
                $row['email'] ?? '-',
                $row['media_sosial'] ?? '-',
                // Data Ayah
                $row['nama_ayah'] ?? '-',
                $row['nik_ayah'] ?? '-',
                $row['pendidikan_ayah'] ?? '-',
                $row['pekerjaan_ayah'] ?? '-',
                $row['penghasilan_ayah'] ?? '-',
                $row['hp_ayah'] ?? '-',
                $row['status_ayah'] ?? '-',
                // Data Ibu
                $row['nama_ibu'] ?? '-',
                $row['nik_ibu'] ?? '-',
                $row['pendidikan_ibu'] ?? '-',
                $row['pekerjaan_ibu'] ?? '-',
                $row['penghasilan_ibu'] ?? '-',
                $row['hp_ibu'] ?? '-',
                $row['status_ibu'] ?? '-',
                // Data Wali
                $row['nama_wali'] ?? '-',
                $row['nik_wali'] ?? '-',
                $row['pendidikan_wali'] ?? '-',
                $row['pekerjaan_wali'] ?? '-',
                $row['penghasilan_wali'] ?? '-',
                $row['hp_wali'] ?? '-',
                // Bansos
                $row['no_kks'] ?? '-',
                $row['no_pkh'] ?? '-',
                $row['no_kip'] ?? '-',
                // Asal Sekolah
                $row['npsn'] ?? '-',
                $row['nama_asal_sekolah'] ?? '-',
                $row['jenjang_sekolah'] ?? '-',
                $row['status_sekolah'] ?? '-',
                $row['lokasi_sekolah'] ?? '-',
                $row['asal_jenjang'] ?? '-'
            ], ',', '"', '\\');
        }

        fclose($output);
        exit;
    }

    /**
     * Export data to Excel with advanced formatting
     */
    public function exportExcel()
    {
        // Get parameters
        $jalur = $this->request->getGet('jalur') ?? 'all';
        $search = $this->request->getGet('search') ?? '';
        $sortBy = $this->request->getGet('sort') ?? 'tanggal_daftar';
        $sortDir = $this->request->getGet('dir') ?? 'DESC';
        $startDate = $this->request->getGet('start_date') ?? '';
        $endDate = $this->request->getGet('end_date') ?? '';

        // Build query with ALL related tables (same as CSV export)
        $builder = $this->pendaftarModel
            ->select('pendaftar.*,
                      alamat_pendaftar.nomor_kk, alamat_pendaftar.jenis_tempat_tinggal,
                      alamat_pendaftar.alamat, alamat_pendaftar.desa, alamat_pendaftar.kecamatan,
                      alamat_pendaftar.kabupaten, alamat_pendaftar.provinsi, alamat_pendaftar.kode_pos,
                      alamat_pendaftar.jarak_ke_sekolah, alamat_pendaftar.waktu_tempuh,
                      alamat_pendaftar.transportasi, alamat_pendaftar.email, alamat_pendaftar.media_sosial,
                      data_ayah.nama_ayah, data_ayah.nik_ayah, data_ayah.pendidikan_ayah,
                      data_ayah.pekerjaan_ayah, data_ayah.penghasilan_ayah,
                      data_ayah.hp_ayah, data_ayah.status_ayah,
                      data_ibu.nama_ibu, data_ibu.nik_ibu, data_ibu.pendidikan_ibu,
                      data_ibu.pekerjaan_ibu, data_ibu.penghasilan_ibu,
                      data_ibu.hp_ibu, data_ibu.status_ibu,
                      data_wali.nama_wali, data_wali.nik_wali, data_wali.pendidikan_wali,
                      data_wali.pekerjaan_wali, data_wali.penghasilan_wali,
                      data_wali.hp_wali,
                      bansos_pendaftar.no_kks, bansos_pendaftar.no_pkh, bansos_pendaftar.no_kip,
                      asal_sekolah.npsn, asal_sekolah.nama_asal_sekolah, asal_sekolah.jenjang_sekolah,
                      asal_sekolah.status_sekolah, asal_sekolah.lokasi_sekolah, asal_sekolah.asal_jenjang')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_ayah', 'data_ayah.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_ibu', 'data_ibu.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_wali', 'data_wali.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('bansos_pendaftar', 'bansos_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'asal_sekolah.id_pendaftar = pendaftar.id_pendaftar', 'left');

        // Filter by jalur if not 'all'
        if ($jalur !== 'all') {
            $builder->where('pendaftar.jalur_pendaftaran', $jalur);
        }

        // Apply search filter
        if (!empty($search)) {
            $builder->groupStart()
                ->like('pendaftar.nama_lengkap', $search)
                ->orLike('pendaftar.nisn', $search)
                ->orLike('pendaftar.nik', $search)
                ->orLike('pendaftar.tempat_lahir', $search)
                ->orLike('alamat_pendaftar.kecamatan', $search)
                ->groupEnd();
        }

        // Apply date range filter
        if (!empty($startDate)) {
            $builder->where('pendaftar.tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $builder->where('pendaftar.tanggal_daftar <=', $endDate . ' 23:59:59');
        }

        // Validate sort column
        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan', 'jalur_pendaftaran'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal_daftar';
        }

        // Validate sort direction
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        // Get all data (no pagination for export)
        $data = $builder
            ->orderBy('pendaftar.' . $sortBy, $sortDir)
            ->findAll();

        // Create new Spreadsheet
        $spreadsheet = new Spreadsheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('PSB Persis 31')
            ->setTitle('Data Pendaftar')
            ->setSubject('Laporan Pendaftaran Santri')
            ->setDescription('Laporan data pendaftaran santri PSB Persis 31');

        // Create Summary Sheet
        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Ringkasan');

        // Summary header
        $summarySheet->setCellValue('A1', 'LAPORAN DATA PENDAFTAR');
        $summarySheet->setCellValue('A2', 'PSB PERSIS 31 CIAMIS');
        $summarySheet->mergeCells('A1:D1');
        $summarySheet->mergeCells('A2:D2');

        // Style summary header
        $summarySheet->getStyle('A1:D2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Summary statistics
        $row = 4;
        $summarySheet->setCellValue('A' . $row, 'Jalur:');
        $summarySheet->setCellValue('B' . $row, $jalur === 'all' ? 'Semua Jalur' : ucfirst($jalur));
        $row++;

        $summarySheet->setCellValue('A' . $row, 'Total Pendaftar:');
        $summarySheet->setCellValue('B' . $row, count($data));
        $row++;

        if (!empty($startDate) || !empty($endDate)) {
            $summarySheet->setCellValue('A' . $row, 'Periode:');
            $period = '';
            if (!empty($startDate)) $period .= date('d/m/Y', strtotime($startDate));
            if (!empty($startDate) && !empty($endDate)) $period .= ' - ';
            if (!empty($endDate)) $period .= date('d/m/Y', strtotime($endDate));
            $summarySheet->setCellValue('B' . $row, $period);
            $row++;
        }

        $summarySheet->setCellValue('A' . $row, 'Tanggal Export:');
        $summarySheet->setCellValue('B' . $row, date('d/m/Y H:i:s'));

        // Style summary content
        $summarySheet->getStyle('A4:A' . $row)->applyFromArray([
            'font' => ['bold' => true]
        ]);

        // Statistics by jalur
        $row += 2;
        $summarySheet->setCellValue('A' . $row, 'Statistik Per Jalur');
        $summarySheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12]
        ]);
        $row++;

        $tsnCount = 0;
        $muaCount = 0;
        foreach ($data as $d) {
            if ($d['jalur_pendaftaran'] === 'tsanawiyyah') $tsnCount++;
            if ($d['jalur_pendaftaran'] === 'muallimin') $muaCount++;
        }

        $summarySheet->setCellValue('A' . $row, 'Tsanawiyyah:');
        $summarySheet->setCellValue('B' . $row, $tsnCount);
        $row++;
        $summarySheet->setCellValue('A' . $row, 'Mu\'allimin:');
        $summarySheet->setCellValue('B' . $row, $muaCount);

        // Auto-width for summary
        $summarySheet->getColumnDimension('A')->setWidth(20);
        $summarySheet->getColumnDimension('B')->setWidth(30);

        // Create Data Sheet
        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('Data Pendaftar');

        // Headers for data sheet
        $headers = [
            'No', 'Nomor Pendaftaran', 'NISN', 'NIK', 'Nama Lengkap',
            'Jenis Kelamin', 'Tempat Lahir', 'Tanggal Lahir', 'Jalur Pendaftaran',
            'Status Keluarga', 'Anak Ke', 'Jumlah Saudara', 'No HP',
            'Desa/Kelurahan', 'Kecamatan', 'Tanggal Daftar'
        ];

        $col = 'A';
        foreach ($headers as $header) {
            $dataSheet->setCellValue($col . '1', $header);
            $col++;
        }

        // Style header row
        $dataSheet->getStyle('A1:P1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1AB34A']
            ],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]
            ]
        ]);

        // Data rows
        $rowNum = 2;
        $no = 1;
        foreach ($data as $row) {
            $dataSheet->setCellValue('A' . $rowNum, $no++);
            $dataSheet->setCellValue('B' . $rowNum, $row['nomor_pendaftaran'] ?? '-');
            $dataSheet->setCellValue('C' . $rowNum, $row['nisn'] ?? '-');
            $dataSheet->setCellValue('D' . $rowNum, $row['nik'] ?? '-');
            $dataSheet->setCellValue('E' . $rowNum, $row['nama_lengkap'] ?? '-');
            $dataSheet->setCellValue('F' . $rowNum, $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan');
            $dataSheet->setCellValue('G' . $rowNum, $row['tempat_lahir'] ?? '-');
            $dataSheet->setCellValue('H' . $rowNum, !empty($row['tanggal_lahir']) ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-');
            $dataSheet->setCellValue('I' . $rowNum, ucfirst($row['jalur_pendaftaran'] ?? '-'));
            $dataSheet->setCellValue('J' . $rowNum, $row['status_keluarga'] ?? '-');
            $dataSheet->setCellValue('K' . $rowNum, $row['anak_ke'] ?? '-');
            $dataSheet->setCellValue('L' . $rowNum, $row['jumlah_saudara'] ?? '-');
            $dataSheet->setCellValue('M' . $rowNum, $row['no_hp'] ?? '-');
            $dataSheet->setCellValue('N' . $rowNum, $row['desa'] ?? '-');
            $dataSheet->setCellValue('O' . $rowNum, $row['kecamatan'] ?? '-');
            $dataSheet->setCellValue('P' . $rowNum, date('d/m/Y H:i', strtotime($row['tanggal_daftar'])));

            // Zebra striping
            if ($rowNum % 2 == 0) {
                $dataSheet->getStyle('A' . $rowNum . ':P' . $rowNum)->applyFromArray([
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }

            $rowNum++;
        }

        // Apply borders to all data
        $dataSheet->getStyle('A1:P' . ($rowNum - 1))->applyFromArray([
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]
            ]
        ]);

        // Auto-width for all columns
        foreach (range('A', 'P') as $col) {
            $dataSheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Freeze header row
        $dataSheet->freezePane('A2');

        // Generate filename
        $filename = 'pendaftar_' . ($jalur !== 'all' ? $jalur . '_' : '') . date('Y-m-d_His') . '.xlsx';

        // Set active sheet back to summary
        $spreadsheet->setActiveSheetIndex(0);

        // Create writer and output
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');

        $writer->save('php://output');
        exit;
    }

    /**
     * Helper: Get current user data from session
     */
    private function getUserData(): array
    {
        return [
            'id_admin' => $this->session->get('id_admin'),
            'username' => $this->session->get('username'),
            'nama_lengkap' => $this->session->get('nama_lengkap'),
            'email' => $this->session->get('email'),
            'role_panitia' => $this->session->get('role_panitia')
        ];
    }
}
