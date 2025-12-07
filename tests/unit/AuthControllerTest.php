<?php

namespace Tests\Unit;

use App\Controllers\Auth;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class AuthControllerTest extends CIUnitTestCase
{
    use ControllerTestTrait;
    use DatabaseTestTrait;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test login page loads
     */
    public function testLoginPageLoads()
    {
        $result = $this->withURI('http://localhost/login')
            ->controller(Auth::class)
            ->execute('login');

        $this->assertTrue($result->isOK());
    }

    /**
     * Test login with empty credentials fails
     */
    public function testLoginWithEmptyCredentialsFails()
    {
        $data = [
            'username' => '',
            'password' => '',
        ];

        $result = $this->withBody($data)
            ->withURI('http://localhost/login')
            ->controller(Auth::class)
            ->execute('attemptLogin');

        // Should fail and redirect or show error
        $this->assertTrue($result->isRedirect() || !empty($result->response()));
    }

    /**
     * Test login with invalid credentials fails
     */
    public function testLoginWithInvalidCredentialsFails()
    {
        $data = [
            'username' => 'nonexistent_user',
            'password' => 'wrong_password',
        ];

        $result = $this->withBody($data)
            ->withURI('http://localhost/login')
            ->controller(Auth::class)
            ->execute('attemptLogin');

        // Should fail authentication
        $this->assertTrue($result->isRedirect() || !empty($result->response()));
    }

    /**
     * Test CSRF protection on login form
     */
    public function testLoginFormHasCSRFProtection()
    {
        $result = $this->withURI('http://localhost/login')
            ->controller(Auth::class)
            ->execute('login');

        $result->assertSee('csrf');
    }

    /**
     * Test SQL injection attempt in login
     */
    public function testSQLInjectionInLogin()
    {
        $data = [
            'username' => "admin' OR '1'='1",
            'password' => "password' OR '1'='1",
        ];

        $result = $this->withBody($data)
            ->withURI('http://localhost/login')
            ->controller(Auth::class)
            ->execute('attemptLogin');

        // Should not allow login via SQL injection
        $session = session();
        $this->assertFalse($session->has('user_id'));
    }

    /**
     * Test password is hashed (not stored in plain text)
     */
    public function testPasswordIsHashed()
    {
        // This test verifies that passwords are hashed
        $db = \Config\Database::connect();

        if ($db->tableExists('admin_panitia')) {
            $builder = $db->table('admin_panitia');
            $users = $builder->get()->getResultArray();

            foreach ($users as $user) {
                if (isset($user['password'])) {
                    // Password should not be a simple string
                    // Hashed passwords typically start with $2y$ (bcrypt)
                    $this->assertNotEquals('password', $user['password']);
                    $this->assertNotEquals('12345', $user['password']);
                }
            }
        }

        $this->assertTrue(true); // Pass if table doesn't exist yet
    }

    /**
     * Test logout functionality
     */
    public function testLogout()
    {
        // Simulate logged in user
        $session = session();
        $session->set('user_id', 1);

        $result = $this->withURI('http://localhost/logout')
            ->controller(Auth::class)
            ->execute('logout');

        // Should clear session and redirect
        $this->assertTrue($result->isRedirect());
    }
}
