<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * AuthFilter - Authentication Filter
 *
 * This filter checks if the user is authenticated (logged in).
 * If not authenticated, redirect to login page.
 */
class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = \Config\Services::session();

        // Check if user is logged in
        if (!$session->get('isLoggedIn')) {
            // Store the intended URL to redirect after login
            $session->set('redirect_url', current_url());

            // Redirect to login with error message
            return redirect()->to('/login')
                ->with('error', 'Anda harus login terlebih dahulu untuk mengakses halaman ini.');
        }

        // Check if user account is still active (optional additional check)
        // You can add database check here if needed
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
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
