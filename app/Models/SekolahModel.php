<?php

namespace App\Models;

use CodeIgniter\Model;

class SekolahModel extends Model
{
    protected $table            = 'asal_sekolah';
    protected $primaryKey       = 'id_sekolah';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pendaftar',
        'nama_asal_sekolah',
        'jenjang_sekolah',
        'status_sekolah',
        'npsn',
        'lokasi_sekolah',
        'alamat_sekolah',  // Sprint 2 - NEW
        'tahun_lulus',  // Sprint 2 - NEW
        'rata_rata_rapor',  // Sprint 2 - NEW
        'nilai_tka',  // Sprint 2 - NEW
        'sekolah_md',  // Sprint 2 - NEW
        'asal_jenjang',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_pendaftar' => 'required|is_not_unique[pendaftar.id_pendaftar]',
    ];

    protected $validationMessages = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $beforeUpdate   = [];
    protected $afterInsert    = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get data sekolah by ID pendaftar
     */
    public function getByPendaftar($idPendaftar)
    {
        return $this->where('id_pendaftar', $idPendaftar)->first();
    }

    /**
     * Get sekolah by NPSN
     */
    public function getByNPSN($npsn)
    {
        return $this->where('npsn', $npsn)->findAll();
    }
}
