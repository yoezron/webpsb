<?php

namespace App\Models;

use CodeIgniter\Model;

class WaliModel extends Model
{
    protected $table            = 'data_wali';
    protected $primaryKey       = 'id_wali';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pendaftar',
        'nama_wali',
        'hubungan_wali',  // NEW: Hubungan wali dengan santri
        'nik_wali',
        'tempat_lahir_wali',  // Sprint 2 - NEW (replace tahun_lahir_wali)
        'tanggal_lahir_wali',  // Sprint 2 - NEW (replace tahun_lahir_wali)
        'pendidikan_wali',
        'pekerjaan_wali',
        'penghasilan_wali',
        'hp_wali',
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
     * Get data wali by ID pendaftar
     */
    public function getByPendaftar($idPendaftar)
    {
        return $this->where('id_pendaftar', $idPendaftar)->first();
    }
}
