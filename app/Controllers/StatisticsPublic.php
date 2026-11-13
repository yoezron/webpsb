<?php

namespace App\Controllers;

use App\Libraries\FormPendaftaran;
use App\Models\PeriodeModel;
use CodeIgniter\Database\BaseConnection;

/**
 * Laman statistik pendaftar publik (/statistik) + endpoint JSON-nya.
 *
 * Semua angka dibatasi ke periode aktif dan mengabaikan pendaftar yang dihapus.
 * Laman utama menyematkan seluruh data sekaligus (tanpa 9 request AJAX terpisah);
 * endpoint /api/statistik/* tetap tersedia untuk kebutuhan lain.
 */
class StatisticsPublic extends BaseController
{
    private const JALUR = ['TSANAWIYYAH' => 'Tsanawiyyah', 'MUALLIMIN' => "Mu'allimin"];

    private const JK = ['L' => 'Laki-laki', 'P' => 'Perempuan'];

    private const BULAN = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

    protected BaseConnection $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index(): string
    {
        $periode = (new PeriodeModel())->getAktif();

        return view('statistics/index', [
            'title'              => 'Statistik Pendaftar - ' . nama_lembaga(),
            'meta_description'   => 'Statistik dan data pendaftar santri baru ' . nama_lembaga(),
            'meta_keywords'      => 'statistik pendaftar, data pendaftar, ' . pengaturan('meta_keywords', ''),
            'tahun_ajaran'       => tahun_ajaran_aktif(),
            'periode'            => $periode,
            'status_pendaftaran' => (new PeriodeModel())->statusPendaftaran(),
            'ringkasan'          => $this->dataRingkasan($periode),
            'grafik'             => [
                'jalur'       => $this->dataJalur(),
                'tanggal'     => $this->dataTanggal(),
                'usia'        => $this->dataUsia(),
                'desa'        => $this->dataDesa(),
                'jarak'       => $this->hitungKategori('jarak_ke_sekolah', FormPendaftaran::JARAK),
                'waktu'       => $this->hitungKategori('waktu_tempuh', FormPendaftaran::WAKTU_TEMPUH),
                'sekolah'     => $this->dataStatusSekolah(),
                'jenjang'     => $this->dataJenjangSekolah(),
                'penghasilan' => $this->dataPenghasilan(),
            ],
            'tabel'              => $this->dataTabel(),
        ]);
    }

    // =====================================================================
    // Endpoint JSON
    // =====================================================================

    public function getByJalur()
    {
        return $this->json($this->dataJalur());
    }

    public function getByTanggalDaftar()
    {
        return $this->json($this->dataTanggal());
    }

    public function getByUsia()
    {
        return $this->json($this->dataUsia());
    }

    public function getByDesa()
    {
        return $this->json($this->dataDesa());
    }

    public function getByJarak()
    {
        return $this->json($this->hitungKategori('jarak_ke_sekolah', FormPendaftaran::JARAK));
    }

    public function getByWaktuTempuh()
    {
        return $this->json($this->hitungKategori('waktu_tempuh', FormPendaftaran::WAKTU_TEMPUH));
    }

    public function getByStatusSekolah()
    {
        return $this->json($this->dataStatusSekolah());
    }

    public function getByPenghasilan()
    {
        return $this->json($this->dataPenghasilan());
    }

    public function getTableData()
    {
        $jalur = strtoupper((string) ($this->request->getGet('jalur') ?? 'all'));

        return $this->json($this->dataTabel(array_key_exists($jalur, self::JALUR) ? $jalur : null));
    }

    private function json(array $data)
    {
        return $this->response->setJSON(['success' => true, 'data' => $data]);
    }

    // =====================================================================
    // Perhitungan
    // =====================================================================

