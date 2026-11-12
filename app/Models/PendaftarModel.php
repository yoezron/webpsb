<?php

namespace App\Models;

use CodeIgniter\Model;

class PendaftarModel extends Model
{
    protected $table            = 'pendaftar';
    protected $primaryKey       = 'id_pendaftar';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_periode',
        'nomor_pendaftaran',
        'jalur_pendaftaran',
        'nisn',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'agama',
        'file_kk',
        'tempat_lahir',
        'tanggal_lahir',
        'status_keluarga',
        'anak_ke',
        'jumlah_saudara',
        'hobi',
        'cita_cita',
        'yang_membiayai_sekolah',  // Sprint 2 - NEW
        'pernah_paud',
        'pernah_tk',
        'kebutuhan_disabilitas',
        'kebutuhan_khusus',  // Sprint 2 - NEW
        'imunisasi',
        'no_hp',
        'ukuran_baju',
        'prestasi',
        'minat_bakat',  // Sprint 2 - NEW
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'tanggal_daftar';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nomor_pendaftaran' => 'permit_empty|is_unique[pendaftar.nomor_pendaftaran]',
        'jalur_pendaftaran' => 'required|in_list[TSANAWIYYAH,MUALLIMIN]',
        'nama_lengkap'      => 'required|min_length[3]|max_length[150]',
        'jenis_kelamin'     => 'required|in_list[L,P]',
    ];

    protected $validationMessages = [
        'nomor_pendaftaran' => [
            'is_unique'  => 'Nomor pendaftaran sudah digunakan',
        ],
        'jalur_pendaftaran' => [
            'required'   => 'Jalur pendaftaran harus dipilih',
            'in_list'    => 'Jalur pendaftaran tidak valid',
        ],
        'nama_lengkap' => [
            'required'   => 'Nama lengkap harus diisi',
            'min_length' => 'Nama lengkap minimal 3 karakter',
        ],
        'jenis_kelamin' => [
            'required' => 'Jenis kelamin harus dipilih',
            'in_list'  => 'Jenis kelamin tidak valid',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['generateNomorPendaftaran'];
    protected $beforeUpdate   = [];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Generate nomor pendaftaran otomatis berdasarkan periode aktif.
     *
     * Format diatur di menu Pengaturan > Periode (kolom format_nomor), default:
     *   {PREFIX}-{YY1}{YY2}{NOMOR}  ->  T-26270001 / M-26270001
     *
     * Juga mengisi id_periode dengan periode aktif agar data bisa diarsipkan per periode.
     */
    protected function generateNomorPendaftaran(array $data)
    {
        $periodeModel = new PeriodeModel();
        $periode      = $periodeModel->getAktif();

        if ($periode && empty($data['data']['id_periode'])) {
            $data['data']['id_periode'] = $periode['id_periode'];
        }

        if (isset($data['data']['nomor_pendaftaran']) && !empty($data['data']['nomor_pendaftaran'])) {
            return $data;
        }

        $jalur = strtoupper($data['data']['jalur_pendaftaran'] ?? '');

        // Fallback bila tabel periode belum dimigrasi: perilaku lama (T-26270001)
        if (!$periode) {
            $ta1     = (int) date('Y') + 1;
            $periode = [
                'tahun_ajaran' => $ta1 . '/' . ($ta1 + 1),
                'format_nomor' => PeriodeModel::DEFAULT_FORMAT,
                'digit_nomor'  => 4,
                'nomor_mulai'  => 1,
            ];
        }

        // Nomor urut = nomor_mulai + jumlah pendaftar jalur ini pada periode ini (termasuk soft-deleted)
        $builder = $this->db->table($this->table)->where('jalur_pendaftaran', $jalur);
        if (!empty($periode['id_periode'])) {
            $builder->where('id_periode', $periode['id_periode']);
        } else {
            // Format default diakhiri {NOMOR}: cocokkan berdasarkan awalan nomor
            $awalan = substr(PeriodeModel::formatNomor($periode, $jalur, 0), 0, -4);
            $builder->like('nomor_pendaftaran', $awalan, 'after');
        }
        $urut = max(1, (int) ($periode['nomor_mulai'] ?? 1)) + $builder->countAllResults();

        // Pastikan unik (mis. ada data yang dihapus permanen / format sama dengan periode lain)
        $nomor = PeriodeModel::formatNomor($periode, $jalur, $urut);
        $guard = 0;
        while ($this->db->table($this->table)->where('nomor_pendaftaran', $nomor)->countAllResults() > 0 && $guard < 10000) {
            $urut++;
            $guard++;
            $nomor = PeriodeModel::formatNomor($periode, $jalur, $urut);
        }

        $data['data']['nomor_pendaftaran'] = $nomor;

        return $data;
    }

    /**
     * Get pendaftar with all related data
     */
    public function getPendaftarLengkap($id = null)
    {
        $builder = $this->select('pendaftar.*')
            ->join('alamat_pendaftar', 'pendaftar.id_pendaftar = alamat_pendaftar.id_pendaftar', 'left')
            ->join('data_ayah', 'pendaftar.id_pendaftar = data_ayah.id_pendaftar', 'left')
            ->join('data_ibu', 'pendaftar.id_pendaftar = data_ibu.id_pendaftar', 'left')
            ->join('data_wali', 'pendaftar.id_pendaftar = data_wali.id_pendaftar', 'left')
            ->join('bansos_pendaftar', 'pendaftar.id_pendaftar = bansos_pendaftar.id_pendaftar', 'left')
            ->join('asal_sekolah', 'pendaftar.id_pendaftar = asal_sekolah.id_pendaftar', 'left');

        if ($id !== null) {
            return $builder->where('pendaftar.id_pendaftar', $id)->first();
        }

        return $builder->findAll();
    }

    /**
     * Get pendaftar by jalur
     */
    public function getPendaftarByJalur($jalur)
    {
        return $this->where('jalur_pendaftaran', $jalur)
            ->orderBy('tanggal_daftar', 'DESC')
            ->findAll();
    }

    /**
     * Get statistics
     */
    public function getStatistik()
    {
        $db = \Config\Database::connect();

        $idPeriode = periode_aktif_id();
        $count = function (array $where = []) use ($idPeriode) {
            $b = $this->where($where);
            if ($idPeriode) {
                $b->where('id_periode', $idPeriode);
            }
            return $b->countAllResults();
        };

        return [
            'total' => $count(),
            'tsanawiyyah' => $count(['jalur_pendaftaran' => 'TSANAWIYYAH']),
            'muallimin' => $count(['jalur_pendaftaran' => 'MUALLIMIN']),
            'laki_laki' => $count(['jenis_kelamin' => 'L']),
            'perempuan' => $count(['jenis_kelamin' => 'P']),
        ];
    }

    /**
     * Hard delete pendaftar and all related data
     * This will permanently delete data from all 7 tables
     *
     * @param int $id ID pendaftar
     * @return bool
     */
    public function hardDeletePendaftar($id)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Delete from related tables first (foreign key order)
            $db->table('alamat_pendaftar')->where('id_pendaftar', $id)->delete();
            $db->table('data_ayah')->where('id_pendaftar', $id)->delete();
            $db->table('data_ibu')->where('id_pendaftar', $id)->delete();
            $db->table('data_wali')->where('id_pendaftar', $id)->delete();
            $db->table('bansos_pendaftar')->where('id_pendaftar', $id)->delete();
            $db->table('asal_sekolah')->where('id_pendaftar', $id)->delete();

            // Finally delete from main pendaftar table (hard delete, bypass soft delete)
            $db->table('pendaftar')->where('id_pendaftar', $id)->delete();

            $db->transComplete();

            return $db->transStatus();
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Hard delete failed for pendaftar ID ' . $id . ': ' . $e->getMessage());
            return false;
        }
    }
}
