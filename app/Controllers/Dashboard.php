<?php

namespace App\Controllers;

use App\Models\PendaftarModel;
use App\Models\AlamatModel;
use App\Models\AyahModel;
use App\Models\IbuModel;
use App\Models\WaliModel;
use App\Models\BansosModel;
use App\Models\SekolahModel;

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
        $perPage = 20;

        // Get statistics for both programs
        $totalTsn = $this->pendaftarModel->where('jalur_pendaftaran', 'tsanawiyyah')->countAllResults();
        $totalMua = $this->pendaftarModel->where('jalur_pendaftaran', 'muallimin')->countAllResults();
        $totalAll = $this->pendaftarModel->countAll();

        // Build query for registrations
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left');

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

        $data = [
            'title' => 'Dashboard Superadmin',
            'user' => $this->getUserData(),
            'stats' => [
                'total_all' => $totalAll,
                'total_tsn' => $totalTsn,
                'total_mua' => $totalMua,
            ],
            'recent_registrations' => $recentRegistrations,
            'pager' => $pager,
            'search' => $search,
            'sortBy' => $sortBy,
            'sortDir' => $sortDir
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
        $perPage = 20;

        // Build query
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
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

        // Validate sort column
        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal_daftar';
        }

        // Validate sort direction
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        // Get total for statistics (before pagination)
        $total = $this->pendaftarModel->where('jalur_pendaftaran', 'tsanawiyyah')->countAllResults();

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
            'sortDir' => $sortDir
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
        $perPage = 20;

        // Build query
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
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

        // Validate sort column
        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'tanggal_daftar';
        }

        // Validate sort direction
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';

        // Get total for statistics (before pagination)
        $total = $this->pendaftarModel->where('jalur_pendaftaran', 'muallimin')->countAllResults();

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
            'sortDir' => $sortDir
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

        // Build query
        $builder = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left');

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

        // CSV Headers
        fputcsv($output, [
            'No',
            'Nomor Pendaftaran',
            'NISN',
            'NIK',
            'Nama Lengkap',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jalur Pendaftaran',
            'Status Keluarga',
            'Anak Ke',
            'Jumlah Saudara',
            'No HP',
            'Desa/Kelurahan',
            'Kecamatan',
            'Tanggal Daftar'
        ]);

        // CSV Data
        $no = 1;
        foreach ($data as $row) {
            fputcsv($output, [
                $no++,
                $row['nomor_pendaftaran'] ?? '-',
                $row['nisn'] ?? '-',
                $row['nik'] ?? '-',
                $row['nama_lengkap'] ?? '-',
                $row['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan',
                $row['tempat_lahir'] ?? '-',
                !empty($row['tanggal_lahir']) ? date('d/m/Y', strtotime($row['tanggal_lahir'])) : '-',
                ucfirst($row['jalur_pendaftaran'] ?? '-'),
                $row['status_keluarga'] ?? '-',
                $row['anak_ke'] ?? '-',
                $row['jumlah_saudara'] ?? '-',
                $row['no_hp'] ?? '-',
                $row['desa'] ?? '-',
                $row['kecamatan'] ?? '-',
                date('d/m/Y H:i', strtotime($row['tanggal_daftar']))
            ]);
        }

        fclose($output);
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
