<?php

namespace App\Controllers;

use App\Models\PendaftarModel;

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
