<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanModel extends Model
{
    protected $table            = 'pengumuman';
    protected $primaryKey       = 'id_pengumuman';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_admin',
        'judul',
        'konten',
        'gambar',
        'is_active',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'judul'  => 'required|min_length[5]|max_length[255]',
        'konten' => 'required|min_length[10]',
    ];

    protected $validationMessages = [
        'judul' => [
            'required'   => 'Judul pengumuman harus diisi',
            'min_length' => 'Judul minimal 5 karakter',
            'max_length' => 'Judul maksimal 255 karakter',
        ],
        'konten' => [
            'required'   => 'Konten pengumuman harus diisi',
            'min_length' => 'Konten minimal 10 karakter',
        ],
    ];

    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = ['deleteImage'];
    protected $afterDelete    = [];

    /**
     * Delete image file before deleting record
     */
    protected function deleteImage(array $data)
    {
        if (isset($data['id'])) {
            $pengumuman = $this->find($data['id']);
            if ($pengumuman && !empty($pengumuman['gambar'])) {
                $imagePath = FCPATH . 'uploads/pengumuman/' . $pengumuman['gambar'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }
        return $data;
    }

    /**
     * Get all active announcements for public view
     */
    public function getActiveAnnouncements($limit = 10, $offset = 0)
    {
        return $this->select('pengumuman.*, admin_panitia.nama_lengkap as admin_nama')
            ->join('admin_panitia', 'admin_panitia.id_admin = pengumuman.id_admin')
            ->where('pengumuman.is_active', true)
            ->orderBy('pengumuman.created_at', 'DESC')
            ->findAll($limit, $offset);
    }

    /**
     * Get announcement with admin info
     */
    public function getAnnouncementWithAdmin($id)
    {
        return $this->select('pengumuman.*, admin_panitia.nama_lengkap as admin_nama')
            ->join('admin_panitia', 'admin_panitia.id_admin = pengumuman.id_admin')
            ->where('pengumuman.id_pengumuman', $id)
            ->first();
    }

    /**
     * Get active announcement with admin info for public
     */
    public function getActiveAnnouncementWithAdmin($id)
    {
        return $this->select('pengumuman.*, admin_panitia.nama_lengkap as admin_nama')
            ->join('admin_panitia', 'admin_panitia.id_admin = pengumuman.id_admin')
            ->where('pengumuman.id_pengumuman', $id)
            ->where('pengumuman.is_active', true)
            ->first();
    }

    /**
     * Get all announcements for admin view
     */
    public function getAllForAdmin()
    {
        return $this->select('pengumuman.*, admin_panitia.nama_lengkap as admin_nama')
            ->join('admin_panitia', 'admin_panitia.id_admin = pengumuman.id_admin')
            ->orderBy('pengumuman.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Count active announcements
     */
    public function countActive()
    {
        return $this->where('is_active', true)->countAllResults();
    }

    /**
     * Get recent announcements for landing page
     */
    public function getRecentForLanding($limit = 5)
    {
        return $this->select('pengumuman.*, admin_panitia.nama_lengkap as admin_nama')
            ->join('admin_panitia', 'admin_panitia.id_admin = pengumuman.id_admin')
            ->where('pengumuman.is_active', true)
            ->orderBy('pengumuman.created_at', 'DESC')
            ->findAll($limit);
    }
}