    /**
     * Angka ringkas untuk kartu di bagian atas.
     */
    private function dataRingkasan(?array $periode): array
    {
        $rows = $this->scopePendaftar($this->db->table('pendaftar'))
            ->select('jalur_pendaftaran, jenis_kelamin, COUNT(*) AS total')
            ->groupBy(['jalur_pendaftaran', 'jenis_kelamin'])
            ->get()->getResultArray();

        $hasil = ['total' => 0, 'TSANAWIYYAH' => 0, 'MUALLIMIN' => 0, 'L' => 0, 'P' => 0];
        foreach ($rows as $r) {
            $n = (int) $r['total'];
            $hasil['total'] += $n;
            if (isset($hasil[$r['jalur_pendaftaran']])) {
                $hasil[$r['jalur_pendaftaran']] += $n;
            }
            if (isset($hasil[$r['jenis_kelamin']])) {
                $hasil[$r['jenis_kelamin']] += $n;
            }
        }

        $hasil['hari_ini'] = (int) $this->scopePendaftar($this->db->table('pendaftar'))
            ->where('DATE(tanggal_daftar)', date('Y-m-d'))
            ->countAllResults();
        $hasil['tujuh_hari'] = (int) $this->scopePendaftar($this->db->table('pendaftar'))
            ->where('tanggal_daftar >=', date('Y-m-d 00:00:00', strtotime('-6 days')))
            ->countAllResults();

        $terakhir = $this->scopePendaftar($this->db->table('pendaftar'))
            ->selectMax('tanggal_daftar', 'terakhir')
            ->get()->getRowArray();
        $hasil['terakhir'] = $terakhir['terakhir'] ?? null;

        $hasil['kuota'] = [
            'TSANAWIYYAH' => !empty($periode['kuota_tsanawiyyah']) ? (int) $periode['kuota_tsanawiyyah'] : null,
            'MUALLIMIN'   => !empty($periode['kuota_muallimin']) ? (int) $periode['kuota_muallimin'] : null,
        ];

        return $hasil;
    }

    /**
     * Jumlah per jalur, dipecah per jenis kelamin. Kedua jalur selalu tampil walau 0.
     */
    private function dataJalur(): array
    {
        $rows = $this->scopePendaftar($this->db->table('pendaftar'))
            ->select('jalur_pendaftaran, jenis_kelamin, COUNT(*) AS total')
            ->groupBy(['jalur_pendaftaran', 'jenis_kelamin'])
            ->get()->getResultArray();

        $per = [];
        foreach (array_keys(self::JALUR) as $j) {
            $per[$j] = ['L' => 0, 'P' => 0];
        }
        foreach ($rows as $r) {
            if (isset($per[$r['jalur_pendaftaran']][$r['jenis_kelamin']])) {
                $per[$r['jalur_pendaftaran']][$r['jenis_kelamin']] = (int) $r['total'];
            }
        }

        return [
            'labels'    => array_values(self::JALUR),
            'values'    => array_map(static fn($x) => $x['L'] + $x['P'], array_values($per)),
            'laki'      => array_column(array_values($per), 'L'),
            'perempuan' => array_column(array_values($per), 'P'),
        ];
    }

    /**
     * Pendaftar per hari (tanggal tanpa pendaftar tetap muncul dengan nilai 0) + kumulatif.
     */
    private function dataTanggal(): array
    {
        $rows = $this->scopePendaftar($this->db->table('pendaftar'))
            ->select('DATE(tanggal_daftar) AS tgl, COUNT(*) AS total')
            ->groupBy('DATE(tanggal_daftar)')
            ->orderBy('tgl', 'ASC')
            ->get()->getResultArray();

        $data = ['labels' => [], 'values' => [], 'kumulatif' => []];
        if ($rows === []) {
            return $data;
        }

        $map = array_column($rows, 'total', 'tgl');
        $mulai = new \DateTimeImmutable($rows[0]['tgl']);
        $akhir = new \DateTimeImmutable(max(end($rows)['tgl'], date('Y-m-d')));
        // Batasi rentang agar grafik tetap terbaca bila data sangat panjang
        if ($mulai->diff($akhir)->days > 365) {
            $mulai = $akhir->modify('-365 days');
        }

        $jumlah = 0;
        foreach ($map as $tgl => $n) {
            if ($tgl < $mulai->format('Y-m-d')) {
                $jumlah += (int) $n;
            }
        }

        for ($d = $mulai; $d <= $akhir; $d = $d->modify('+1 day')) {
            $n = (int) ($map[$d->format('Y-m-d')] ?? 0);
            $jumlah += $n;
            $data['labels'][]    = $d->format('j') . ' ' . self::BULAN[(int) $d->format('n') - 1] . ' ' . $d->format('Y');
            $data['values'][]    = $n;
            $data['kumulatif'][] = $jumlah;
        }

        return $data;
    }

