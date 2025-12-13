<?php

namespace App\Models;

use CodeIgniter\Model;

class AlamatModel extends Model
{
    protected $table            = 'alamat_pendaftar';
    protected $primaryKey       = 'id_alamat';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pendaftar',
        'nomor_kk',
        'nama_kepala_keluarga',  // Sprint 2 - NEW
        'jenis_tempat_tinggal',
        'alamat',
        'rt_rw',  // Sprint 2 - NEW
        'desa',
        'kecamatan',
        'kabupaten',
        'provinsi',
        'kode_pos',
        'tinggal_bersama',  // Sprint 2 - NEW
        'jarak_ke_sekolah',
        'waktu_tempuh',
        'transportasi',
        'email',
        'media_sosial',
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
        'email'        => 'permit_empty|valid_email',
    ];

    protected $validationMessages = [
        'id_pendaftar' => [
            'required'       => 'ID Pendaftar harus diisi',
            'is_not_unique'  => 'ID Pendaftar tidak valid',
        ],
    ];

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
     * Get alamat by ID pendaftar
     */
    public function getByPendaftar($idPendaftar)
    {
        return $this->where('id_pendaftar', $idPendaftar)->first();
    }
}
