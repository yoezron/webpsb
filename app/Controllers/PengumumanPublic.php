<?php

namespace App\Controllers;

use App\Models\PengumumanModel;
use App\Models\PengumumanBalasanModel;
use App\Models\PengumumanLikeModel;

class PengumumanPublic extends BaseController
{
    protected $pengumumanModel;
    protected $balasanModel;
    protected $likeModel;
    protected $session;

    public function __construct()
    {
        $this->pengumumanModel = new PengumumanModel();
        $this->balasanModel = new PengumumanBalasanModel();
        $this->likeModel = new PengumumanLikeModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Public: List all announcements
     */
    public function index()
    {
        $page = $this->request->getGet('page') ?? 1;
        $perPage = 10;
        $offset = ($page - 1) * $perPage;

        $announcements = $this->pengumumanModel->getActiveAnnouncements($perPage, $offset);
        $total = $this->pengumumanModel->countActive();

        // Get session ID for checking likes
        $sessionId = $this->session->session_id ?? session_id();
        $userLikedAnnouncements = $this->likeModel->getUserLikedAnnouncements($sessionId);

        // Get likes count for each announcement
        foreach ($announcements as &$announcement) {
            $announcement['likes_count'] = $this->likeModel->countAnnouncementLikes($announcement['id_pengumuman']);
            $announcement['replies_count'] = $this->balasanModel->countReplies($announcement['id_pengumuman']);
            $announcement['user_liked'] = in_array($announcement['id_pengumuman'], $userLikedAnnouncements);
        }

        $data = [
            'title' => 'Pengumuman - PSB Persis 31',
            'announcements' => $announcements,
            'pager' => [
                'current' => (int) $page,
                'total' => ceil($total / $perPage),
                'perPage' => $perPage,
                'total_items' => $total
            ]
        ];

        return view('pengumuman/public/index', $data);
    }

    /**
     * Public: View single announcement with replies
     */
    public function show($id)
    {
        $announcement = $this->pengumumanModel->getActiveAnnouncementWithAdmin($id);

        if (!$announcement) {
            return redirect()->to('/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
        }

        // Get session ID for checking likes
        $sessionId = $this->session->session_id ?? session_id();

        // Get approved replies with admin replies
        $replies = $this->balasanModel->getRepliesWithAdminReplies($id, true);

        // Get likes info
        $likesCount = $this->likeModel->countAnnouncementLikes($id);
        $userLiked = $this->likeModel->hasLikedAnnouncement($id, $sessionId);
        $userLikedReplies = $this->likeModel->getUserLikedReplies($sessionId);

        // Add likes info to replies
        foreach ($replies as &$reply) {
            $reply['likes_count'] = $this->likeModel->countReplyLikes($reply['id_balasan']);
            $reply['user_liked'] = in_array($reply['id_balasan'], $userLikedReplies);

            foreach ($reply['admin_replies'] as &$adminReply) {
                $adminReply['likes_count'] = $this->likeModel->countReplyLikes($adminReply['id_balasan']);
                $adminReply['user_liked'] = in_array($adminReply['id_balasan'], $userLikedReplies);
            }
        }

        $data = [
            'title' => $announcement['judul'] . ' - PSB Persis 31',
            'announcement' => $announcement,
            'replies' => $replies,
            'likes_count' => $likesCount,
            'user_liked' => $userLiked
        ];

        return view('pengumuman/public/show', $data);
    }

    /**
     * Public: Submit a reply/question
     */
    public function submitReply($idPengumuman)
    {
        $announcement = $this->pengumumanModel->find($idPengumuman);

        if (!$announcement || !$announcement['is_active']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pengumuman tidak ditemukan'
            ]);
        }

        // Validate input
        $rules = [
            'nama_pengirim' => 'required|min_length[2]|max_length[100]',
            'isi_balasan' => 'required|min_length[3]|max_length[1000]',
            'email_pengirim' => 'permit_empty|valid_email|max_length[100]'
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Insert reply
        $this->balasanModel->insert([
            'id_pengumuman' => $idPengumuman,
            'nama_pengirim' => $this->request->getPost('nama_pengirim'),
            'email_pengirim' => $this->request->getPost('email_pengirim'),
            'isi_balasan' => $this->request->getPost('isi_balasan'),
            'is_admin_reply' => false,
            'is_approved' => true // Auto approve, can be changed to false for moderation
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pertanyaan/balasan Anda berhasil dikirim'
        ]);
    }

    /**
     * Public: Toggle like for announcement
     */
    public function toggleLikeAnnouncement($idPengumuman)
    {
        $announcement = $this->pengumumanModel->find($idPengumuman);

        if (!$announcement || !$announcement['is_active']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Pengumuman tidak ditemukan'
            ]);
        }

        $sessionId = $this->session->session_id ?? session_id();
        $ipAddress = $this->request->getIPAddress();

        $result = $this->likeModel->toggleAnnouncementLike($idPengumuman, $sessionId, $ipAddress);

        return $this->response->setJSON([
            'success' => true,
            'action' => $result['action'],
            'count' => $result['count']
        ]);
    }

    /**
     * Public: Toggle like for reply
     */
    public function toggleLikeReply($idBalasan)
    {
        $reply = $this->balasanModel->find($idBalasan);

        if (!$reply) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Balasan tidak ditemukan'
            ]);
        }

        $sessionId = $this->session->session_id ?? session_id();
        $ipAddress = $this->request->getIPAddress();

        $result = $this->likeModel->toggleReplyLike($idBalasan, $sessionId, $ipAddress);

        if (!$result) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Gagal memproses like'
            ]);
        }

        return $this->response->setJSON([
            'success' => true,
            'action' => $result['action'],
            'count' => $result['count']
        ]);
    }

    /**
     * API: Get announcements for landing page
     */
    public function getForLanding()
    {
        $limit = $this->request->getGet('limit') ?? 5;
        $announcements = $this->pengumumanModel->getRecentForLanding($limit);

        // Get likes count
        foreach ($announcements as &$announcement) {
            $announcement['likes_count'] = $this->likeModel->countAnnouncementLikes($announcement['id_pengumuman']);
            $announcement['replies_count'] = $this->balasanModel->countReplies($announcement['id_pengumuman']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $announcements
        ]);
    }
}
