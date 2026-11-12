<?php

namespace App\Controllers;

use App\Models\PendaftarModel;
use App\Models\AlamatModel;
use App\Models\AyahModel;
use App\Models\IbuModel;
use App\Models\WaliModel;
use App\Models\BansosModel;
use App\Models\SekolahModel;
use App\Models\PeriodeModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use App\Libraries\FormPendaftaran;
use App\Libraries\PerbaruiPendaftar;

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
        $idPeriode = $this->resolvePeriodeId();
        $perPage = 20;

        // Get statistics for both programs (with date filter if applicable)
        // Use separate builders to avoid query mixing
        $db = \Config\Database::connect();

        // Tsanawiyyah count
        $tsnBuilder = $this->scopePeriode($db->table('pendaftar'), $idPeriode)->where('jalur_pendaftaran', 'tsanawiyyah');
        if (!empty($startDate)) {
            $tsnBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $tsnBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $totalTsn = $tsnBuilder->countAllResults();

        // Muallimin count
        $muaBuilder = $this->scopePeriode($db->table('pendaftar'), $idPeriode)->where('jalur_pendaftaran', 'muallimin');
        if (!empty($startDate)) {
            $muaBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $muaBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $totalMua = $muaBuilder->countAllResults();

        // All count
        $allBuilder = $this->scopePeriode($db->table('pendaftar'), $idPeriode);
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

        // Batasi ke periode yang dipilih (default: periode aktif)
        $this->scopePeriode($builder, $idPeriode, 'pendaftar.id_periode');

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
        $maleCount = $this->scopePeriode($db->table('pendaftar'), $idPeriode)->where('jenis_kelamin', 'L')->countAllResults();
        $femaleCount = $this->scopePeriode($db->table('pendaftar'), $idPeriode)->where('jenis_kelamin', 'P')->countAllResults();

        // Registration time statistics
        $today = date('Y-m-d');
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $monthStart = date('Y-m-01');

        $todayCount = $this->scopePeriode($db->table('pendaftar'), $idPeriode)
            ->where('DATE(tanggal_daftar)', $today)
            ->countAllResults();
        $weekCount = $this->scopePeriode($db->table('pendaftar'), $idPeriode)
            ->where('tanggal_daftar >=', $weekStart . ' 00:00:00')
            ->countAllResults();
        $monthCount = $this->scopePeriode($db->table('pendaftar'), $idPeriode)
            ->where('tanggal_daftar >=', $monthStart . ' 00:00:00')
            ->countAllResults();

        // Parent income statistics (from data_ayah table)
        $incomeRanges = [
            'below_1m' => 0,
            '1m_3m' => 0,
            '3m_5m' => 0,
            'above_5m' => 0
        ];

        $incomeData = $this->scopePeriode(
                $db->table('data_ayah')->join('pendaftar', 'pendaftar.id_pendaftar = data_ayah.id_pendaftar', 'inner'),
                $idPeriode,
                'pendaftar.id_periode'
            )
            ->select('data_ayah.penghasilan_ayah AS penghasilan_ayah')
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

        // Distance statistics (kategori sesuai pilihan EMIS)
        $distanceRanges = array_fill_keys(FormPendaftaran::JARAK, 0);
        $distanceData = $this->scopePeriode(
                $db->table('alamat_pendaftar')->join('pendaftar', 'pendaftar.id_pendaftar = alamat_pendaftar.id_pendaftar', 'inner'),
                $idPeriode,
                'pendaftar.id_periode'
            )
            ->select('alamat_pendaftar.jarak_ke_sekolah AS jarak, COUNT(*) AS total')
            ->groupBy('alamat_pendaftar.jarak_ke_sekolah')
            ->get()
            ->getResultArray();
        foreach ($distanceData as $row) {
            if (isset($distanceRanges[$row['jarak']])) {
                $distanceRanges[$row['jarak']] = (int) $row['total'];
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
            'endDate' => $endDate,
            'periodeList' => (new PeriodeModel())->orderBy('id_periode', 'DESC')->findAll(),
            'periodeTerpilih' => $idPeriode ? (new PeriodeModel())->find($idPeriode) : null,
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
        $idPeriode = $this->resolvePeriodeId();
        $perPage = 20;

        // Get total for statistics (with date filter)
        $db = \Config\Database::connect();
        $totalBuilder = $this->scopePeriode($db->table('pendaftar'), $idPeriode)->where('jalur_pendaftaran', 'tsanawiyyah');
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

        // Batasi ke periode yang dipilih (default: periode aktif)
        $this->scopePeriode($builder, $idPeriode, 'pendaftar.id_periode');

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
            'endDate' => $endDate,
            'periodeTerpilih' => $idPeriode ? (new PeriodeModel())->find($idPeriode) : null,
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
        $idPeriode = $this->resolvePeriodeId();
        $perPage = 20;

        // Get total for statistics (with date filter)
        $db = \Config\Database::connect();
        $totalBuilder = $this->scopePeriode($db->table('pendaftar'), $idPeriode)->where('jalur_pendaftaran', 'muallimin');
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

        // Batasi ke periode yang dipilih (default: periode aktif)
        $this->scopePeriode($builder, $idPeriode, 'pendaftar.id_periode');

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
            'endDate' => $endDate,
            'periodeTerpilih' => $idPeriode ? (new PeriodeModel())->find($idPeriode) : null,
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
        if (!$this->bolehAksesJalur($pendaftar['jalur_pendaftaran'])) {
            return redirect()->to('/dashboard')->with('error', 'Anda hanya dapat mengakses pendaftar jalur Anda sendiri.');
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
     * Query lengkap (7 tabel) untuk export & unduh ZIP, dengan filter yang sama seperti dashboard.
     */
    private function queryExport(): array
    {
        $jalur     = strtolower($this->request->getGet('jalur') ?? 'all');
        $search    = $this->request->getGet('search') ?? '';
        $sortBy    = $this->request->getGet('sort') ?? 'tanggal_daftar';
        $sortDir   = $this->request->getGet('dir') ?? 'DESC';
        $startDate = $this->request->getGet('start_date') ?? '';
        $endDate   = $this->request->getGet('end_date') ?? '';
        $idPeriode = $this->resolvePeriodeId();

        // Panitia jalur hanya boleh mengambil data jalurnya sendiri
        $role = $this->session->get('role_panitia');
        if (in_array($role, ['tsanawiyyah', 'muallimin'], true)) {
            $jalur = $role;
        }
        if (!in_array($jalur, ['all', 'tsanawiyyah', 'muallimin'], true)) {
            $jalur = 'all';
        }

        // Ambil semua kolom tabel relasi (tanpa id & timestamp) agar tidak ada data yang terlewat
        $db     = \Config\Database::connect();
        $select = ['pendaftar.*'];
        foreach (['alamat_pendaftar', 'data_ayah', 'data_ibu', 'data_wali', 'bansos_pendaftar', 'asal_sekolah'] as $tabel) {
            foreach ($db->getFieldNames($tabel) as $kolom) {
                if ($kolom === 'id_pendaftar' || str_starts_with($kolom, 'id_') || in_array($kolom, ['created_at', 'updated_at', 'deleted_at'], true)) {
                    continue;
                }
                $select[] = $tabel . '.' . $kolom;
            }
        }
        $select[] = 'data_wali.id_wali AS ada_wali_id';
        $select[] = 'periode_pendaftaran.tahun_ajaran AS tahun_ajaran_periode';

        $builder = $this->pendaftarModel
            ->select(implode(', ', $select), false)
            ->join('alamat_pendaftar', 'alamat_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_ayah', 'data_ayah.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_ibu', 'data_ibu.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('data_wali', 'data_wali.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('bansos_pendaftar', 'bansos_pendaftar.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'asal_sekolah.id_pendaftar = pendaftar.id_pendaftar', 'left')
            ->join('periode_pendaftaran', 'periode_pendaftaran.id_periode = pendaftar.id_periode', 'left');

        $this->scopePeriode($builder, $idPeriode, 'pendaftar.id_periode');

        if ($jalur !== 'all') {
            $builder->where('pendaftar.jalur_pendaftaran', strtoupper($jalur));
        }

        if (!empty($search)) {
            $builder->groupStart()
                ->like('pendaftar.nama_lengkap', $search)
                ->orLike('pendaftar.nisn', $search)
                ->orLike('pendaftar.nik', $search)
                ->orLike('pendaftar.tempat_lahir', $search)
                ->orLike('alamat_pendaftar.kecamatan', $search)
                ->groupEnd();
        }

        if (!empty($startDate)) {
            $builder->where('pendaftar.tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $builder->where('pendaftar.tanggal_daftar <=', $endDate . ' 23:59:59');
        }

        $allowedSorts = ['nama_lengkap', 'tanggal_daftar', 'jenis_kelamin', 'tempat_lahir', 'kecamatan', 'jalur_pendaftaran'];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'tanggal_daftar';
        }
        $sortDir = strtoupper($sortDir) === 'ASC' ? 'ASC' : 'DESC';
        $kolomSort = $sortBy === 'kecamatan' ? 'alamat_pendaftar.kecamatan' : 'pendaftar.' . $sortBy;

        $rows = $builder->orderBy($kolomSort, $sortDir)->findAll();

        return [
            'rows'      => $rows,
            'jalur'     => $jalur,
            'idPeriode' => $idPeriode,
            'startDate' => $startDate,
            'endDate'   => $endDate,
        ];
    }

    /**
     * Definisi kolom export: [Judul Kolom => fungsi(row)].
     * Satu sumber untuk CSV & Excel sehingga keduanya selalu memuat seluruh isian formulir.
     */
    private function kolomExport(): array
    {
        $v   = fn (string $k) => fn (array $r) => (isset($r[$k]) && $r[$k] !== '') ? (string) $r[$k] : '-';
        $tgl = fn (string $k) => fn (array $r) => !empty($r[$k]) ? date('d/m/Y', strtotime($r[$k])) : '-';
        $ya  = fn (string $k) => fn (array $r) => !empty($r[$k]) ? 'Ya' : 'Tidak';

        $kolom = [
            // ---------------- Pendaftaran ----------------
            'Tahun Ajaran'           => $v('tahun_ajaran_periode'),
            'Nomor Pendaftaran'      => $v('nomor_pendaftaran'),
            'Jalur Pendaftaran'      => fn ($r) => ucfirst(strtolower($r['jalur_pendaftaran'] ?? '-')),
            'Tanggal Daftar'         => fn ($r) => !empty($r['tanggal_daftar']) ? date('d/m/Y H:i', strtotime($r['tanggal_daftar'])) : '-',

            // ---------------- Data Diri ----------------
            'NISN'                   => $v('nisn'),
            'NIK'                    => $v('nik'),
            'Nama Lengkap'           => $v('nama_lengkap'),
            'Jenis Kelamin'          => fn ($r) => ['L' => 'Laki-laki', 'P' => 'Perempuan'][$r['jenis_kelamin'] ?? ''] ?? '-',
            'Agama'                  => $v('agama'),
            'Tempat Lahir'           => $v('tempat_lahir'),
            'Tanggal Lahir'          => $tgl('tanggal_lahir'),
            'Status dalam Keluarga'  => $v('status_keluarga'),
            'Anak Ke'                => $v('anak_ke'),
            'Jumlah Saudara'         => $v('jumlah_saudara'),
            'Hobi'                   => $v('hobi'),
            'Cita-cita'              => $v('cita_cita'),
            'No HP Santri'           => $v('no_hp'),
            'Email Santri'           => $v('email'),
            'Yang Membiayai Sekolah' => $v('yang_membiayai_sekolah'),
            'Pernah PAUD'            => $ya('pernah_paud'),
            'Pernah TK'              => $ya('pernah_tk'),
            'Kebutuhan Disabilitas'  => $v('kebutuhan_disabilitas'),
            'Kebutuhan Khusus'       => $v('kebutuhan_khusus'),
            'Riwayat Imunisasi'      => $v('imunisasi'),
            'Ukuran Baju'            => $v('ukuran_baju'),
            'Prestasi'               => $v('prestasi'),
            'Minat & Bakat'          => $v('minat_bakat'),
            'File Kartu Keluarga'    => fn ($r) => FormPendaftaran::pathKk($r['file_kk'] ?? null) ? FormPendaftaran::namaFileUnduhKk($r) : 'Belum diunggah',

            // ---------------- Tempat Tinggal ----------------
            'Nomor KK'               => $v('nomor_kk'),
            'Nama Kepala Keluarga'   => $v('nama_kepala_keluarga'),
            'Jenis Tempat Tinggal'   => $v('jenis_tempat_tinggal'),
            'Alamat'                 => $v('alamat'),
            'RT/RW'                  => $v('rt_rw'),
            'Desa/Kelurahan'         => $v('desa'),
            'Kecamatan'              => $v('kecamatan'),
            'Kabupaten/Kota'         => $v('kabupaten'),
            'Provinsi'               => $v('provinsi'),
            'Kode Pos'               => $v('kode_pos'),
            'Status Tempat Tinggal Santri' => $v('tinggal_bersama'),
            'Jarak ke Sekolah'       => $v('jarak_ke_sekolah'),
            'Waktu Tempuh'           => $v('waktu_tempuh'),
            'Transportasi'           => $v('transportasi'),
            'Media Sosial'           => $v('media_sosial'),
        ];

        foreach (['ayah' => 'Ayah', 'ibu' => 'Ibu', 'wali' => 'Wali'] as $p => $L) {
            if ($p === 'wali') {
                $kolom['Ada Wali']          = fn ($r) => !empty($r['ada_wali_id']) ? 'Ya' : 'Tidak';
                $kolom['Status/Hubungan Wali'] = $v('hubungan_wali');
            } else {
                $kolom['Status ' . $L] = $v('status_' . $p);
            }
            $kolom += [
                'NIK ' . $L                   => $v('nik_' . $p),
                'Nama ' . $L                  => $v('nama_' . $p),
                'Tempat Lahir ' . $L          => $v('tempat_lahir_' . $p),
                'Tanggal Lahir ' . $L         => $tgl('tanggal_lahir_' . $p),
                'Pendidikan ' . $L            => $v('pendidikan_' . $p),
                'Pekerjaan ' . $L             => $v('pekerjaan_' . $p),
                'Domisili ' . $L              => $v('domisili_' . $p),
                'No HP ' . $L                 => $v('hp_' . $p),
                'Penghasilan ' . $L           => $v('penghasilan_' . $p),
                'Alamat ' . $L                => $v('alamat_' . $p),
                'Status Tempat Tinggal ' . $L => $v('status_tempat_tinggal_' . $p),
            ];
        }

        $kolom += [
            // ---------------- Bansos ----------------
            'No. KKS'          => $v('no_kks'),
            'No. PKH'          => $v('no_pkh'),
            'No. KIP'          => $v('no_kip'),

            // ---------------- Asal Sekolah ----------------
            'NPSN'             => $v('npsn'),
            'Nama Asal Sekolah' => $v('nama_asal_sekolah'),
            'Jenjang Sekolah'  => $v('jenjang_sekolah'),
            'Status Sekolah'   => $v('status_sekolah'),
            'Lokasi Sekolah'   => $v('lokasi_sekolah'),
            'Alamat Sekolah'   => $v('alamat_sekolah'),
            'Tahun Lulus'      => $v('tahun_lulus'),
            'Rata-rata Rapor'  => $v('rata_rata_rapor'),
            'Nilai TKA'        => $v('nilai_tka'),
            'Sekolah MD'       => $v('sekolah_md'),
            'Asal Jenjang'     => $v('asal_jenjang'),
        ];

        return $kolom;
    }

    private function namaFileExport(array $q, string $ext): string
    {
        return 'pendaftar_' . $this->slugPeriode($q['idPeriode']) . ($q['jalur'] !== 'all' ? $q['jalur'] . '_' : '') . date('Y-m-d_His') . '.' . $ext;
    }

    /**
     * Export data to CSV (seluruh isian formulir)
     */
    public function exportCsv()
    {
        $q     = $this->queryExport();
        $kolom = $this->kolomExport();

        $filename = $this->namaFileExport($q, 'csv');

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Pragma: no-cache');
        header('Expires: 0');

        $output = fopen('php://output', 'w');
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF)); // BOM agar UTF-8 terbaca di Excel

        fputcsv($output, array_merge(['No'], array_keys($kolom)), ',', '"', '\\');

        $no = 1;
        foreach ($q['rows'] as $row) {
            $baris = [$no++];
            foreach ($kolom as $fn) {
                $baris[] = $fn($row);
            }
            fputcsv($output, $baris, ',', '"', '\\');
        }

        fclose($output);
        exit;
    }

    /**
     * Export data to Excel (seluruh isian formulir, angka panjang disimpan sebagai teks)
     */
    public function exportExcel()
    {
        $q     = $this->queryExport();
        $kolom = $this->kolomExport();
        $data  = $q['rows'];
        $jalur = $q['jalur'];

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getProperties()
            ->setCreator(pengaturan('nama_singkat', 'PSB'))
            ->setTitle('Data Pendaftar')
            ->setSubject('Laporan Pendaftaran Santri')
            ->setDescription('Laporan data pendaftaran santri ' . nama_lembaga());

        // ---------------- Sheet Ringkasan ----------------
        $summarySheet = $spreadsheet->getActiveSheet();
        $summarySheet->setTitle('Ringkasan');
        $summarySheet->setCellValue('A1', 'LAPORAN DATA PENDAFTAR');
        $summarySheet->setCellValue('A2', strtoupper('PSB ' . nama_lembaga()));
        $summarySheet->mergeCells('A1:D1');
        $summarySheet->mergeCells('A2:D2');
        $summarySheet->getStyle('A1:D2')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        $periodeExport = $q['idPeriode'] ? (new PeriodeModel())->find($q['idPeriode']) : null;
        $row = 4;
        $info = [
            'Tahun Ajaran:'    => $periodeExport['tahun_ajaran'] ?? 'Semua Periode',
            'Jalur:'           => $jalur === 'all' ? 'Semua Jalur' : ucfirst($jalur),
            'Total Pendaftar:' => count($data),
        ];
        if (!empty($q['startDate']) || !empty($q['endDate'])) {
            $info['Periode Tanggal:'] = trim((!empty($q['startDate']) ? date('d/m/Y', strtotime($q['startDate'])) : '') . ' - ' . (!empty($q['endDate']) ? date('d/m/Y', strtotime($q['endDate'])) : ''), ' -');
        }
        $info['Tanggal Export:'] = date('d/m/Y H:i:s');
        foreach ($info as $label => $nilai) {
            $summarySheet->setCellValue('A' . $row, $label);
            $summarySheet->setCellValue('B' . $row, $nilai);
            $row++;
        }
        $summarySheet->getStyle('A4:A' . ($row - 1))->applyFromArray(['font' => ['bold' => true]]);

        $row++;
        $summarySheet->setCellValue('A' . $row, 'Statistik Per Jalur');
        $summarySheet->getStyle('A' . $row)->applyFromArray(['font' => ['bold' => true, 'size' => 12]]);
        $row++;
        $tsnCount = count(array_filter($data, fn ($d) => strtoupper($d['jalur_pendaftaran']) === 'TSANAWIYYAH'));
        $summarySheet->setCellValue('A' . $row, 'Tsanawiyyah:');
        $summarySheet->setCellValue('B' . $row, $tsnCount);
        $row++;
        $summarySheet->setCellValue('A' . $row, 'Mu\'allimin:');
        $summarySheet->setCellValue('B' . $row, count($data) - $tsnCount);
        $summarySheet->getColumnDimension('A')->setWidth(20);
        $summarySheet->getColumnDimension('B')->setWidth(30);

        // ---------------- Sheet Data ----------------
        $dataSheet = $spreadsheet->createSheet();
        $dataSheet->setTitle('Data Pendaftar');

        $headers  = array_merge(['No'], array_keys($kolom));
        $lastCol  = Coordinate::stringFromColumnIndex(count($headers));

        foreach ($headers as $i => $header) {
            $dataSheet->setCellValue(Coordinate::stringFromColumnIndex($i + 1) . '1', $header);
        }
        $dataSheet->getStyle('A1:' . $lastCol . '1')->applyFromArray([
            'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1AB34A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => '000000']]],
        ]);

        $rowNum = 2;
        $no = 1;
        foreach ($data as $r) {
            $dataSheet->setCellValue('A' . $rowNum, $no++);
            $c = 2;
            foreach ($kolom as $fn) {
                // Simpan sebagai teks agar NIK/NISN/No HP tidak kehilangan angka 0 di depan
                $dataSheet->setCellValueExplicit(Coordinate::stringFromColumnIndex($c) . $rowNum, $fn($r), DataType::TYPE_STRING);
                $c++;
            }
            if ($rowNum % 2 == 0) {
                $dataSheet->getStyle('A' . $rowNum . ':' . $lastCol . $rowNum)->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F8F9FA']],
                ]);
            }
            $rowNum++;
        }

        $dataSheet->getStyle('A1:' . $lastCol . max(1, $rowNum - 1))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CCCCCC']]],
        ]);
        for ($i = 1; $i <= count($headers); $i++) {
            $dataSheet->getColumnDimension(Coordinate::stringFromColumnIndex($i))->setAutoSize(true);
        }
        $dataSheet->freezePane('C2');

        $filename = $this->namaFileExport($q, 'xlsx');
        $spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        header('Pragma: no-cache');

        $writer->save('php://output');
        exit;
    }

    /**
     * Lihat / unduh file Kartu Keluarga satu pendaftar.
     * ?unduh=1 untuk mengunduh dengan nama "Nama Siswa_NISN.jpg".
     */
    public function fileKk($id)
    {
        $pendaftar = $this->pendaftarModel->find((int) $id);
        if (!$pendaftar || !$this->bolehAksesJalur($pendaftar['jalur_pendaftaran'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $path = FormPendaftaran::pathKk($pendaftar['file_kk'] ?? null);
        if (!$path) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('File Kartu Keluarga belum diunggah.');
        }

        $nama = FormPendaftaran::namaFileUnduhKk($pendaftar);

        if ($this->request->getGet('unduh')) {
            return $this->response->download($path, null)->setFileName($nama);
        }

        return $this->response
            ->setHeader('Content-Type', 'image/jpeg')
            ->setHeader('Content-Disposition', 'inline; filename="' . str_replace('"', '', $nama) . '"')
            ->setHeader('Cache-Control', 'private, max-age=300')
            ->setHeader('X-Content-Type-Options', 'nosniff')
            ->setBody(file_get_contents($path));
    }

    /**
     * Unduh ZIP berisi file Kartu Keluarga sesuai filter (jalur/periode/pencarian/tanggal).
     * Nama tiap file: "Nama Siswa_NISN.jpg".
     */
    public function downloadKkZip()
    {
        if (!class_exists(\ZipArchive::class)) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Ekstensi PHP zip tidak tersedia di server.');
        }

        $q = $this->queryExport();

        $tmp = tempnam(WRITEPATH . 'cache', 'kkzip_');
        $zip = new \ZipArchive();
        if ($zip->open($tmp, \ZipArchive::OVERWRITE) !== true) {
            return redirect()->to(base_url('dashboard'))->with('error', 'Gagal membuat file ZIP.');
        }

        $dipakai = [];
        $jumlah  = 0;
        $tanpaKk = [];
        foreach ($q['rows'] as $r) {
            $path = FormPendaftaran::pathKk($r['file_kk'] ?? null);
            if (!$path) {
                $tanpaKk[] = ($r['nomor_pendaftaran'] ?? '-') . ' - ' . ($r['nama_lengkap'] ?? '-');
                continue;
            }
            $nama = FormPendaftaran::namaFileUnduhKk($r);
            $dasar = substr($nama, 0, -4);
            $i = 2;
            while (isset($dipakai[strtolower($nama)])) {
                $nama = $dasar . ' (' . $i++ . ').jpg';
            }
            $dipakai[strtolower($nama)] = true;
            $zip->addFile($path, $nama);
            $jumlah++;
        }

        if ($tanpaKk !== []) {
            $zip->addFromString('_BELUM_UPLOAD_KK.txt', "Pendaftar yang belum mengunggah Kartu Keluarga:\r\n" . implode("\r\n", $tanpaKk) . "\r\n");
        }
        $zip->close();

        if ($jumlah === 0) {
            @unlink($tmp);
            return redirect()->to(base_url('dashboard'))->with('error', 'Belum ada file Kartu Keluarga yang diunggah pada data yang dipilih.');
        }

        register_shutdown_function(static function () use ($tmp) {
            @unlink($tmp);
        });

        $namaZip = 'Kartu_Keluarga_' . $this->slugPeriode($q['idPeriode']) . ($q['jalur'] !== 'all' ? $q['jalur'] . '_' : '') . date('Y-m-d_His') . '.zip';

        return $this->response->download($tmp, null)->setFileName($namaZip);
    }

    /**
     * Panitia jalur hanya boleh mengakses pendaftar jalurnya sendiri.
     */
    private function bolehAksesJalur(?string $jalur): bool
    {
        $role = $this->session->get('role_panitia');
        if ($role === 'superadmin') {
            return true;
        }

        return strtolower((string) $jalur) === $role;
    }

    /**
     * Form edit data pendaftar oleh panitia/superadmin
     * (membantu memperbaiki data yang salah isi).
     */
    public function edit($id)
    {
        $pendaftar = $this->pendaftarModel->find((int) $id);
        if (!$pendaftar) {
            return redirect()->to('/dashboard')->with('error', 'Data pendaftar tidak ditemukan.');
        }
        if (!$this->bolehAksesJalur($pendaftar['jalur_pendaftaran'])) {
            return redirect()->to('/dashboard')->with('error', 'Anda hanya dapat mengubah pendaftar jalur Anda sendiri.');
        }

        $id = (int) $pendaftar['id_pendaftar'];

        return view('update_data/form_edit', [
            'title'       => 'Edit Data ' . $pendaftar['nama_lengkap'] . ' - Panitia',
            'jalur'       => $pendaftar['jalur_pendaftaran'],
            'jalur_label' => $pendaftar['jalur_pendaftaran'] === 'TSANAWIYYAH' ? 'Tsanawiyyah' : "Mu'allimin",
            'year'        => \App\Models\PeriodeModel::tahunMulai(tahun_ajaran_pendaftar($pendaftar)),
            'pendaftar'   => $pendaftar,
            'alamat'      => (new AlamatModel())->where('id_pendaftar', $id)->first(),
            'ayah'        => (new AyahModel())->where('id_pendaftar', $id)->first(),
            'ibu'         => (new IbuModel())->where('id_pendaftar', $id)->first(),
            'wali'        => (new WaliModel())->where('id_pendaftar', $id)->first(),
            'bansos'      => (new BansosModel())->where('id_pendaftar', $id)->first(),
            'sekolah'     => (new SekolahModel())->where('id_pendaftar', $id)->first(),
            'modeAdmin'   => true,
            'formAction'  => base_url('dashboard/update/' . $id),
            'batalUrl'    => base_url('dashboard/detail/' . $id),
        ]);
    }

    /**
     * Simpan perubahan data pendaftar oleh panitia/superadmin.
     */
    public function update($id)
    {
        $pendaftarLama = $this->pendaftarModel->find((int) $id);
        if (!$pendaftarLama) {
            return redirect()->to('/dashboard')->with('error', 'Data pendaftar tidak ditemukan.');
        }
        if (!$this->bolehAksesJalur($pendaftarLama['jalur_pendaftaran'])) {
            return redirect()->to('/dashboard')->with('error', 'Anda hanya dapat mengubah pendaftar jalur Anda sendiri.');
        }

        $id       = (int) $pendaftarLama['id_pendaftar'];
        $perbarui = new PerbaruiPendaftar($this->request);
        [$rules, $messages] = $perbarui->aturan($pendaftarLama);

        if (!$this->validate($rules, $messages)) {
            return redirect()->to(base_url('dashboard/edit/' . $id))->withInput()
                ->with('error', 'Data tidak valid! Mohon periksa kembali formulir.')
                ->with('validation_errors', $this->validator->getErrors());
        }

        $duplikat = $perbarui->cekDuplikat($pendaftarLama);
        if ($duplikat !== []) {
            return redirect()->to(base_url('dashboard/edit/' . $id))->withInput()
                ->with('error', 'NISN/NIK sudah dipakai pendaftar lain pada periode ini.')
                ->with('validation_errors', $duplikat);
        }

        try {
            $perbarui->simpan($id, $pendaftarLama);
        } catch (\Exception $e) {
            log_message('error', '[PSB-ADMIN] Gagal edit pendaftar ID ' . $id . ': ' . $e->getMessage());

            return redirect()->to(base_url('dashboard/edit/' . $id))->withInput()
                ->with('error', 'Gagal menyimpan perubahan: ' . $e->getMessage());
        }

        log_message('info', '[PSB-ADMIN] Data pendaftar ' . $pendaftarLama['nomor_pendaftaran'] . ' (ID ' . $id . ') diubah oleh ' . $this->session->get('username'));

        return redirect()->to(base_url('dashboard/detail/' . $id))
            ->with('success', 'Data pendaftar ' . $pendaftarLama['nomor_pendaftaran'] . ' berhasil diperbarui.');
    }

    /**
     * Hapus pendaftar (permanen, 7 tabel + file KK).
     * Superadmin: semua jalur. Panitia: hanya jalurnya sendiri.
     */
    public function delete($id)
    {
        $pendaftar = $this->pendaftarModel->find((int) $id);

        if (!$pendaftar) {
            return redirect()->to('/dashboard')->with('error', 'Data pendaftar tidak ditemukan');
        }
        if (!$this->bolehAksesJalur($pendaftar['jalur_pendaftaran'])) {
            return redirect()->to('/dashboard')->with('error', 'Anda hanya dapat menghapus pendaftar jalur Anda sendiri.');
        }

        $namaPendaftar    = $pendaftar['nama_lengkap'];
        $nomorPendaftaran = $pendaftar['nomor_pendaftaran'];

        if (!$this->pendaftarModel->hardDeletePendaftar((int) $pendaftar['id_pendaftar'])) {
            return redirect()->to('/dashboard')->with('error', 'Gagal menghapus data pendaftar. Silakan coba lagi.');
        }

        FormPendaftaran::hapusFileKk($pendaftar['file_kk'] ?? null);

        log_message('info', "Pendaftar dihapus: {$namaPendaftar} ({$nomorPendaftaran}) - ID: {$pendaftar['id_pendaftar']} - Oleh: "
            . $this->session->get('username') . ' (' . $this->session->get('role_panitia') . ')');

        return redirect()->to('/dashboard')
            ->with('success', "Data pendaftar {$namaPendaftar} ({$nomorPendaftaran}) berhasil dihapus permanen");
    }

    /**
     * Periode yang ditampilkan.
     * - Superadmin dapat memilih periode lain (termasuk arsip) via ?periode=ID
     * - Panitia lain selalu melihat periode aktif
     */
    private function resolvePeriodeId(): ?int
    {
        $periodeModel = new PeriodeModel();
        $aktif        = $periodeModel->getAktif();
        $diminta      = (int) ($this->request->getGet('periode') ?? 0);

        if ($diminta > 0 && $this->session->get('role_panitia') === 'superadmin') {
            if ($periodeModel->find($diminta)) {
                return $diminta;
            }
        }

        return $aktif ? (int) $aktif['id_periode'] : null;
    }

    /**
     * Tambahkan filter periode ke query builder.
     */
    private function scopePeriode($builder, ?int $idPeriode, string $column = 'id_periode')
    {
        if ($idPeriode) {
            $builder->where($column, $idPeriode);
        }

        return $builder;
    }

    private function slugPeriode(?int $idPeriode): string
    {
        if (!$idPeriode) {
            return '';
        }
        $periode = (new PeriodeModel())->find($idPeriode);

        return $periode ? preg_replace('/[^0-9A-Za-z]+/', '-', $periode['tahun_ajaran']) . '_' : '';
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
