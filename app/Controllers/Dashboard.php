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
        $tsnBuilder = $this->pendaftarModel->where('jalur_pendaftaran', 'tsanawiyyah');
        $muaBuilder = $this->pendaftarModel->where('jalur_pendaftaran', 'muallimin');
        $allBuilder = $this->pendaftarModel;

        if (!empty($startDate)) {
            $tsnBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
            $muaBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
            $allBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $tsnBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
            $muaBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
            $allBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }

        $totalTsn = $tsnBuilder->countAllResults();
        $totalMua = $muaBuilder->countAllResults();
        $totalAll = $allBuilder->countAllResults();

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
        $totalBuilder = $this->pendaftarModel->where('jalur_pendaftaran', 'tsanawiyyah');
        if (!empty($startDate)) {
            $totalBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $totalBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $total = $totalBuilder->countAllResults();

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
        $totalBuilder = $this->pendaftarModel->where('jalur_pendaftaran', 'muallimin');
        if (!empty($startDate)) {
            $totalBuilder->where('tanggal_daftar >=', $startDate . ' 00:00:00');
        }
        if (!empty($endDate)) {
            $totalBuilder->where('tanggal_daftar <=', $endDate . ' 23:59:59');
        }
        $total = $totalBuilder->countAllResults();

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
