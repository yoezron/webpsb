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
        // Get statistics for both programs
        $totalTsn = $this->pendaftarModel->where('jalur_pendaftaran', 'tsanawiyyah')->countAllResults();
        $totalMua = $this->pendaftarModel->where('jalur_pendaftaran', 'muallimin')->countAllResults();
        $totalAll = $this->pendaftarModel->countAll();

        // Get recent registrations
        $recentRegistrations = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->orderBy('pendaftar.tanggal_daftar', 'DESC')
            ->limit(10)
            ->find();

        $data = [
            'title' => 'Dashboard Superadmin',
            'user' => $this->getUserData(),
            'stats' => [
                'total_all' => $totalAll,
                'total_tsn' => $totalTsn,
                'total_mua' => $totalMua,
            ],
            'recent_registrations' => $recentRegistrations
        ];

        return view('dashboard/superadmin', $data);
    }

    /**
     * Tsanawiyyah Dashboard - Only Tsanawiyyah data
     */
    public function tsanawiyyah()
    {
        // Get Tsanawiyyah statistics
        $total = $this->pendaftarModel->where('jalur_pendaftaran', 'tsanawiyyah')->countAllResults();

        // Get recent Tsanawiyyah registrations
        $registrations = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->where('pendaftar.jalur_pendaftaran', 'tsanawiyyah')
            ->orderBy('pendaftar.tanggal_daftar', 'DESC')
            ->limit(20)
            ->find();

        $data = [
            'title' => 'Dashboard Tsanawiyyah',
            'user' => $this->getUserData(),
            'jalur' => 'Tsanawiyyah',
            'total_registrations' => $total,
            'registrations' => $registrations
        ];

        return view('dashboard/jalur_dashboard', $data);
    }

    /**
     * Muallimin Dashboard - Only Muallimin data
     */
    public function muallimin()
    {
        // Get Muallimin statistics
        $total = $this->pendaftarModel->where('jalur_pendaftaran', 'muallimin')->countAllResults();

        // Get recent Muallimin registrations
        $registrations = $this->pendaftarModel
            ->select('pendaftar.*, alamat_pendaftar.desa, alamat_pendaftar.kecamatan')
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->where('pendaftar.jalur_pendaftaran', 'muallimin')
            ->orderBy('pendaftar.tanggal_daftar', 'DESC')
            ->limit(20)
            ->find();

        $data = [
            'title' => 'Dashboard Mu\'allimin',
            'user' => $this->getUserData(),
            'jalur' => 'Mu\'allimin',
            'total_registrations' => $total,
            'registrations' => $registrations
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
