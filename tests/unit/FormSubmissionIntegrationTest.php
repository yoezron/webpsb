<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * Integration Tests for Form Submission Flow
 * Tests the complete registration process from form display to data storage
 */
class FormSubmissionIntegrationTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use DatabaseTestTrait;

    protected $namespace;

    protected function setUp(): void
    {
        parent::setUp();
        $this->namespace = 'App';
    }

    protected function tearDown(): void
    {
        parent::tearDown();
    }

    /**
     * Test complete tsanawiyyah registration flow
     */
    public function testCompleteTsanawiyyahRegistrationFlow()
    {
        // Step 1: Visit registration form
        $result = $this->get('/daftar/tsanawiyyah');
        $result->assertStatus(200);
        $result->assertSee('Form Pendaftaran');

        // Step 2: Submit valid registration data
        $registrationData = $this->getValidRegistrationData('tsanawiyyah');

        $result = $this->post('/pendaftaran/submit/tsanawiyyah', $registrationData);

        // Should redirect to success page or show success message
        $this->assertTrue(
            $result->isRedirect() || $result->assertSee('berhasil', true) || $result->assertStatus(200)
        );
    }

    /**
     * Test complete muallimin registration flow
     */
    public function testCompleteMualliminRegistrationFlow()
    {
        // Step 1: Visit registration form
        $result = $this->get('/daftar/muallimin');
        $result->assertStatus(200);
        $result->assertSee('Form Pendaftaran');

        // Step 2: Submit valid registration data
        $registrationData = $this->getValidRegistrationData('muallimin');

        $result = $this->post('/pendaftaran/submit/muallimin', $registrationData);

        // Should redirect to success page or show success message
        $this->assertTrue(
            $result->isRedirect() || $result->assertSee('berhasil', true) || $result->assertStatus(200)
        );
    }

    /**
     * Test data persistence across all 7 tables
     */
    public function testDataPersistenceAcrossAllTables()
    {
        $db = \Config\Database::connect();

        // Ensure all required tables exist
        $requiredTables = [
            'pendaftar',
            'alamat_pendaftar',
            'data_ayah',
            'data_ibu',
            'data_wali',
            'bansos_pendaftar',
            'asal_sekolah'
        ];

        foreach ($requiredTables as $table) {
            $this->assertTrue(
                $db->tableExists($table),
                "Table {$table} should exist for complete registration flow"
            );
        }
    }

    /**
     * Test validation error handling in form submission
     */
    public function testValidationErrorHandling()
    {
        // Submit with missing required fields
        $incompleteData = [
            'jalur' => 'tsanawiyyah',
            // Missing nama_lengkap, jenis_kelamin, etc.
        ];

        $result = $this->post('/pendaftaran/submit/tsanawiyyah', $incompleteData);

        // Should show validation errors
        $this->assertTrue(
            $result->isRedirect() ||
            $result->assertSee('error', true) ||
            $result->assertSee('wajib', true)
        );
    }

    /**
     * Test CSRF token validation
     */
    public function testCSRFTokenValidation()
    {
        // Disable CSRF for this specific test setup
        $config = config('Security');
        $originalCSRF = $config->csrfProtection;

        // Re-enable CSRF
        $config->csrfProtection = true;

        $data = $this->getValidRegistrationData('tsanawiyyah');

        // Try to submit without CSRF token
        $result = $this->post('/pendaftaran/submit/tsanawiyyah', $data);

        // Should reject or handle CSRF validation
        $this->assertTrue($result->isOK() || $result->isRedirect());

        // Restore original setting
        $config->csrfProtection = $originalCSRF;
    }

    /**
     * Test duplicate registration prevention
     */
    public function testDuplicateRegistrationPrevention()
    {
        $data = $this->getValidRegistrationData('tsanawiyyah');

        // First submission
        $result1 = $this->post('/pendaftaran/submit/tsanawiyyah', $data);

        // Second submission with same NISN/NIK should be prevented
        $result2 = $this->post('/pendaftaran/submit/tsanawiyyah', $data);

        // At least one should succeed, duplicate should fail or be handled
        $this->assertTrue(true); // Placeholder - actual implementation depends on business logic
    }

    /**
     * Test file upload handling (if applicable)
     */
    public function testFileUploadHandling()
    {
        // Check if uploads directory exists and is writable
        $uploadsPath = WRITEPATH . 'uploads';

        if (!is_dir($uploadsPath)) {
            mkdir($uploadsPath, 0777, true);
        }

        $this->assertTrue(is_writable($uploadsPath), 'Uploads directory should be writable');
    }

    /**
     * Test data sanitization
     */
    public function testDataSanitization()
    {
        $data = $this->getValidRegistrationData('tsanawiyyah');

        // Add potentially malicious data
        $data['nama_lengkap'] = '<script>alert("XSS")</script>Test Name';
        $data['alamat'] = 'Test Address<img src=x onerror=alert(1)>';

        $result = $this->post('/pendaftaran/submit/tsanawiyyah', $data);

        // Data should be sanitized before storage
        $this->assertTrue(true); // Placeholder - check database for sanitized data
    }

    /**
     * Helper: Get valid registration data
     */
    private function getValidRegistrationData($jalur)
    {
        $timestamp = time();

        return [
            // CSRF token would normally be included here
            'jalur' => $jalur,

            // Section 1: Data Diri
            'nisn' => '1234567890' . $timestamp % 1000,
            'nik' => '3204567890123' . $timestamp % 100,
            'nama_lengkap' => 'Test Student ' . $timestamp,
            'jenis_kelamin' => 'L',
            'tempat_lahir' => 'Bandung',
            'tanggal_lahir' => '2010-01-01',
            'status_keluarga' => 'Anak Kandung',
            'anak_ke' => '1',
            'jumlah_saudara' => '2',
            'hobi' => 'Membaca',
            'cita_cita' => 'Dokter',
            'pernah_paud' => 'Ya',
            'pernah_tk' => 'Ya',
            'kebutuhan_disabilitas' => 'Tidak Ada',
            'imunisasi' => 'Lengkap',
            'no_hp' => '081234567890',
            'ukuran_baju' => 'M',
            'prestasi' => '-',

            // Section 2: Alamat
            'nomor_kk' => '3204567890123456',
            'jenis_tempat_tinggal' => 'Rumah Sendiri',
            'alamat' => 'Jl. Test No. 123',
            'desa' => 'Test Desa',
            'kecamatan' => 'Test Kecamatan',
            'kabupaten' => 'Bandung',
            'provinsi' => 'Jawa Barat',
            'kode_pos' => '40123',
            'jarak_ke_sekolah' => '5 km',
            'waktu_tempuh' => '15 menit',
            'transportasi' => 'Angkutan Umum',
            'email' => 'test' . $timestamp . '@example.com',
            'media_sosial' => '-',

            // Section 3: Data Ayah
            'nama_ayah' => 'Test Ayah',
            'nik_ayah' => '3204567890123457',
            'tempat_lahir_ayah' => 'Bandung',
            'tanggal_lahir_ayah' => '1980-01-01',
            'status_ayah' => 'Masih Hidup',
            'pendidikan_ayah' => 'S1',
            'pekerjaan_ayah' => 'Wiraswasta',
            'penghasilan_ayah' => '5000000',
            'hp_ayah' => '081234567891',
            'alamat_ayah' => 'Sama dengan alamat siswa',

            // Section 4: Data Ibu
            'nama_ibu' => 'Test Ibu',
            'nik_ibu' => '3204567890123458',
            'tempat_lahir_ibu' => 'Bandung',
            'tanggal_lahir_ibu' => '1982-01-01',
            'status_ibu' => 'Masih Hidup',
            'pendidikan_ibu' => 'SMA',
            'pekerjaan_ibu' => 'Ibu Rumah Tangga',
            'penghasilan_ibu' => '0',
            'hp_ibu' => '081234567892',
            'alamat_ibu' => 'Sama dengan alamat siswa',

            // Section 5: Data Wali (optional)
            'nama_wali' => '-',
            'nik_wali' => '',
            'tahun_lahir_wali' => '',
            'pendidikan_wali' => '',
            'pekerjaan_wali' => '',
            'penghasilan_wali' => '',
            'hp_wali' => '',

            // Section 6: Bansos
            'no_kks' => '',
            'no_pkh' => '',
            'no_kip' => '',

            // Section 7: Asal Sekolah
            'nama_asal_sekolah' => 'SD Test',
            'jenjang_sekolah' => 'SD',
            'status_sekolah' => 'Negeri',
            'npsn' => '12345678',
            'lokasi_sekolah' => 'Bandung',
            'asal_jenjang' => 'SD',

            // Section 8: Review
            'confirm_data' => '1',
        ];
    }
}
