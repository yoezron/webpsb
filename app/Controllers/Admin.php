<?php

namespace App\Controllers;

use App\Models\AdminModel;

/**
 * Manajemen User Panitia (khusus Superadmin)
 */
class Admin extends BaseController
{
    protected AdminModel $adminModel;

    private const ROLES = [
        'tsanawiyyah' => 'Panitia Tsanawiyyah',
        'muallimin'   => "Panitia Mu'allimin",
        'superadmin'  => 'Superadmin',
    ];

    public function __construct()
    {
        $this->adminModel = new AdminModel();
    }

    public function index()
    {
        return redirect()->to(base_url('admin/pengaturan'));
    }

    public function users()
    {
        return view('pengaturan/users', $this->baseData('users', 'User Panitia') + [
            'users' => $this->adminModel->orderBy('role_panitia', 'ASC')->orderBy('username', 'ASC')->findAll(),
            'roles' => self::ROLES,
        ]);
    }

    public function addUser()
    {
        return view('pengaturan/user_form', $this->baseData('users', 'Tambah User Panitia') + [
            'mode'  => 'tambah',
            'item'  => ['username' => '', 'nama_lengkap' => '', 'email' => '', 'role_panitia' => 'tsanawiyyah', 'is_active' => 1],
            'roles' => self::ROLES,
        ]);
    }

    public function createUser()
    {
        $rules = [
            'username'         => 'required|min_length[3]|max_length[50]|alpha_dash|is_unique[admin_panitia.username]',
            'nama_lengkap'     => 'required|max_length[150]',
            'email'            => 'permit_empty|valid_email|max_length[100]|is_unique[admin_panitia.email]',
            'role_panitia'     => 'required|in_list[' . implode(',', array_keys(self::ROLES)) . ']',
            'password'         => 'required|min_length[8]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (!$this->validate($rules, $this->messages())) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post = $this->request->getPost();
        $this->adminModel->skipValidation(true)->insert([
            'username'      => trim($post['username']),
            'nama_lengkap'  => trim($post['nama_lengkap']),
            'email'         => trim($post['email'] ?? '') ?: null,
            'role_panitia'  => $post['role_panitia'],
            'is_active'     => !empty($post['is_active']) ? 1 : 0,
            'password_hash' => password_hash($post['password'], PASSWORD_DEFAULT),
        ]);

        log_message('info', 'User panitia dibuat: ' . $post['username'] . ' oleh ' . session('username'));

        return redirect()->to(base_url('admin/users'))->with('success', 'User ' . esc($post['username']) . ' berhasil ditambahkan.');
    }

    public function editUser($id)
    {
        $item = $this->adminModel->find((int) $id);
        if (!$item) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        return view('pengaturan/user_form', $this->baseData('users', 'Edit User ' . $item['username']) + [
            'mode'  => 'edit',
            'item'  => $item,
            'roles' => self::ROLES,
        ]);
    }

    public function updateUser($id)
    {
        $id   = (int) $id;
        $item = $this->adminModel->find($id);
        if (!$item) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }

        $rules = [
            'username'     => "required|min_length[3]|max_length[50]|alpha_dash|is_unique[admin_panitia.username,id_admin,{$id}]",
            'nama_lengkap' => 'required|max_length[150]',
            'email'        => "permit_empty|valid_email|max_length[100]|is_unique[admin_panitia.email,id_admin,{$id}]",
            'role_panitia' => 'required|in_list[' . implode(',', array_keys(self::ROLES)) . ']',
            'password'     => 'permit_empty|min_length[8]',
        ];
        if ($this->request->getPost('password')) {
            $rules['password_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules, $this->messages())) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $post   = $this->request->getPost();
        $isSelf = $id === (int) session('id_admin');

        $data = [
            'username'     => trim($post['username']),
            'nama_lengkap' => trim($post['nama_lengkap']),
            'email'        => trim($post['email'] ?? '') ?: null,
            'role_panitia' => $isSelf ? $item['role_panitia'] : $post['role_panitia'], // tidak bisa menurunkan role sendiri
            'is_active'    => $isSelf ? 1 : (!empty($post['is_active']) ? 1 : 0),      // tidak bisa menonaktifkan diri sendiri
        ];
        if (!empty($post['password'])) {
            $data['password_hash'] = password_hash($post['password'], PASSWORD_DEFAULT);
        }

        if (!$isSelf && $item['role_panitia'] === 'superadmin' && ($data['role_panitia'] !== 'superadmin' || !$data['is_active'])
            && $this->jumlahSuperadminAktif() <= 1) {
            return redirect()->back()->withInput()->with('error', 'Minimal harus ada satu superadmin aktif.');
        }

        $this->adminModel->skipValidation(true)->update($id, $data);

        if ($isSelf) {
            session()->set(['username' => $data['username'], 'nama_lengkap' => $data['nama_lengkap'], 'email' => $data['email']]);
        }

        return redirect()->to(base_url('admin/users'))->with('success', 'User ' . esc($data['username']) . ' berhasil diperbarui.');
    }

    public function toggleUser($id)
    {
        $id   = (int) $id;
        $item = $this->adminModel->find($id);
        if (!$item) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }
        if ($id === (int) session('id_admin')) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Anda tidak dapat menonaktifkan akun sendiri.');
        }
        if ($item['is_active'] && $item['role_panitia'] === 'superadmin' && $this->jumlahSuperadminAktif() <= 1) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Minimal harus ada satu superadmin aktif.');
        }

