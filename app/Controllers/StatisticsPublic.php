<?php

namespace App\Controllers;

use App\Libraries\FormPendaftaran;
use App\Models\PendaftarModel;

class StatisticsPublic extends BaseController
{
    protected $pendaftarModel;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
    }

    /**
     * Display the public statistics page
     *
     * @return string
     */
    public function index(): string
    {
        $data = [
            'tahun_ajaran' => tahun_ajaran_aktif(),
            'title' => 'Statistik Pendaftar - Pesantren Persatuan Islam 31 Banjaran',
            'meta_description' => 'Statistik dan data pendaftar santri baru Pesantren Persatuan Islam 31 Banjaran',
            'meta_keywords' => 'statistik pendaftar, data pendaftar, pesantren persis 31, banjaran',
        ];

        return view('statistics/index', $data);
    }

    /**
     * API: Get statistics by jalur pendaftaran
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByJalur()
    {
        $db = \Config\Database::connect();

        $jalurStats = $this->scopePendaftar($db->table('pendaftar'))
            ->select('jalur_pendaftaran, COUNT(*) as total')
            ->groupBy('jalur_pendaftaran')
            ->get()
            ->getResultArray();

        $data = [
            'labels' => [],
            'values' => []
        ];

        foreach ($jalurStats as $row) {
            $data['labels'][] = ucfirst(strtolower($row['jalur_pendaftaran']));
            $data['values'][] = (int)$row['total'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * API: Get statistics by registration date
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByTanggalDaftar()
    {
        $db = \Config\Database::connect();

        $dateStats = $this->scopePendaftar($db->table('pendaftar'))
            ->select('DATE(tanggal_daftar) as tanggal, COUNT(*) as total')
            ->groupBy('DATE(tanggal_daftar)')
            ->orderBy('tanggal', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'labels' => [],
            'values' => []
        ];

        foreach ($dateStats as $row) {
            $data['labels'][] = date('d M Y', strtotime($row['tanggal']));
            $data['values'][] = (int)$row['total'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * API: Get statistics by age groups
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByUsia()
    {
        $db = \Config\Database::connect();

        $pendaftarData = $this->scopePendaftar($db->table('pendaftar'))
            ->select('tanggal_lahir')
            ->get()
            ->getResultArray();

        $ageRanges = [
            '< 12 tahun' => 0,
            '12-13 tahun' => 0,
            '14-15 tahun' => 0,
            '16-17 tahun' => 0,
            '> 17 tahun' => 0
        ];

        foreach ($pendaftarData as $row) {
            if (!empty($row['tanggal_lahir'])) {
                $birthDate = new \DateTime($row['tanggal_lahir']);
                $today = new \DateTime();
                $age = $today->diff($birthDate)->y;

                if ($age < 12) {
                    $ageRanges['< 12 tahun']++;
                } elseif ($age >= 12 && $age <= 13) {
                    $ageRanges['12-13 tahun']++;
                } elseif ($age >= 14 && $age <= 15) {
                    $ageRanges['14-15 tahun']++;
                } elseif ($age >= 16 && $age <= 17) {
                    $ageRanges['16-17 tahun']++;
                } else {
                    $ageRanges['> 17 tahun']++;
                }
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'labels' => array_keys($ageRanges),
                'values' => array_values($ageRanges)
            ]
        ]);
    }

    /**
     * API: Get statistics by desa (village)
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByDesa()
    {
        $db = \Config\Database::connect();

        $desaStats = $this->scopeRelasi($db->table('alamat_pendaftar'), 'alamat_pendaftar')
            ->select('desa, COUNT(*) as total')
            ->groupBy('desa')
            ->orderBy('total', 'DESC')
            ->limit(10) // Top 10 desa
            ->get()
            ->getResultArray();

        $data = [
            'labels' => [],
            'values' => []
        ];

        foreach ($desaStats as $row) {
            $data['labels'][] = $row['desa'] ?? 'Tidak diisi';
            $data['values'][] = (int)$row['total'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * API: Get statistics by jarak ke sekolah (kategori sesuai pilihan EMIS)
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByJarak()
    {
        return $this->response->setJSON([
            'success' => true,
            'data'    => $this->hitungKategori('jarak_ke_sekolah', FormPendaftaran::JARAK),
        ]);
    }

    /**
     * API: Get statistics by waktu tempuh (kategori sesuai pilihan EMIS)
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByWaktuTempuh()
    {
        return $this->response->setJSON([
            'success' => true,
            'data'    => $this->hitungKategori('waktu_tempuh', FormPendaftaran::WAKTU_TEMPUH),
        ]);
    }

    /**
     * Hitung jumlah per kategori pada alamat_pendaftar (periode aktif), urut sesuai daftar pilihan.
     * Nilai di luar daftar (data lama) dikumpulkan sebagai "Lainnya (data lama)".
     */
    private function hitungKategori(string $kolom, array $kategori): array
    {
        $db = \Config\Database::connect();

        $rows = $this->scopeRelasi($db->table('alamat_pendaftar'), 'alamat_pendaftar')
            ->select("alamat_pendaftar.{$kolom} AS nilai, COUNT(*) AS total")
            ->groupBy("alamat_pendaftar.{$kolom}")
            ->get()
            ->getResultArray();

        $hitung = array_fill_keys($kategori, 0);
        $lainnya = 0;
        foreach ($rows as $row) {
            if (array_key_exists((string) $row['nilai'], $hitung)) {
                $hitung[$row['nilai']] = (int) $row['total'];
            } elseif ((string) $row['nilai'] !== '') {
                $lainnya += (int) $row['total'];
            }
        }
        if ($lainnya > 0) {
            $hitung['Lainnya (data lama)'] = $lainnya;
        }

        return ['labels' => array_keys($hitung), 'values' => array_values($hitung)];
    }

    /**
     * API: Get statistics by status asal sekolah
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByStatusSekolah()
    {
        $db = \Config\Database::connect();

        // Get jenjang_sekolah as status
        $sekolahStats = $this->scopeRelasi($db->table('asal_sekolah'), 'asal_sekolah')
            ->select('jenjang_sekolah, COUNT(*) as total')
            ->groupBy('jenjang_sekolah')
            ->get()
            ->getResultArray();

        $data = [
            'labels' => [],
            'values' => []
        ];

        foreach ($sekolahStats as $row) {
            $data['labels'][] = $row['jenjang_sekolah'] ?? 'Tidak diisi';
            $data['values'][] = (int)$row['total'];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * API: Get statistics by penghasilan ayah (family income)
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getByPenghasilan()
    {
        $db = \Config\Database::connect();

        $incomeData = $this->scopeRelasi($db->table('data_ayah'), 'data_ayah')
            ->select('penghasilan_ayah')
            ->get()
            ->getResultArray();

        $incomeRanges = [
            '< Rp 1 juta' => 0,
            'Rp 1-3 juta' => 0,
            'Rp 3-5 juta' => 0,
            '> Rp 5 juta' => 0
        ];

        foreach ($incomeData as $row) {
            $income = (int)$row['penghasilan_ayah'];

            if ($income < 1000000) {
                $incomeRanges['< Rp 1 juta']++;
            } elseif ($income >= 1000000 && $income < 3000000) {
                $incomeRanges['Rp 1-3 juta']++;
            } elseif ($income >= 3000000 && $income < 5000000) {
                $incomeRanges['Rp 3-5 juta']++;
            } else {
                $incomeRanges['> Rp 5 juta']++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => [
                'labels' => array_keys($incomeRanges),
                'values' => array_values($incomeRanges)
            ]
        ]);
    }

    /**
     * API: Get registrant data table by jalur
     *
     * @return \CodeIgniter\HTTP\ResponseInterface
     */
    public function getTableData()
    {
        $jalur = $this->request->getGet('jalur') ?? 'all';

        $db = \Config\Database::connect();

        $builder = $db->table('pendaftar p')
            ->select('p.nomor_pendaftaran, p.nama_lengkap, p.jenis_kelamin, p.jalur_pendaftaran, s.nama_asal_sekolah')
            ->join('asal_sekolah s', 's.id_pendaftar = p.id_pendaftar', 'left')
            ->orderBy('p.tanggal_daftar', 'DESC');
        $this->scopePendaftar($builder, 'p');

        if ($jalur !== 'all') {
            $builder->where('p.jalur_pendaftaran', strtoupper($jalur));
        }

        $results = $builder->get()->getResultArray();

        $data = [];
        foreach ($results as $row) {
            $data[] = [
                'nomor_pendaftaran' => $row['nomor_pendaftaran'],
                'nama_lengkap' => $row['nama_lengkap'],
                'jenis_kelamin' => $row['jenis_kelamin'],
                'jalur_pendaftaran' => ucfirst(strtolower($row['jalur_pendaftaran'])),
                'asal_sekolah' => $row['nama_asal_sekolah'] ?? '-'
            ];
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Batasi query tabel pendaftar ke periode aktif.
     */
    private function scopePendaftar($builder, string $alias = 'pendaftar')
    {
        $idPeriode = periode_aktif_id();
        if ($idPeriode) {
            $builder->where($alias . '.id_periode', $idPeriode);
        }
        $builder->where($alias . '.deleted_at', null);

        return $builder;
    }

    /**
     * Batasi query tabel relasi (alamat/ayah/sekolah) ke pendaftar periode aktif.
     */
    private function scopeRelasi($builder, string $table)
    {
        $builder->join('pendaftar psb_p', 'psb_p.id_pendaftar = ' . $table . '.id_pendaftar', 'inner');

        return $this->scopePendaftar($builder, 'psb_p');
    }
}