    /**
     * Usia pendaftar per 1 Juli tahun ajaran (saat mulai masuk), bukan per hari ini.
     */
    private function dataUsia(): array
    {
        $rows = $this->scopePendaftar($this->db->table('pendaftar'))
            ->select('tanggal_lahir')
            ->where('tanggal_lahir IS NOT NULL')
            ->get()->getResultArray();

        $kelompok = ['≤ 11 tahun' => 0, '12 tahun' => 0, '13 tahun' => 0, '14 tahun' => 0, '15 tahun' => 0, '16 tahun' => 0, '≥ 17 tahun' => 0];
        $acuan = new \DateTimeImmutable(periode_tahun_mulai() . '-07-01');

        foreach ($rows as $r) {
            try {
                $usia = (new \DateTimeImmutable($r['tanggal_lahir']))->diff($acuan)->y;
            } catch (\Throwable $e) {
                continue;
            }
            if ($usia <= 11) {
                $kelompok['≤ 11 tahun']++;
            } elseif ($usia >= 17) {
                $kelompok['≥ 17 tahun']++;
            } else {
                $kelompok[$usia . ' tahun']++;
            }
        }

        return ['labels' => array_keys($kelompok), 'values' => array_values($kelompok)];
    }

    /**
     * 10 desa/kelurahan asal terbanyak (penulisan huruf besar/kecil disatukan).
     */
    private function dataDesa(): array
    {
        $rows = $this->scopeRelasi($this->db->table('alamat_pendaftar'), 'alamat_pendaftar')
            ->select('UPPER(TRIM(alamat_pendaftar.desa)) AS desa, COUNT(*) AS total', false)
            ->where("TRIM(COALESCE(alamat_pendaftar.desa, '')) !=", '')
            ->groupBy('UPPER(TRIM(alamat_pendaftar.desa))', false)
            ->orderBy('total', 'DESC')
            ->limit(10)
            ->get()->getResultArray();

        return [
            'labels' => array_map(static fn($r) => ucwords(strtolower($r['desa'])), $rows),
            'values' => array_map(static fn($r) => (int) $r['total'], $rows),
        ];
    }

    /**
     * Status asal sekolah (Negeri / Swasta).
     */
    private function dataStatusSekolah(): array
    {
        return $this->hitungKolomSekolah('status_sekolah', ['Negeri', 'Swasta']);
    }

    /**
     * Jenjang asal sekolah (SD, MI, SMP, MTs, Paket A/B, ...).
     */
    private function dataJenjangSekolah(): array
    {
        return $this->hitungKolomSekolah('jenjang_sekolah', ['SD', 'MI', 'SMP', 'MTs', 'Paket A', 'Paket B', 'Lainnya']);
    }

    private function hitungKolomSekolah(string $kolom, array $urutan): array
    {
        $rows = $this->scopeRelasi($this->db->table('asal_sekolah'), 'asal_sekolah')
            ->select("asal_sekolah.{$kolom} AS nilai, COUNT(*) AS total")
            ->where("asal_sekolah.{$kolom} IS NOT NULL")
            ->where("asal_sekolah.{$kolom} !=", '')
            ->groupBy("asal_sekolah.{$kolom}")
            ->get()->getResultArray();

        $hitung = [];
        foreach ($urutan as $u) {
            $hitung[$u] = 0;
        }
        foreach ($rows as $r) {
            $hitung[$r['nilai']] = ($hitung[$r['nilai']] ?? 0) + (int) $r['total'];
        }
        $hitung = array_filter($hitung); // sembunyikan kategori kosong

        return ['labels' => array_keys($hitung), 'values' => array_values($hitung)];
    }

