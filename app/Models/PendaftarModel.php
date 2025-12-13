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
        'nomor_pendaftaran',
        'jalur_pendaftaran',
        'nisn',
        'nik',
        'nama_lengkap',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'status_keluarga',
        'anak_ke',
        'jumlah_saudara',
        'hobi',
        'cita_cita',
        'pernah_paud',
        'pernah_tk',
        'kebutuhan_disabilitas',
        'imunisasi',
        'no_hp',
        'ukuran_baju',
        'prestasi',
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
     * Generate nomor pendaftaran otomatis
     * Format: T2026-001 (Tsanawiyyah) atau M2026-001 (Mu'allimin)
     */
    protected function generateNomorPendaftaran(array $data)
    {
        if (isset($data['data']['nomor_pendaftaran']) && !empty($data['data']['nomor_pendaftaran'])) {
            return $data;
        }

        $jalur = $data['data']['jalur_pendaftaran'] ?? '';
        // Tahun ajaran PSB 2026/2027
        $tahunPSB = 2026;
        $prefix = ($jalur === 'TSANAWIYYAH') ? 'T' : 'M';

        // Get last number for this jalur and tahun PSB
        $lastPendaftar = $this->like('nomor_pendaftaran', $prefix . $tahunPSB, 'after')
            ->orderBy('id_pendaftar', 'DESC')
            ->first();

        if ($lastPendaftar && preg_match('/\d{3}$/', $lastPendaftar['nomor_pendaftaran'], $matches)) {
            $counter = intval($matches[0]) + 1;
        } else {
            $counter = 1;
        }

        $data['data']['nomor_pendaftaran'] = sprintf('%s%s-%03d', $prefix, $tahunPSB, $counter);

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

        return [
            'total' => $this->countAll(),
            'tsanawiyyah' => $this->where('jalur_pendaftaran', 'TSANAWIYYAH')->countAllResults(),
            'muallimin' => $this->where('jalur_pendaftaran', 'MUALLIMIN')->countAllResults(),
            'laki_laki' => $this->where('jenis_kelamin', 'L')->countAllResults(),
            'perempuan' => $this->where('jenis_kelamin', 'P')->countAllResults(),
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
