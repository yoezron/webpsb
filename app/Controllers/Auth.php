<?php

namespace App\Controllers;

use App\Models\AdminModel;

class Auth extends BaseController
{
    protected $session;
    protected $adminModel;

    public function __construct()
    {
        $this->session = session();
        $this->adminModel = new AdminModel();
    }

    /**
     * Display login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('isLoggedIn')) {
            return $this->redirectToDashboard();
        }

        $data = [
            'title' => 'Login Panitia - PSB Persis 31 Banjaran',
            'error' => $this->session->getFlashdata('error'),
            'success' => $this->session->getFlashdata('success'),
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login
     */
    public function attemptLogin()
    {
        // Validate input
        $rules = [
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[5]',
        ];

        $messages = [
            'username' => [
                'required' => 'Username harus diisi',
                'min_length' => 'Username minimal 3 karakter',
            ],
            'password' => [
                'required' => 'Password harus diisi',
                'min_length' => 'Password minimal 5 karakter',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('error', implode('<br>', $this->validator->getErrors()));
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Verify login
        $admin = $this->adminModel->verifyLogin($username, $password);

        if (!$admin) {
            return redirect()->back()->withInput()->with('error', 'Username atau password salah.');
        }

        // Set session data
        $sessionData = [
            'id_admin' => $admin['id_admin'],
            'username' => $admin['username'],
            'nama_lengkap' => $admin['nama_lengkap'],
            'email' => $admin['email'],
            'role' => $admin['role_panitia'],
            'isLoggedIn' => true,
        ];

        $this->session->set($sessionData);

        // Log activity
        log_message('info', 'User login: ' . $admin['username'] . ' (Role: ' . $admin['role_panitia'] . ')');

        return $this->redirectToDashboard();
    }

    /**
     * Redirect to appropriate dashboard based on role
     */
    protected function redirectToDashboard()
    {
        $role = $this->session->get('role');

        switch ($role) {
            case 'tsanawiyyah':
                return redirect()->to('/dashboard/tsanawiyyah');
            case 'muallimin':
                return redirect()->to('/dashboard/muallimin');
            case 'superadmin':
            default:
                return redirect()->to('/dashboard');
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        $username = $this->session->get('username');

        // Log activity
        log_message('info', 'User logout: ' . $username);

        // Destroy session
        $this->session->destroy();

        return redirect()->to('/auth/login')->with('success', 'Anda telah berhasil logout.');
    }
}
