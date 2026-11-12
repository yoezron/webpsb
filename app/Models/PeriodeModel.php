<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Periode / Tahun Ajaran Penerimaan Santri Baru.
 *
 * Hanya ada satu periode berstatus "aktif". Periode sebelumnya berstatus "arsip":
 * data pendaftarnya tetap tersimpan (kolom pendaftar.id_periode) dan bisa diunduh.
 */
class PeriodeModel extends Model
{
    protected $table         = 'periode_pendaftaran';
    protected $primaryKey    = 'id_periode';
    protected $returnType    = 'array';
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $allowedFields = [
        'tahun_ajaran',
        'nama_periode',
        'tanggal_buka',
        'tanggal_tutup',
        'format_nomor',
        'prefix_tsanawiyyah',
        'prefix_muallimin',
        'digit_nomor',
        'nomor_mulai',
        'kuota_tsanawiyyah',
        'kuota_muallimin',
        'pendaftaran_dibuka',
        'status',
        'catatan',
        'diarsipkan_pada',
    ];

    public const DEFAULT_FORMAT = '{PREFIX}-{YY1}{YY2}{NOMOR}';

    /** Cache per-request */
    private static ?array $aktifCache = null;
    private static bool $aktifLoaded = false;
    private static bool $tersedia = false;

    /**
     * Periode yang sedang aktif (atau null jika tabel belum dimigrasi / kosong).
     */
    public function getAktif(): ?array
    {
        if (self::$aktifLoaded) {
            return self::$aktifCache;
        }

        self::$aktifLoaded = true;
        self::$tersedia    = false;

        try {
            if (!$this->db->tableExists($this->table)) {
                return self::$aktifCache = null;
            }
            self::$tersedia = true;

            self::$aktifCache = $this->where('status', 'aktif')
                ->orderBy('id_periode', 'DESC')
                ->first();
        } catch (\Throwable $e) {
            log_message('error', 'PeriodeModel::getAktif gagal: ' . $e->getMessage());
            self::$aktifCache = null;
            self::$tersedia   = false;
        }

        return self::$aktifCache;
    }

    /**
     * Apakah fitur periode sudah tersedia (tabel sudah dimigrasi & bisa dibaca).
     */
    public function fiturTersedia(): bool
    {
        $this->getAktif();

        return self::$tersedia;
    }

    public static function resetCache(): void
    {
        self::$aktifCache  = null;
        self::$aktifLoaded = false;
        self::$tersedia    = false;
    }

    /**
     * Daftar semua periode + jumlah pendaftar per jalur.
     */
    public function getAllWithCounts(): array
    {
        $periodes = $this->orderBy('id_periode', 'DESC')->findAll();

        $counts = $this->db->table('pendaftar')
            ->select('id_periode, jalur_pendaftaran, COUNT(*) AS total')
            ->where('deleted_at', null)
            ->groupBy(['id_periode', 'jalur_pendaftaran'])
            ->get()->getResultArray();

        $map = [];
        foreach ($counts as $row) {
            $map[$row['id_periode']][strtoupper($row['jalur_pendaftaran'])] = (int) $row['total'];
        }

        foreach ($periodes as &$p) {
            $p['total_tsanawiyyah'] = $map[$p['id_periode']]['TSANAWIYYAH'] ?? 0;
            $p['total_muallimin']   = $map[$p['id_periode']]['MUALLIMIN'] ?? 0;
            $p['total_pendaftar']   = $p['total_tsanawiyyah'] + $p['total_muallimin'];
        }

        return $periodes;
    }

    /**
     * Tahun mulai dari string tahun ajaran, mis. "2026/2027" -> 2026.
     */
    public static function tahunMulai(?string $tahunAjaran): int
    {
        if ($tahunAjaran && preg_match('/(\d{4})/', $tahunAjaran, $m)) {
            return (int) $m[1];
        }

        return (int) date('Y');
    }

    /**
     * Tahun akhir dari string tahun ajaran, mis. "2026/2027" -> 2027.
     */
    public static function tahunAkhir(?string $tahunAjaran): int
    {
        if ($tahunAjaran && preg_match('/\d{4}\D+(\d{4})/', $tahunAjaran, $m)) {
            return (int) $m[1];
        }

        return self::tahunMulai($tahunAjaran) + 1;
    }

