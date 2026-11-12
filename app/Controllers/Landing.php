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
        unset($announcement);

        $set = pengaturan();
        $statusPendaftaran = (new \App\Models\PeriodeModel())->statusPendaftaran();

        $data = [
            'title' => ($set['hero_judul'] ?: 'Pendaftaran Santri Baru') . ' - ' . $set['nama_lembaga'],
            'meta_description' => $set['meta_deskripsi'],
            'meta_keywords' => $set['meta_keywords'],
            'year' => periode_tahun_mulai(),
            'tahun_ajaran' => tahun_ajaran_aktif(),
            'status_pendaftaran' => $statusPendaftaran,
            'set' => $set,
            'keunggulan' => json_decode($set['keunggulan_items'] ?? '[]', true) ?: [],
            'announcements' => !empty($set['tampil_pengumuman']) ? $announcements : [],
        ];

        return view('landing/index', $data);
    }
}
