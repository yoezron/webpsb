<?php

namespace App\Controllers;

use App\Models\PengumumanModel;
use App\Models\PengumumanBalasanModel;
use App\Models\PengumumanLikeModel;

class Landing extends BaseController
{
    protected $pengumumanModel;
    protected $balasanModel;
    protected $likeModel;

    public function __construct()
    {
        $this->pengumumanModel = new PengumumanModel();
        $this->balasanModel = new PengumumanBalasanModel();
        $this->likeModel = new PengumumanLikeModel();
    }

    /**
     * Display the landing page
     *
     * @return string
     */
    public function index(): string
    {
        // Get recent announcements for landing page
        $announcements = $this->pengumumanModel->getRecentForLanding(3);

        // Add likes count for each announcement
        foreach ($announcements as &$announcement) {
            $announcement['likes_count'] = $this->likeModel->countAnnouncementLikes($announcement['id_pengumuman']);
            $announcement['replies_count'] = $this->balasanModel->countReplies($announcement['id_pengumuman']);
        }

        $data = [
            'title' => 'Pendaftaran Santri Baru - Pesantren Persatuan Islam 31 Banjaran',
            'meta_description' => 'Pendaftaran Santri Baru Pesantren Persatuan Islam 31 Banjaran untuk Tingkat Tsanawiyyah dan Mu\'allimin. Daftar sekarang!',
            'meta_keywords' => 'pendaftaran santri, pesantren persis 31, banjaran, tsanawiyyah, muallimin, pendaftaran online',
            'year' => 2026,
            'announcements' => $announcements,
        ];

        return view('landing/index', $data);
    }
}