    /**
     * Penghasilan orang tua per kategori EMIS (ayah; bila kosong pakai ibu, lalu wali).
     */
    private function dataPenghasilan(): array
    {
        $builder = $this->db->table('pendaftar p')
            ->select('a.penghasilan_ayah AS ayah, i.penghasilan_ibu AS ibu, w.penghasilan_wali AS wali')
            ->join('data_ayah a', 'a.id_pendaftar = p.id_pendaftar', 'left')
            ->join('data_ibu i', 'i.id_pendaftar = p.id_pendaftar', 'left')
            ->join('data_wali w', 'w.id_pendaftar = p.id_pendaftar', 'left');
        $rows = $this->scopePendaftar($builder, 'p')->get()->getResultArray();

        $kategori = FormPendaftaran::PENGHASILAN;
        $hitung = array_fill_keys(array_values($kategori), 0);
        $lainnya = 0;

        foreach ($rows as $r) {
            $nilai = '';
            foreach (['ayah', 'ibu', 'wali'] as $k) {
                if (trim((string) $r[$k]) !== '') {
                    $nilai = trim((string) $r[$k]);
                    break;
                }
            }
            if ($nilai === '') {
                continue;
            }
            if (isset($kategori[$nilai])) {
                $hitung[$kategori[$nilai]]++;
            } elseif (in_array($nilai, $kategori, true)) {
                $hitung[$nilai]++;
            } else {
                $lainnya++;
            }
        }
        if ($lainnya > 0) {
            $hitung['Lainnya (data lama)'] = $lainnya;
        }

        return ['labels' => array_keys($hitung), 'values' => array_values($hitung)];
    }

    /**
     * Hitung jumlah per kategori pada alamat_pendaftar (periode aktif), urut sesuai daftar pilihan.
     * Nilai di luar daftar (data lama) dikumpulkan sebagai "Lainnya (data lama)".
     */
    private function hitungKategori(string $kolom, array $kategori): array
    {
        $rows = $this->scopeRelasi($this->db->table('alamat_pendaftar'), 'alamat_pendaftar')
            ->select("alamat_pendaftar.{$kolom} AS nilai, COUNT(*) AS total")
            ->groupBy("alamat_pendaftar.{$kolom}")
            ->get()->getResultArray();

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
     * Daftar pendaftar untuk tabel publik.
     */
    private function dataTabel(?string $jalur = null): array
    {
        $builder = $this->db->table('pendaftar p')
            ->select('p.nomor_pendaftaran, p.nama_lengkap, p.jenis_kelamin, p.jalur_pendaftaran, p.tanggal_daftar, s.nama_asal_sekolah')
            ->join('asal_sekolah s', 's.id_pendaftar = p.id_pendaftar', 'left')
            ->orderBy('p.tanggal_daftar', 'DESC');
        $this->scopePendaftar($builder, 'p');
        if ($jalur !== null) {
            $builder->where('p.jalur_pendaftaran', $jalur);
        }

        $data = [];
        foreach ($builder->get()->getResultArray() as $r) {
            $data[] = [
                'nomor_pendaftaran' => $r['nomor_pendaftaran'],
                'nama_lengkap'      => $r['nama_lengkap'],
                'jenis_kelamin'     => self::JK[$r['jenis_kelamin']] ?? (string) $r['jenis_kelamin'],
                'jk'                => $r['jenis_kelamin'],
                'jalur'             => $r['jalur_pendaftaran'],
                'jalur_pendaftaran' => self::JALUR[$r['jalur_pendaftaran']] ?? ucfirst(strtolower((string) $r['jalur_pendaftaran'])),
                'asal_sekolah'      => trim((string) $r['nama_asal_sekolah']) !== '' ? $r['nama_asal_sekolah'] : '-',
                'tanggal'           => $r['tanggal_daftar'] ? date('d/m/Y', strtotime($r['tanggal_daftar'])) : '-',
            ];
        }

        return $data;
    }

    // =====================================================================
    // Pembatas periode
    // =====================================================================

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
