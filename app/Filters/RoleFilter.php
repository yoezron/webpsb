<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * RoleFilter - Role-Based Access Control Filter
 *
 * This filter checks if the authenticated user has the required role(s)
 * to access a specific route. If not authorized, redirect to unauthorized page.
 *
 * Usage in Routes.php:
 * $routes->get('/admin', 'Admin::index', ['filter' => 'role:superadmin']);
 * $routes->get('/tsn', 'Dashboard::tsanawiyyah', ['filter' => 'role:superadmin,tsanawiyyah']);
 */
class RoleFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments - Array of allowed roles
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // First check if user is authenticated
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login')
                ->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Get user's role from session
        $userRole = $session->get('role_panitia');

        // If no role specified in filter arguments, allow access
        if (empty($arguments)) {
            return;
        }

        // Convert arguments to array if it's a string
        $allowedRoles = is_array($arguments) ? $arguments : [$arguments];

        // Superadmin has access to everything
        if ($userRole === 'superadmin') {
            return;
        }

        // Check if user's role is in the allowed roles
        if (!in_array($userRole, $allowedRoles)) {
            // Log unauthorized access attempt
            log_message('warning', 'Unauthorized access attempt by user: ' . $session->get('username') . ' (Role: ' . $userRole . ') to ' . current_url());

            // Redirect to unauthorized page
            return redirect()->to('/unauthorized')
                ->with('error', 'Anda tidak memiliki akses ke halaman ini. Role Anda: ' . ucfirst($userRole));
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here if needed after the request is processed
    }
}