    /**
     * Susun nomor pendaftaran dari format periode.
     *
     * Placeholder yang didukung:
     *   {PREFIX}  prefix jalur (mis. T / M)
     *   {JALUR}   kode jalur: TSN / MUA
     *   {TA1}     tahun mulai 4 digit (2026)    {YY1} 2 digit (26)
     *   {TA2}     tahun akhir 4 digit (2027)    {YY2} 2 digit (27)
     *   {NOMOR}   nomor urut dengan nol di depan sesuai digit_nomor
     */
    public static function formatNomor(array $periode, string $jalur, int $urut): string
    {
        $jalur  = strtoupper($jalur);
        $format = trim($periode['format_nomor'] ?? '') ?: self::DEFAULT_FORMAT;
        $digit  = max(1, min(10, (int) ($periode['digit_nomor'] ?? 4)));

        $ta1 = self::tahunMulai($periode['tahun_ajaran'] ?? null);
        $ta2 = self::tahunAkhir($periode['tahun_ajaran'] ?? null);

        $prefix = $jalur === 'TSANAWIYYAH'
            ? ($periode['prefix_tsanawiyyah'] ?? 'T')
            : ($periode['prefix_muallimin'] ?? 'M');

        $nomor = str_pad((string) $urut, $digit, '0', STR_PAD_LEFT);

        // Jika format tidak memuat {NOMOR}, tambahkan di belakang agar tetap unik
        if (stripos($format, '{NOMOR}') === false) {
            $format .= '{NOMOR}';
        }

        return strtr($format, [
            '{PREFIX}' => $prefix,
            '{JALUR}'  => $jalur === 'TSANAWIYYAH' ? 'TSN' : 'MUA',
            '{TA1}'    => (string) $ta1,
            '{TA2}'    => (string) $ta2,
            '{YY1}'    => substr((string) $ta1, -2),
            '{YY2}'    => substr((string) $ta2, -2),
            '{NOMOR}'  => $nomor,
        ]);
    }

    /**
     * Status pendaftaran untuk periode aktif.
     *
     * @return array{dibuka: bool, alasan: string}
     */
    public function statusPendaftaran(?string $jalur = null): array
    {
        $periode = $this->getAktif();

        if (!$periode) {
            // Migrasi belum dijalankan: pertahankan perilaku lama (pendaftaran selalu dibuka)
            if (!self::$tersedia) {
                return ['dibuka' => true, 'alasan' => ''];
            }

            return ['dibuka' => false, 'alasan' => 'Belum ada periode pendaftaran yang dibuka.'];
        }

        if (empty($periode['pendaftaran_dibuka'])) {
            return ['dibuka' => false, 'alasan' => 'Pendaftaran Tahun Ajaran ' . $periode['tahun_ajaran'] . ' sedang ditutup.'];
        }

        $today = date('Y-m-d');
        if (!empty($periode['tanggal_buka']) && $today < $periode['tanggal_buka']) {
            return ['dibuka' => false, 'alasan' => 'Pendaftaran Tahun Ajaran ' . $periode['tahun_ajaran'] . ' baru dibuka pada ' . date('d/m/Y', strtotime($periode['tanggal_buka'])) . '.'];
        }
        if (!empty($periode['tanggal_tutup']) && $today > $periode['tanggal_tutup']) {
            return ['dibuka' => false, 'alasan' => 'Pendaftaran Tahun Ajaran ' . $periode['tahun_ajaran'] . ' telah ditutup pada ' . date('d/m/Y', strtotime($periode['tanggal_tutup'])) . '.'];
        }

        if ($jalur !== null) {
            $jalur = strtoupper($jalur);
            $kuota = $jalur === 'TSANAWIYYAH' ? ($periode['kuota_tsanawiyyah'] ?? null) : ($periode['kuota_muallimin'] ?? null);

            if (!empty($kuota)) {
                $terisi = $this->db->table('pendaftar')
                    ->where('id_periode', $periode['id_periode'])
                    ->where('jalur_pendaftaran', $jalur)
                    ->where('deleted_at', null)
                    ->countAllResults();

                if ($terisi >= (int) $kuota) {
                    $label = $jalur === 'TSANAWIYYAH' ? 'Tsanawiyyah' : "Mu'allimin";
                    return ['dibuka' => false, 'alasan' => "Kuota pendaftaran {$label} sudah penuh."];
                }
            }
        }

        return ['dibuka' => true, 'alasan' => ''];
    }

    /**
     * Buka periode baru: arsipkan periode aktif (jika ada), lalu aktifkan periode baru.
     *
     * @return int|false id periode baru
     */
    public function bukaPeriodeBaru(array $data)
    {
        $this->db->transStart();

        $this->arsipkanSemuaAktif();

        $data['status'] = 'aktif';
        $id = $this->insert($data);

        $this->db->transComplete();
        self::resetCache();

        return $this->db->transStatus() ? $id : false;
    }

    /**
     * Aktifkan kembali periode tertentu (periode aktif saat ini diarsipkan).
     */
    public function aktifkan(int $idPeriode): bool
    {
        $this->db->transStart();

        $this->arsipkanSemuaAktif($idPeriode);
        $this->update($idPeriode, ['status' => 'aktif', 'diarsipkan_pada' => null]);

        $this->db->transComplete();
        self::resetCache();

        return $this->db->transStatus();
    }

    private function arsipkanSemuaAktif(?int $kecuali = null): void
    {
        $builder = $this->db->table($this->table)->where('status', 'aktif');
        if ($kecuali !== null) {
            $builder->where('id_periode !=', $kecuali);
        }

        $builder->update([
            'status'          => 'arsip',
            'diarsipkan_pada' => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        ]);
    }
}
