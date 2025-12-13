<?php

namespace App\Controllers;

use App\Models\PengumumanModel;
use App\Models\PengumumanBalasanModel;
use App\Models\PengumumanLikeModel;

class Pengumuman extends BaseController
{
    protected $session;
    protected $pengumumanModel;
    protected $balasanModel;
    protected $likeModel;

    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->pengumumanModel = new PengumumanModel();
        $this->balasanModel = new PengumumanBalasanModel();
        $this->likeModel = new PengumumanLikeModel();
    }

    /**
     * Admin: List all announcements
     */
    public function index()
    {
        $announcements = $this->pengumumanModel->getAllForAdmin();

        // Get likes and replies count for each announcement
        foreach ($announcements as &$announcement) {
            $announcement['likes_count'] = $this->likeModel->countAnnouncementLikes($announcement['id_pengumuman']);
            $announcement['replies_count'] = $this->balasanModel->countReplies($announcement['id_pengumuman'], false);
        }

        $data = [
            'title' => 'Kelola Pengumuman',
            'user' => $this->getUserData(),
            'announcements' => $announcements
        ];

        return view('pengumuman/admin/index', $data);
    }

    /**
     * Admin: Create new announcement form
     */
    public function create()
    {
        $data = [
            'title' => 'Buat Pengumuman Baru',
            'user' => $this->getUserData()
        ];

        return view('pengumuman/admin/create', $data);
    }

    /**
     * Admin: Store new announcement
     */
    public function store()
    {
        try {
            // Check if user is logged in and has admin privileges
            if (!$this->session->get('isLoggedIn') || !$this->session->get('id_admin')) {
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            // Validate input
            $rules = [
                'judul' => 'required|min_length[5]|max_length[255]',
                'konten' => 'required|min_length[10]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Handle image upload
            $gambar = null;
            $file = $this->request->getFile('gambar');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validate image
                $validationRule = [
                    'gambar' => [
                        'rules' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
                        'errors' => [
                            'max_size' => 'Ukuran gambar maksimal 2MB',
                            'is_image' => 'File harus berupa gambar',
                            'mime_in' => 'Format gambar harus JPG, PNG, GIF, atau WebP'
                        ]
                    ]
                ];

                if (!$this->validate($validationRule)) {
                    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                }

                // Create upload directory if not exists
                $uploadPath = FCPATH . 'uploads/pengumuman';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Generate unique filename
                $gambar = $file->getRandomName();
                $file->move($uploadPath, $gambar);
            }

            // Insert announcement
            $this->pengumumanModel->insert([
                'id_admin' => $this->session->get('id_admin'),
                'judul' => $this->request->getPost('judul'),
                'konten' => $this->request->getPost('konten'),
                'gambar' => $gambar,
                'is_active' => $this->request->getPost('is_active') ? true : false
            ]);

            return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil dibuat');
        } catch (\Exception $e) {
            log_message('error', 'Error creating announcement: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pengumuman. Silakan coba lagi.');
        }
    }

    /**
     * Admin: Edit announcement form
     */
    public function edit($id)
    {
        $announcement = $this->pengumumanModel->find($id);

        if (!$announcement) {
            return redirect()->to('/admin/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Pengumuman',
            'user' => $this->getUserData(),
            'announcement' => $announcement
        ];

        return view('pengumuman/admin/edit', $data);
    }

    /**
     * Admin: Update announcement
     */
    public function update($id)
    {
        try {
            // Check if user is logged in and has admin privileges
            if (!$this->session->get('isLoggedIn') || !$this->session->get('id_admin')) {
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            $announcement = $this->pengumumanModel->find($id);

            if (!$announcement) {
                return redirect()->to('/admin/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
            }

            // Validate input
            $rules = [
                'judul' => 'required|min_length[5]|max_length[255]',
                'konten' => 'required|min_length[10]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Handle image upload
            $gambar = $announcement['gambar'];
            $file = $this->request->getFile('gambar');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validate image
                $validationRule = [
                    'gambar' => [
                        'rules' => 'uploaded[gambar]|max_size[gambar,2048]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png,image/gif,image/webp]',
                        'errors' => [
                            'max_size' => 'Ukuran gambar maksimal 2MB',
                            'is_image' => 'File harus berupa gambar',
                            'mime_in' => 'Format gambar harus JPG, PNG, GIF, atau WebP'
                        ]
                    ]
                ];

                if (!$this->validate($validationRule)) {
                    return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
                }

                // Delete old image
                if ($announcement['gambar']) {
                    $oldImagePath = FCPATH . 'uploads/pengumuman/' . $announcement['gambar'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Create upload directory if not exists
                $uploadPath = FCPATH . 'uploads/pengumuman';
                if (!is_dir($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Generate unique filename
                $gambar = $file->getRandomName();
                $file->move($uploadPath, $gambar);
            }

            // Handle image removal
            if ($this->request->getPost('remove_image') && $announcement['gambar']) {
                $oldImagePath = FCPATH . 'uploads/pengumuman/' . $announcement['gambar'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $gambar = null;
            }

            // Update announcement
            $this->pengumumanModel->update($id, [
                'judul' => $this->request->getPost('judul'),
                'konten' => $this->request->getPost('konten'),
                'gambar' => $gambar,
                'is_active' => $this->request->getPost('is_active') ? true : false
            ]);

            return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil diperbarui');
        } catch (\Exception $e) {
            log_message('error', 'Error updating announcement: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat memperbarui pengumuman. Silakan coba lagi.');
        }
    }

    /**
     * Admin: Delete announcement
     */
    public function delete($id)
    {
        try {
            // Check if user is logged in and has admin privileges
            if (!$this->session->get('isLoggedIn') || !$this->session->get('id_admin')) {
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            $announcement = $this->pengumumanModel->find($id);

            if (!$announcement) {
                return redirect()->to('/admin/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
            }

            // Delete image file
            if ($announcement['gambar']) {
                $imagePath = FCPATH . 'uploads/pengumuman/' . $announcement['gambar'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $this->pengumumanModel->delete($id);

            return redirect()->to('/admin/pengumuman')->with('success', 'Pengumuman berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting announcement: ' . $e->getMessage());
            return redirect()->to('/admin/pengumuman')->with('error', 'Terjadi kesalahan saat menghapus pengumuman. Silakan coba lagi.');
        }
    }

    /**
     * Admin: View announcement with replies
     */
    public function view($id)
    {
        $announcement = $this->pengumumanModel->getAnnouncementWithAdmin($id);

        if (!$announcement) {
            return redirect()->to('/admin/pengumuman')->with('error', 'Pengumuman tidak ditemukan');
        }

        // Get all replies (including unapproved)
        $replies = $this->balasanModel->getRepliesWithAdminReplies($id, false);

        // Get likes count
        $likesCount = $this->likeModel->countAnnouncementLikes($id);

        // Get likes count for each reply
        foreach ($replies as &$reply) {
            $reply['likes_count'] = $this->likeModel->countReplyLikes($reply['id_balasan']);
            foreach ($reply['admin_replies'] as &$adminReply) {
                $adminReply['likes_count'] = $this->likeModel->countReplyLikes($adminReply['id_balasan']);
            }
        }

        $data = [
            'title' => 'Detail Pengumuman',
            'user' => $this->getUserData(),
            'announcement' => $announcement,
            'replies' => $replies,
            'likes_count' => $likesCount
        ];

        return view('pengumuman/admin/view', $data);
    }

    /**
     * Admin: Reply to a public comment
     */
    public function adminReply($idBalasan)
    {
        try {
            // Check if user is logged in and has admin privileges
            if (!$this->session->get('isLoggedIn') || !$this->session->get('id_admin')) {
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            $parentReply = $this->balasanModel->find($idBalasan);

            if (!$parentReply) {
                return redirect()->back()->with('error', 'Balasan tidak ditemukan');
            }

            // Validate input
            $rules = [
                'isi_balasan' => 'required|min_length[3]',
            ];

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            // Insert admin reply
            $this->balasanModel->insert([
                'id_pengumuman' => $parentReply['id_pengumuman'],
                'parent_id' => $idBalasan,
                'nama_pengirim' => $this->session->get('nama_lengkap'),
                'email_pengirim' => $this->session->get('email'),
                'isi_balasan' => $this->request->getPost('isi_balasan'),
                'is_admin_reply' => true,
                'id_admin' => $this->session->get('id_admin'),
                'is_approved' => true
            ]);

            return redirect()->back()->with('success', 'Balasan berhasil dikirim');
        } catch (\Exception $e) {
            log_message('error', 'Error submitting admin reply: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengirim balasan. Silakan coba lagi.');
        }
    }

    /**
     * Admin: Delete a reply
     */
    public function deleteReply($idBalasan)
    {
        try {
            // Check if user is logged in and has admin privileges
            if (!$this->session->get('isLoggedIn') || !$this->session->get('id_admin')) {
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            $reply = $this->balasanModel->find($idBalasan);

            if (!$reply) {
                return redirect()->back()->with('error', 'Balasan tidak ditemukan');
            }

            // Also delete child replies (admin replies to this comment)
            $this->balasanModel->where('parent_id', $idBalasan)->delete();
            $this->balasanModel->delete($idBalasan);

            return redirect()->back()->with('success', 'Balasan berhasil dihapus');
        } catch (\Exception $e) {
            log_message('error', 'Error deleting reply: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus balasan. Silakan coba lagi.');
        }
    }

    /**
     * Admin: Toggle reply approval
     */
    public function toggleApproval($idBalasan)
    {
        try {
            // Check if user is logged in and has admin privileges
            if (!$this->session->get('isLoggedIn') || !$this->session->get('id_admin')) {
                return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu');
            }

            $reply = $this->balasanModel->find($idBalasan);

            if (!$reply) {
                return redirect()->back()->with('error', 'Balasan tidak ditemukan');
            }

            $this->balasanModel->update($idBalasan, [
                'is_approved' => !$reply['is_approved']
            ]);

            $status = $reply['is_approved'] ? 'disembunyikan' : 'disetujui';
            return redirect()->back()->with('success', 'Balasan berhasil ' . $status);
        } catch (\Exception $e) {
            log_message('error', 'Error toggling reply approval: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah status balasan. Silakan coba lagi.');
        }
    }

    /**
     * Helper: Get current user data from session
     */
    private function getUserData(): array
    {
        return [
            'id_admin' => $this->session->get('id_admin'),
            'username' => $this->session->get('username'),
            'nama_lengkap' => $this->session->get('nama_lengkap'),
            'email' => $this->session->get('email'),
            'role_panitia' => $this->session->get('role_panitia')
        ];
    }
}
