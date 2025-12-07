<?php

namespace Tests\Unit;

use App\Models\PendaftarModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class PendaftarModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $model;
    protected $seed = 'TestSeeder';

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new PendaftarModel();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test model instance creation
     */
    public function testModelInstantiation()
    {
        $this->assertInstanceOf(PendaftarModel::class, $this->model);
    }

    /**
     * Test table name configuration
     */
    public function testTableName()
    {
        $this->assertEquals('pendaftar', $this->model->getTable());
    }

    /**
     * Test primary key configuration
     */
    public function testPrimaryKey()
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('primaryKey');
        $property->setAccessible(true);

        $this->assertEquals('id_pendaftar', $property->getValue($this->model));
    }

    /**
     * Test allowed fields configuration
     */
    public function testAllowedFields()
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('allowedFields');
        $property->setAccessible(true);
        $allowedFields = $property->getValue($this->model);

        $this->assertContains('nomor_pendaftaran', $allowedFields);
        $this->assertContains('jalur_pendaftaran', $allowedFields);
        $this->assertContains('nama_lengkap', $allowedFields);
        $this->assertContains('jenis_kelamin', $allowedFields);
    }

    /**
     * Test validation rules exist
     */
    public function testValidationRulesExist()
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('validationRules');
        $property->setAccessible(true);
        $rules = $property->getValue($this->model);

        $this->assertArrayHasKey('jalur_pendaftaran', $rules);
        $this->assertArrayHasKey('nama_lengkap', $rules);
        $this->assertArrayHasKey('jenis_kelamin', $rules);
    }

    /**
     * Test jalur pendaftaran validation
     */
    public function testJalurPendaftaranValidation()
    {
        $data = [
            'jalur_pendaftaran' => 'INVALID',
            'nama_lengkap' => 'Test Student',
            'jenis_kelamin' => 'L',
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('jalur_pendaftaran', $errors);
    }

    /**
     * Test valid jalur pendaftaran
     */
    public function testValidJalurPendaftaran()
    {
        $validJalurs = ['TSANAWIYYAH', 'MUALLIMIN'];

        foreach ($validJalurs as $jalur) {
            $data = [
                'jalur_pendaftaran' => $jalur,
                'nama_lengkap' => 'Test Student for ' . $jalur,
                'jenis_kelamin' => 'L',
            ];

            // We're testing validation only, not actual insertion
            $this->model->skipValidation(false);
            $isValid = $this->model->validate($data);

            $this->assertTrue($isValid, "Jalur {$jalur} should be valid");
        }
    }

    /**
     * Test nama_lengkap required validation
     */
    public function testNamaLengkapRequired()
    {
        $data = [
            'jalur_pendaftaran' => 'TSANAWIYYAH',
            'jenis_kelamin' => 'L',
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('nama_lengkap', $errors);
    }

    /**
     * Test nama_lengkap minimum length
     */
    public function testNamaLengkapMinLength()
    {
        $data = [
            'jalur_pendaftaran' => 'TSANAWIYYAH',
            'nama_lengkap' => 'AB', // Less than 3 characters
            'jenis_kelamin' => 'L',
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('nama_lengkap', $errors);
    }

    /**
     * Test jenis_kelamin validation
     */
    public function testJenisKelaminValidation()
    {
        $data = [
            'jalur_pendaftaran' => 'TSANAWIYYAH',
            'nama_lengkap' => 'Test Student',
            'jenis_kelamin' => 'X', // Invalid
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('jenis_kelamin', $errors);
    }

    /**
     * Test valid jenis_kelamin values
     */
    public function testValidJenisKelamin()
    {
        $validGenders = ['L', 'P'];

        foreach ($validGenders as $gender) {
            $data = [
                'jalur_pendaftaran' => 'TSANAWIYYAH',
                'nama_lengkap' => 'Test Student',
                'jenis_kelamin' => $gender,
            ];

            $isValid = $this->model->validate($data);
            $this->assertTrue($isValid, "Gender {$gender} should be valid");
        }
    }

    /**
     * Test soft deletes configuration
     */
    public function testSoftDeletesEnabled()
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('useSoftDeletes');
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($this->model));
    }

    /**
     * Test timestamps configuration
     */
    public function testTimestampsEnabled()
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('useTimestamps');
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($this->model));
    }

    /**
     * Test nomor pendaftaran generation callback exists
     */
    public function testNomorPendaftaranCallbackExists()
    {
        $reflection = new \ReflectionClass($this->model);
        $method = $reflection->getMethod('generateNomorPendaftaran');

        $this->assertTrue($method->isProtected());
    }
}
