<?php

namespace Tests\Unit;

use App\Controllers\PendaftaranLengkap;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\ControllerTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class PendaftaranControllerTest extends CIUnitTestCase
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
     * Test tsanawiyyah form page loads
     */
    public function testTsanawiyyahPageLoads()
    {
        $result = $this->withURI('http://localhost/daftar/tsanawiyyah')
            ->controller(PendaftaranLengkap::class)
            ->execute('tsanawiyyah');

        $this->assertTrue($result->isOK());
    }

    /**
     * Test muallimin form page loads
     */
    public function testMualliminPageLoads()
    {
        $result = $this->withURI('http://localhost/daftar/muallimin')
            ->controller(PendaftaranLengkap::class)
            ->execute('muallimin');

        $this->assertTrue($result->isOK());
    }

    /**
     * Test form contains CSRF protection
     */
    public function testFormContainsCSRFField()
    {
        $result = $this->withURI('http://localhost/daftar/tsanawiyyah')
            ->controller(PendaftaranLengkap::class)
            ->execute('tsanawiyyah');

        $result->assertSee('csrf_field');
    }

    /**
     * Test form contains required fields
     */
    public function testFormContainsRequiredFields()
    {
        $result = $this->withURI('http://localhost/daftar/tsanawiyyah')
            ->controller(PendaftaranLengkap::class)
            ->execute('tsanawiyyah');

        $result->assertSee('nama_lengkap');
        $result->assertSee('jenis_kelamin');
        $result->assertSee('jalur');
    }

    /**
     * Test submit with empty data fails validation
     */
    public function testSubmitWithEmptyDataFails()
    {
        $data = [];

        $result = $this->withURI('http://localhost/pendaftaran/submit/tsanawiyyah')
            ->controller(PendaftaranLengkap::class)
            ->execute('submit', 'tsanawiyyah');

        // Should redirect back with errors
        $this->assertTrue($result->isRedirect() || $result->see('error'));
    }

    /**
     * Test XSS protection on input fields
     */
    public function testXSSProtection()
    {
        $xssPayload = '<script>alert("XSS")</script>';

        $data = [
            'jalur' => 'tsanawiyyah',
            'nama_lengkap' => $xssPayload,
            'jenis_kelamin' => 'L',
        ];

        $result = $this->withBody($data)
            ->withURI('http://localhost/pendaftaran/submit/tsanawiyyah')
            ->controller(PendaftaranLengkap::class)
            ->execute('submit', 'tsanawiyyah');

        // The XSS payload should be escaped/sanitized
        $this->assertStringNotContainsString('<script>', $result->response()->getBody());
    }

    /**
     * Test SQL injection protection
     */
    public function testSQLInjectionProtection()
    {
        $sqlPayload = "'; DROP TABLE pendaftar; --";

        $data = [
            'jalur' => 'tsanawiyyah',
            'nama_lengkap' => $sqlPayload,
            'jenis_kelamin' => 'L',
        ];

        // Should not cause SQL error or drop table
        $result = $this->withBody($data)
            ->withURI('http://localhost/pendaftaran/submit/tsanawiyyah')
            ->controller(PendaftaranLengkap::class)
            ->execute('submit', 'tsanawiyyah');

        // Table should still exist
        $db = \Config\Database::connect();
        $this->assertTrue($db->tableExists('pendaftar'));
    }
}
