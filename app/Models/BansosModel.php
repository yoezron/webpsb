<?php

namespace App\Models;

use CodeIgniter\Model;

class BansosModel extends Model
{
    protected $table            = 'bansos_pendaftar';
    protected $primaryKey       = 'id_bansos';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pendaftar',
        'no_kks',
        'no_pkh',
        'no_kip',
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
     * Get data bansos by ID pendaftar
     */
    public function getByPendaftar($idPendaftar)
    {
        return $this->where('id_pendaftar', $idPendaftar)->first();
    }
}
