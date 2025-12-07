<?php

namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\HTTP\RedirectResponse;

class Auth extends BaseController
{
    protected $adminModel;
    protected $session;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Display login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->session->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }

        $data = [
            'title' => 'Login - PSB Persis 31 Banjaran',
            'validation' => \Config\Services::validation()
        ];

        return view('auth/login', $data);
    }

    /**
     * Process login attempt
     */
    public function attemptLogin(): RedirectResponse
    {
        // Validate input
        $rules = [
            'username' => [
                'label' => 'Username',
                'rules' => 'required|min_length[3]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'min_length' => 'Username minimal 3 karakter'
                ]
            ],
            'password' => [
                'label' => 'Password',
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember');

        // Verify login credentials
        $user = $this->adminModel->verifyLogin($username, $password);

        if (!$user) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah');
        }

        // Check if user is active
        if (!$user['is_active']) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Akun Anda tidak aktif. Hubungi administrator.');
        }

        // Update last login
        $this->adminModel->update($user['id_admin'], [
            'last_login' => date('Y-m-d H:i:s')
        ]);

        // Set session data
        $sessionData = [
            'id_admin' => $user['id_admin'],
            'username' => $user['username'],
            'nama_lengkap' => $user['nama_lengkap'],
            'email' => $user['email'],
            'role_panitia' => $user['role_panitia'],
            'isLoggedIn' => true
        ];

        $this->session->set($sessionData);

        // Set remember me cookie if checked (optional enhancement)
        if ($remember) {
            // Cookie expires in 30 days
            $this->response->setCookie('remember_user', $username, 30 * 24 * 60 * 60);
        }

        // Regenerate session ID for security
        $this->session->regenerate();

        // Redirect to dashboard with success message
        return redirect()->to('/dashboard')
            ->with('success', 'Selamat datang, ' . $user['nama_lengkap'] . '!');
    }

    /**
     * Logout user
     */
    public function logout(): RedirectResponse
    {
        // Destroy session
        $this->session->destroy();

        // Redirect to login with message
        return redirect()->to('/login')
            ->with('success', 'Anda telah berhasil logout');
    }

    /**
     * Check if user is authenticated (helper method)
     */
    public function isAuthenticated(): bool
    {
        return $this->session->get('isLoggedIn') === true;
    }

    /**
     * Get current user data
     */
    public function getCurrentUser(): ?array
    {
        if (!$this->isAuthenticated()) {
            return null;
        }

        return [
            'id_admin' => $this->session->get('id_admin'),
            'username' => $this->session->get('username'),
            'nama_lengkap' => $this->session->get('nama_lengkap'),
            'email' => $this->session->get('email'),
            'role_panitia' => $this->session->get('role_panitia')
        ];
    }

    /**
     * Check if user has specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->session->get('role_panitia') === $role;
    }

    /**
     * Check if user has any of the specified roles
     */
    public function hasAnyRole(array $roles): bool
    {
        $userRole = $this->session->get('role_panitia');
        return in_array($userRole, $roles);
    }

    /**
     * Display forgot password page (placeholder for future implementation)
     */
    public function forgotPassword()
    {
        return view('auth/forgot_password', [
            'title' => 'Lupa Password - PSB Persis 31 Banjaran'
        ]);
    }

    /**
     * Unauthorized access handler
     */
    public function unauthorized()
    {
        $data = [
            'title' => 'Akses Ditolak',
            'message' => 'Anda tidak memiliki akses ke halaman ini.'
        ];

        return view('auth/unauthorized', $data);
    }
}
