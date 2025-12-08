<?php

namespace App\Models;

use CodeIgniter\Model;

class PengumumanLikeModel extends Model
{
    protected $table            = 'pengumuman_like';
    protected $primaryKey       = 'id_like';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_pengumuman',
        'id_balasan',
        'session_id',
        'ip_address',
    ];

    // Dates
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
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
     * Check if user already liked an announcement
     */
    public function hasLikedAnnouncement($idPengumuman, $sessionId)
    {
        return $this->where('id_pengumuman', $idPengumuman)
            ->where('session_id', $sessionId)
            ->whereNull('id_balasan')
            ->countAllResults() > 0;
    }

    /**
     * Check if user already liked a reply
     */
    public function hasLikedReply($idBalasan, $sessionId)
    {
        return $this->where('id_balasan', $idBalasan)
            ->where('session_id', $sessionId)
            ->countAllResults() > 0;
    }

    /**
     * Toggle like for announcement
     */
    public function toggleAnnouncementLike($idPengumuman, $sessionId, $ipAddress = null)
    {
        $existingLike = $this->where('id_pengumuman', $idPengumuman)
            ->where('session_id', $sessionId)
            ->whereNull('id_balasan')
            ->first();

        if ($existingLike) {
            $this->delete($existingLike['id_like']);
            return ['action' => 'unliked', 'count' => $this->countAnnouncementLikes($idPengumuman)];
        } else {
            $this->insert([
                'id_pengumuman' => $idPengumuman,
                'id_balasan'    => null,
                'session_id'    => $sessionId,
                'ip_address'    => $ipAddress,
            ]);
            return ['action' => 'liked', 'count' => $this->countAnnouncementLikes($idPengumuman)];
        }
    }

    /**
     * Toggle like for reply
     */
    public function toggleReplyLike($idBalasan, $sessionId, $ipAddress = null)
    {
        // Get the announcement ID from the reply
        $balasanModel = new PengumumanBalasanModel();
        $balasan = $balasanModel->find($idBalasan);

        if (!$balasan) {
            return false;
        }

        $existingLike = $this->where('id_balasan', $idBalasan)
            ->where('session_id', $sessionId)
            ->first();

        if ($existingLike) {
            $this->delete($existingLike['id_like']);
            return ['action' => 'unliked', 'count' => $this->countReplyLikes($idBalasan)];
        } else {
            $this->insert([
                'id_pengumuman' => $balasan['id_pengumuman'],
                'id_balasan'    => $idBalasan,
                'session_id'    => $sessionId,
                'ip_address'    => $ipAddress,
            ]);
            return ['action' => 'liked', 'count' => $this->countReplyLikes($idBalasan)];
        }
    }

    /**
     * Count likes for an announcement
     */
    public function countAnnouncementLikes($idPengumuman)
    {
        return $this->where('id_pengumuman', $idPengumuman)
            ->whereNull('id_balasan')
            ->countAllResults();
    }

    /**
     * Count likes for a reply
     */
    public function countReplyLikes($idBalasan)
    {
        return $this->where('id_balasan', $idBalasan)
            ->countAllResults();
    }

    /**
     * Get likes count for multiple announcements
     */
    public function getLikesCountForAnnouncements(array $ids)
    {
        $result = [];
        foreach ($ids as $id) {
            $result[$id] = $this->countAnnouncementLikes($id);
        }
        return $result;
    }

    /**
     * Get likes count for multiple replies
     */
    public function getLikesCountForReplies(array $ids)
    {
        $result = [];
        foreach ($ids as $id) {
            $result[$id] = $this->countReplyLikes($id);
        }
        return $result;
    }

    /**
     * Get user's liked announcements
     */
    public function getUserLikedAnnouncements($sessionId)
    {
        return $this->where('session_id', $sessionId)
            ->whereNull('id_balasan')
            ->findColumn('id_pengumuman') ?? [];
    }

    /**
     * Get user's liked replies
     */
    public function getUserLikedReplies($sessionId)
    {
        return $this->where('session_id', $sessionId)
            ->where('id_balasan IS NOT NULL')
            ->findColumn('id_balasan') ?? [];
    }
}
