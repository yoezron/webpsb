<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    /**
     * Check if user has required role
     *
     * Usage in routes:
     * $routes->get('dashboard/tsanawiyyah', 'Dashboard::tsanawiyyah', ['filter' => 'role:tsanawiyyah,superadmin']);
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if logged in first
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/auth/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // If no role arguments specified, allow access
        if (empty($arguments)) {
            return $request;
        }

        $userRole = $session->get('role');

        // Superadmin has access to everything
        if ($userRole === 'superadmin') {
            return $request;
        }

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $arguments)) {
            return redirect()->to('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $request;
    }

    /**
     * After filter - not used
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }
}
