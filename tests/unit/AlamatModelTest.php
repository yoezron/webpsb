<?php

namespace Tests\Unit;

use App\Models\AlamatModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class AlamatModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new AlamatModel();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    public function testModelInstantiation()
    {
        $this->assertInstanceOf(AlamatModel::class, $this->model);
    }

    public function testTableName()
    {
        $this->assertEquals('alamat_pendaftar', $this->model->getTable());
    }

    public function testAllowedFieldsContainRequiredFields()
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('allowedFields');
        $property->setAccessible(true);
        $allowedFields = $property->getValue($this->model);

        $requiredFields = ['id_pendaftar', 'alamat', 'provinsi', 'kabupaten', 'kecamatan', 'desa'];

        foreach ($requiredFields as $field) {
            $this->assertContains($field, $allowedFields, "Field {$field} should be in allowedFields");
        }
    }

    public function testSoftDeletesEnabled()
    {
        $reflection = new \ReflectionClass($this->model);
        $property = $reflection->getProperty('useSoftDeletes');
        $property->setAccessible(true);

        $this->assertTrue($property->getValue($this->model));
    }
}
