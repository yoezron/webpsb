<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanBalasanModel extends Model
{
    protected $table            = 'pengumuman_balasan';
    protected $primaryKey       = 'id_balasan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pengumuman',
        'parent_id',
        'nama_pengirim',
        'email_pengirim',
        'isi_balasan',
        'is_admin_reply',
        'id_admin',
        'is_approved',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_pengirim' => 'required|min_length[2]|max_length[100]',
        'isi_balasan'   => 'required|min_length[3]',
    ];

    protected $validationMessages = [
        'nama_pengirim' => [
            'required'   => 'Nama harus diisi',
            'min_length' => 'Nama minimal 2 karakter',
            'max_length' => 'Nama maksimal 100 karakter',
        ],
        'isi_balasan' => [
            'required'   => 'Balasan harus diisi',
            'min_length' => 'Balasan minimal 3 karakter',
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
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get all replies for an announcement
     */
    public function getRepliesForAnnouncement($idPengumuman, $approvedOnly = true)
    {
        $builder = $this->where('id_pengumuman', $idPengumuman)
            ->whereNull('parent_id');

        if ($approvedOnly) {
            $builder->where('is_approved', true);
        }

        return $builder->orderBy('created_at', 'ASC')->findAll();
    }

    /**
     * Get admin replies for a public reply
     */
    public function getAdminReplies($parentId)
    {
        return $this->where('parent_id', $parentId)
            ->where('is_admin_reply', true)
            ->orderBy('created_at', 'ASC')
            ->findAll();
    }

    /**
     * Get all replies with their admin replies (nested)
     */
    public function getRepliesWithAdminReplies($idPengumuman, $approvedOnly = true)
    {
        // Get parent replies (from public)
        $parentReplies = $this->getRepliesForAnnouncement($idPengumuman, $approvedOnly);

        // For each parent reply, get admin replies
        foreach ($parentReplies as &$reply) {
            $reply['admin_replies'] = $this->getAdminReplies($reply['id_balasan']);
        }

        return $parentReplies;
    }

    /**
     * Count replies for an announcement
     */
    public function countReplies($idPengumuman, $approvedOnly = true)
    {
        $builder = $this->where('id_pengumuman', $idPengumuman);

        if ($approvedOnly) {
            $builder->where('is_approved', true);
        }

        return $builder->countAllResults();
    }

    /**
     * Get pending replies (for admin moderation)
     */
    public function getPendingReplies()
    {
        return $this->select('pengumuman_balasan.*, pengumuman.judul as judul_pengumuman')
            ->join('pengumuman', 'pengumuman.id_pengumuman = pengumuman_balasan.id_pengumuman')
            ->where('pengumuman_balasan.is_approved', false)
            ->where('pengumuman_balasan.is_admin_reply', false)
            ->orderBy('pengumuman_balasan.created_at', 'ASC')
            ->findAll();
    }

    /**
     * Get reply with announcement info
     */
    public function getReplyWithAnnouncement($idBalasan)
    {
        return $this->select('pengumuman_balasan.*, pengumuman.judul as judul_pengumuman')
            ->join('pengumuman', 'pengumuman.id_pengumuman = pengumuman_balasan.id_pengumuman')
            ->where('pengumuman_balasan.id_balasan', $idBalasan)
            ->first();
    }

    /**
     * Get all replies for admin management
     */
    public function getAllRepliesForAdmin($idPengumuman = null)
    {
        $builder = $this->select('pengumuman_balasan.*, pengumuman.judul as judul_pengumuman, admin_panitia.nama_lengkap as admin_nama')
            ->join('pengumuman', 'pengumuman.id_pengumuman = pengumuman_balasan.id_pengumuman')
            ->join('admin_panitia', 'admin_panitia.id_admin = pengumuman_balasan.id_admin', 'left');

        if ($idPengumuman) {
            $builder->where('pengumuman_balasan.id_pengumuman', $idPengumuman);
        }

        return $builder->orderBy('pengumuman_balasan.created_at', 'DESC')->findAll();
    }
}