        $this->adminModel->skipValidation(true)->update($id, ['is_active' => $item['is_active'] ? 0 : 1]);

        return redirect()->to(base_url('admin/users'))->with('success', 'User ' . esc($item['username']) . ($item['is_active'] ? ' dinonaktifkan.' : ' diaktifkan.'));
    }

    public function deleteUser($id)
    {
        $id   = (int) $id;
        $item = $this->adminModel->find($id);
        if (!$item) {
            return redirect()->to(base_url('admin/users'))->with('error', 'User tidak ditemukan.');
        }
        if ($id === (int) session('id_admin')) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }
        if ($item['role_panitia'] === 'superadmin' && $item['is_active'] && $this->jumlahSuperadminAktif() <= 1) {
            return redirect()->to(base_url('admin/users'))->with('error', 'Minimal harus ada satu superadmin aktif.');
        }

        $this->adminModel->delete($id);
        log_message('info', 'User panitia dihapus: ' . $item['username'] . ' oleh ' . session('username'));

        return redirect()->to(base_url('admin/users'))->with('success', 'User ' . esc($item['username']) . ' berhasil dihapus.');
    }

    private function jumlahSuperadminAktif(): int
    {
        return $this->adminModel->where('role_panitia', 'superadmin')->where('is_active', 1)->countAllResults();
    }

    private function messages(): array
    {
        return [
            'username' => [
                'required'   => 'Username wajib diisi.',
                'is_unique'  => 'Username sudah digunakan.',
                'alpha_dash' => 'Username hanya boleh huruf, angka, - dan _.',
                'min_length' => 'Username minimal 3 karakter.',
            ],
            'nama_lengkap' => ['required' => 'Nama lengkap wajib diisi.'],
            'email' => [
                'valid_email' => 'Format email tidak valid.',
                'is_unique'   => 'Email sudah digunakan.',
            ],
            'password' => [
                'required'   => 'Password wajib diisi.',
                'min_length' => 'Password minimal 8 karakter.',
            ],
            'password_confirm' => [
                'required' => 'Konfirmasi password wajib diisi.',
                'matches'  => 'Konfirmasi password tidak sama.',
            ],
        ];
    }

    private function baseData(string $menu, string $title): array
    {
        return [
            'title' => $title . ' - Pengaturan',
            'judul' => $title,
            'menu'  => $menu,
            'user'  => [
                'nama_lengkap' => session('nama_lengkap'),
                'username'     => session('username'),
                'role_panitia' => session('role_panitia'),
            ],
        ];
    }
}
