<?php

namespace App\Controllers;

use App\Libraries\FormPendaftaran;
use App\Models\PendaftarModel;
use App\Models\AlamatModel;
use App\Models\AyahModel;
use App\Models\IbuModel;
use App\Models\WaliModel;
use App\Models\BansosModel;
use App\Models\SekolahModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PendaftaranLengkap extends BaseController
{
    protected $pendaftarModel;
    protected $alamatModel;
    protected $ayahModel;
    protected $ibuModel;
    protected $waliModel;
    protected $bansosModel;
    protected $sekolahModel;
    protected $db;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
        $this->alamatModel = new AlamatModel();
        $this->ayahModel = new AyahModel();
        $this->ibuModel = new IbuModel();
        $this->waliModel = new WaliModel();
        $this->bansosModel = new BansosModel();
        $this->sekolahModel = new SekolahModel();
        $this->db = \Config\Database::connect();
    }

    /**
     * Form pendaftaran Tsanawiyyah
     */
    public function tsanawiyyah()
    {
        if ($tutup = $this->cekPendaftaranDibuka('TSANAWIYYAH')) {
            return $tutup;
        }

        $data = [
            'title' => 'Pendaftaran Tsanawiyyah - Pesantren Persatuan Islam 31 Banjaran',
            'jalur' => 'TSANAWIYYAH',
            'jalur_label' => 'Tsanawiyyah',
            'year' => periode_tahun_mulai(),
        ];

        return view('pendaftaran/form_lengkap', $data);
    }

    /**
     * Form pendaftaran Muallimin
     */
    public function muallimin()
    {
        if ($tutup = $this->cekPendaftaranDibuka('MUALLIMIN')) {
            return $tutup;
        }

        $data = [
            'title' => 'Pendaftaran Mu\'allimin - Pesantren Persatuan Islam 31 Banjaran',
            'jalur' => 'MUALLIMIN',
            'jalur_label' => 'Mu\'allimin',
            'year' => periode_tahun_mulai(),
        ];

        return view('pendaftaran/form_lengkap', $data);
    }

    /**
     * Submit pendaftaran - Main submission handler
     * Alias for submitPendaftaran for backward compatibility
     */
    public function submit($jalur = null)
    {
        return $this->submitPendaftaran($jalur);
    }

    /**
     * Submit complete registration form with transactional insert to 7 tables
     *
     * @param string|null $jalur Registration track (TSANAWIYYAH/MUALLIMIN)
     * @return \CodeIgniter\HTTP\RedirectResponse
     */
    public function submitPendaftaran($jalur = null)
    {
        // Log submission attempt
        $this->logPendaftaran('info', 'Pendaftaran submission initiated', [
            'jalur' => $jalur,
            'ip_address' => $this->request->getIPAddress(),
            'user_agent' => $this->request->getUserAgent()->getAgentString(),
        ]);

        // Validate jalur
        if (!$jalur || !in_array(strtoupper($jalur), ['TSANAWIYYAH', 'MUALLIMIN'])) {
            $this->logPendaftaran('warning', 'Invalid jalur pendaftaran', ['jalur' => $jalur]);
            return redirect()->to(base_url('/'))->with('error', 'Jalur pendaftaran tidak valid.');
        }

        $jalurUpper = strtoupper($jalur);

        if ($tutup = $this->cekPendaftaranDibuka($jalurUpper)) {
            return $tutup;
        }

        // Comprehensive validation (aturan Ayah/Ibu/Wali dinamis sesuai status)
        $form = new FormPendaftaran($this->request);
        $validationRules = array_merge($form->rules(), $form->rulesFileKk(true), $this->getValidationRules());
        $validationMessages = array_merge($form->messages(), $this->getValidationMessages());

        if (!$this->validate($validationRules, $validationMessages)) {
            $errors = $this->validator->getErrors();
            $this->logPendaftaran('warning', 'Validation failed', [
                'jalur' => $jalurUpper,
                'errors' => $errors,
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Data tidak valid! Mohon periksa kembali formulir Anda.')
                ->with('validation_errors', $errors);
        }

        // Tolak NISN/NIK yang sudah terdaftar pada periode aktif
        $duplikat = $form->cekDuplikat(periode_aktif_id());
        if ($duplikat !== []) {
            $this->logPendaftaran('warning', 'Pendaftaran ganda ditolak', [
                'jalur' => $jalurUpper,
                'errors' => $duplikat,
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Calon santri ini sudah terdaftar pada periode ini.')
                ->with('validation_errors', $duplikat);
        }

        // Simpan file Kartu Keluarga (dihapus lagi bila penyimpanan data gagal)
        $fileKk = null;

        // Start database transaction
        $this->db->transStart();

        try {
            // Track inserted tables for rollback logging
            $insertedTables = [];

            $fileKk = $form->simpanFileKk((string) $this->request->getPost('nisn'));
            if (!$fileKk) {
                throw new \Exception('File Kartu Keluarga gagal diunggah. Silakan pilih ulang file JPG/JPEG maksimal 1 MB.');
            }

            // =====================================================
            // TABLE 1: pendaftar (Data Diri)
            // =====================================================
            $pendaftarData = $this->preparePendaftarData($jalurUpper, $form);
            $pendaftarData['file_kk'] = $fileKk;

            $idPendaftar = $this->pendaftarModel->insert($pendaftarData);
            if (!$idPendaftar) {
                $modelErrors = $this->pendaftarModel->errors();
                throw new \Exception('Gagal menyimpan data pendaftar: ' . implode(', ', $modelErrors));
            }
            $insertedTables[] = 'pendaftar';

            // Retrieve generated registration number
            $pendaftar = $this->pendaftarModel->find($idPendaftar);
            $nomorPendaftaran = $pendaftar['nomor_pendaftaran'];

            $this->logPendaftaran('info', 'Data pendaftar berhasil disimpan', [
                'id_pendaftar' => $idPendaftar,
                'nomor_pendaftaran' => $nomorPendaftaran,
            ]);

            // =====================================================
            // TABLE 2: alamat_pendaftar (Data Alamat)
            // =====================================================
            $alamatData = ['id_pendaftar' => $idPendaftar] + $form->dataAlamat();

            if (!$this->alamatModel->insert($alamatData)) {
                $modelErrors = $this->alamatModel->errors();
                throw new \Exception('Gagal menyimpan data alamat: ' . implode(', ', $modelErrors));
            }
            $insertedTables[] = 'alamat_pendaftar';

            $this->logPendaftaran('info', 'Data alamat berhasil disimpan', [
                'id_pendaftar' => $idPendaftar,
            ]);

            // =====================================================
            // TABLE 3: data_ayah (Data Ayah)
            // =====================================================
            $ayahData = ['id_pendaftar' => $idPendaftar] + $form->dataOrang('ayah');

            if (!$this->ayahModel->insert($ayahData)) {
                $modelErrors = $this->ayahModel->errors();
                throw new \Exception('Gagal menyimpan data ayah: ' . implode(', ', $modelErrors));
            }
            $insertedTables[] = 'data_ayah';

            $this->logPendaftaran('info', 'Data ayah berhasil disimpan', [
                'id_pendaftar' => $idPendaftar,
            ]);

            // =====================================================
            // TABLE 4: data_ibu (Data Ibu)
            // =====================================================
            $ibuData = ['id_pendaftar' => $idPendaftar] + $form->dataOrang('ibu');

            if (!$this->ibuModel->insert($ibuData)) {
                $modelErrors = $this->ibuModel->errors();
                throw new \Exception('Gagal menyimpan data ibu: ' . implode(', ', $modelErrors));
            }
            $insertedTables[] = 'data_ibu';

            $this->logPendaftaran('info', 'Data ibu berhasil disimpan', [
                'id_pendaftar' => $idPendaftar,
            ]);

            // =====================================================
            // TABLE 5: data_wali (Data Wali - Optional)
            // =====================================================
            if ($form->adaWali()) {
                $waliData = ['id_pendaftar' => $idPendaftar] + $form->dataOrang('wali');

                if (!$this->waliModel->insert($waliData)) {
                    $modelErrors = $this->waliModel->errors();
                    throw new \Exception('Gagal menyimpan data wali: ' . implode(', ', $modelErrors));
                }
                $insertedTables[] = 'data_wali';

                $this->logPendaftaran('info', 'Data wali berhasil disimpan', [
                    'id_pendaftar' => $idPendaftar,
                ]);
            }

            // =====================================================
            // TABLE 6: bansos_pendaftar (Data Bansos - Optional)
            // =====================================================
            if ($form->adaBansos()) {
                $bansosData = ['id_pendaftar' => $idPendaftar] + $form->dataBansos();

                if (!$this->bansosModel->insert($bansosData)) {
                    $modelErrors = $this->bansosModel->errors();
                    throw new \Exception('Gagal menyimpan data bansos: ' . implode(', ', $modelErrors));
                }
                $insertedTables[] = 'bansos_pendaftar';

                $this->logPendaftaran('info', 'Data bansos berhasil disimpan', [
                    'id_pendaftar' => $idPendaftar,
                ]);
            }

            // =====================================================
            // TABLE 7: asal_sekolah (Data Asal Sekolah)
            // =====================================================
            $sekolahData = $this->prepareSekolahData($idPendaftar);

            if (!$this->sekolahModel->insert($sekolahData)) {
                $modelErrors = $this->sekolahModel->errors();
                throw new \Exception('Gagal menyimpan data sekolah: ' . implode(', ', $modelErrors));
            }
            $insertedTables[] = 'asal_sekolah';

            $this->logPendaftaran('info', 'Data asal sekolah berhasil disimpan', [
                'id_pendaftar' => $idPendaftar,
            ]);

            // =====================================================
            // Commit Transaction
            // =====================================================
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal. Silakan coba lagi.');
            }

            // Log successful registration
            $this->logPendaftaran('info', 'PENDAFTARAN BERHASIL', [
                'nomor_pendaftaran' => $nomorPendaftaran,
                'jalur' => $jalurUpper,
                'nama' => $pendaftarData['nama_lengkap'],
                'tables_inserted' => $insertedTables,
                'total_tables' => count($insertedTables),
            ]);

            // Redirect to success page
            return redirect()->to(base_url('pendaftaran/success/' . $nomorPendaftaran))
                ->with('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $nomorPendaftaran);
        } catch (DatabaseException $e) {
            // Database-specific error
            $this->db->transRollback();
            FormPendaftaran::hapusFileKk($fileKk);

            $this->logPendaftaran('error', 'Database error during registration', [
                'jalur' => $jalurUpper,
                'error_message' => $e->getMessage(),
                'error_code' => $e->getCode(),
                'tables_inserted_before_error' => $insertedTables ?? [],
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi atau hubungi administrator.');
        } catch (\Exception $e) {
            // General error
            $this->db->transRollback();
            FormPendaftaran::hapusFileKk($fileKk);

            $this->logPendaftaran('error', 'Error during registration', [
                'jalur' => $jalurUpper,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'tables_inserted_before_error' => $insertedTables ?? [],
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Prepare pendaftar (data diri) data array
     */
    private function preparePendaftarData(string $jalur, FormPendaftaran $form): array
    {
        return ['jalur_pendaftaran' => $jalur] + $form->dataPendaftar();
    }

    /**
     * Prepare asal sekolah data array
     */
    private function prepareSekolahData(int $idPendaftar): array
    {
        return [
            'id_pendaftar' => $idPendaftar,
            'nama_asal_sekolah' => trim($this->request->getPost('nama_asal_sekolah') ?? ''),
            'jenjang_sekolah' => $this->request->getPost('jenjang_sekolah'),
            'status_sekolah' => $this->request->getPost('status_sekolah'),
            'npsn' => trim($this->request->getPost('npsn') ?? ''),
            'lokasi_sekolah' => trim($this->request->getPost('lokasi_sekolah') ?? ''),
            'alamat_sekolah' => trim($this->request->getPost('alamat_sekolah') ?? ''),  // Sprint 2 NEW
            'tahun_lulus' => $this->request->getPost('tahun_lulus') ?: null,  // Sprint 2 NEW
            'rata_rata_rapor' => $this->request->getPost('rata_rata_rapor') ?: null,  // Sprint 2 NEW
            'nilai_tka' => $this->request->getPost('nilai_tka') ?: null,  // Sprint 2 NEW
            'sekolah_md' => trim($this->request->getPost('sekolah_md') ?? ''),  // Sprint 2 NEW
            'asal_jenjang' => $this->request->getPost('asal_jenjang'),
        ];
    }

    /**
     * Log pendaftaran events
     */
    private function logPendaftaran(string $level, string $message, array $context = []): void
    {
        $logContext = array_merge([
            'timestamp' => date('Y-m-d H:i:s'),
            'module' => 'PENDAFTARAN',
        ], $context);

        log_message($level, '[PSB] ' . $message . ' | Context: ' . json_encode($logContext));
    }

    /**
     * Success page with complete registration confirmation
     */
    public function sukses($nomorPendaftaran = null)
    {
        if (!$nomorPendaftaran) {
            $this->logPendaftaran('warning', 'Success page accessed without nomor pendaftaran');
            return redirect()->to(base_url('/'));
        }

        $pendaftar = $this->pendaftarModel->where('nomor_pendaftaran', $nomorPendaftaran)->first();

        if (!$pendaftar) {
            $this->logPendaftaran('warning', 'Success page: pendaftar not found', [
                'nomor_pendaftaran' => $nomorPendaftaran,
            ]);
            return redirect()->to(base_url('/'))->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $idPendaftar = $pendaftar['id_pendaftar'];

        // Get all related data for complete confirmation
        $alamat = $this->alamatModel->where('id_pendaftar', $idPendaftar)->first();
        $ayah = $this->ayahModel->where('id_pendaftar', $idPendaftar)->first();
        $ibu = $this->ibuModel->where('id_pendaftar', $idPendaftar)->first();
        $wali = $this->waliModel->where('id_pendaftar', $idPendaftar)->first();
        $bansos = $this->bansosModel->where('id_pendaftar', $idPendaftar)->first();
        $sekolah = $this->sekolahModel->where('id_pendaftar', $idPendaftar)->first();

        // Log success page access
        $this->logPendaftaran('info', 'Success page accessed', [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nama' => $pendaftar['nama_lengkap'],
        ]);

        $data = [
            'title' => 'Pendaftaran Berhasil - Pesantren Persatuan Islam 31 Banjaran',
            'pendaftar' => $pendaftar,
            'alamat' => $alamat,
            'ayah' => $ayah,
            'ibu' => $ibu,
            'wali' => $wali,
            'bansos' => $bansos,
            'sekolah' => $sekolah,
            'year' => periode_tahun_mulai(),
        ];

        return view('pendaftaran/sukses_lengkap', $data);
    }

    /**
     * Download comprehensive PDF with all registration data
     */
    public function downloadPdf($nomorPendaftaran = null)
    {
        // CRITICAL: Load vendor autoloader FIRST
        $vendorPath = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($vendorPath)) {
            require_once $vendorPath;
        }

        if (!$nomorPendaftaran) {
            return redirect()->to(base_url('/'));
        }

        // Get pendaftar with all related data from 7 tables
        $pendaftar = $this->pendaftarModel->where('nomor_pendaftaran', $nomorPendaftaran)->first();

        if (!$pendaftar) {
            return redirect()->to(base_url('/'))->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Get all related data from 7 tables
        $alamat = $this->alamatModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();
        $ayah = $this->ayahModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();
        $ibu = $this->ibuModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();
        $wali = $this->waliModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();
        $bansos = $this->bansosModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();
        $sekolah = $this->sekolahModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();

        // Generate Logo Data URI
        $logoDataUri = $this->generateLogoDataUri();

        // Prepare comprehensive data for PDF template
        $data = [
            'pendaftar' => $pendaftar,
            'alamat' => $alamat,
            'ayah' => $ayah,
            'ibu' => $ibu,
            'wali' => $wali,
            'bansos' => $bansos,
            'sekolah' => $sekolah,
            'logo' => $logoDataUri,
        ];

        // Generate HTML from comprehensive template
        $html = view('pendaftaran/pdf_lengkap_template', $data);

        // Initialize Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false);
        $options->set('defaultFont', 'Arial');
        $options->set('chroot', FCPATH);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Log PDF generation
        $this->logPendaftaran('info', 'PDF Lengkap downloaded', [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nama' => $pendaftar['nama_lengkap'],
        ]);

        // Stream PDF to browser for download
        $filename = 'Data_Lengkap_' . $nomorPendaftaran . '.pdf';

        return $this->pdfResponse($dompdf->output(['compress' => 1]), $filename);
    }

    /**
     * Generate and download registration card as PDF
     */
    public function downloadKartu($nomorPendaftaran = null)
    {
        // CRITICAL: Load vendor autoloader FIRST - direct inline require
        $vendorPath = __DIR__ . '/../../vendor/autoload.php';
        if (file_exists($vendorPath)) {
            require_once $vendorPath;
        }

        if (!$nomorPendaftaran) {
            return redirect()->to(base_url('/'));
        }

        $pendaftar = $this->pendaftarModel->where('nomor_pendaftaran', $nomorPendaftaran)->first();

        if (!$pendaftar) {
            return redirect()->to(base_url('/'))->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Get related data
        $sekolah = $this->sekolahModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();

        // Generate QR Code
        $qrCodeDataUri = $this->generateQRCode($nomorPendaftaran);

        // Generate Logo Data URI
        $logoDataUri = $this->generateLogoDataUri();

        // Prepare data for PDF template
        $data = [
            'pendaftar' => $pendaftar,
            'sekolah' => $sekolah,
            'qrCode' => $qrCodeDataUri,
            'logo' => $logoDataUri,
        ];

        // Generate HTML from template
        $html = view('pendaftaran/pdf_template', $data);

        // Initialize Dompdf
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', false); // Disable to prevent hanging on remote resources
        $options->set('defaultFont', 'Arial');
        $options->set('chroot', FCPATH);

        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF
        $dompdf->render();

        // Log PDF generation
        $this->logPendaftaran('info', 'PDF Kartu Pendaftaran downloaded', [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nama' => $pendaftar['nama_lengkap'],
        ]);

        // Stream PDF to browser for download
        $filename = 'Kartu_Pendaftaran_' . $nomorPendaftaran . '.pdf';

        return $this->pdfResponse($dompdf->output(['compress' => 1]), $filename);
    }

    /**
     * Kirim PDF lewat Response CodeIgniter.
     * (Dompdf::stream() mengirim header & output sendiri sehingga bentrok dengan
     * cookie CSRF yang dikirim CodeIgniter -> "headers already sent".)
     */
    private function pdfResponse(string $pdf, string $filename)
    {
        $filename = preg_replace('/[^A-Za-z0-9_\-.]/', '_', $filename);

        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setHeader('Content-Length', (string) strlen($pdf))
            ->setHeader('Cache-Control', 'private, no-store')
            ->setBody($pdf);
    }

    /**
     * Ensure vendor autoloader is loaded
     * Fixes autoloading issues in web server environment
     */
    private function ensureVendorAutoloaded(): void
    {
        static $loaded = false;

        if ($loaded) {
            return;
        }

        // Always load vendor autoloader to ensure all packages are available
        $possiblePaths = [
            ROOTPATH . 'vendor/autoload.php',
            __DIR__ . '/../../vendor/autoload.php',
            dirname(dirname(dirname(__DIR__))) . '/vendor/autoload.php',
        ];

        foreach ($possiblePaths as $path) {
            if (file_exists($path)) {
                require_once $path;
                $loaded = true;

                // Log what happened
                $this->logPendaftaran('info', 'Vendor autoloader loaded', [
                    'path' => $path,
                    'dompdf_available' => class_exists('\Dompdf\Dompdf'),
                    'qrcode_available' => class_exists('\Endroid\QrCode\QrCode'),
                ]);

                break;
            }
        }

        if (!$loaded) {
            $this->logPendaftaran('error', 'Failed to load vendor autoloader', [
                'tried_paths' => $possiblePaths,
            ]);
        }
    }

    /**
     * Generate QR Code for registration number
     */
    private function generateQRCode(string $nomorPendaftaran): string
    {
        try {
            // Check if class is available
            if (!class_exists('\Endroid\QrCode\QrCode')) {
                throw new \Exception('Endroid QR Code library not available');
            }

            // Create QR code instance
            $qrCode = new \Endroid\QrCode\QrCode(
                data: $nomorPendaftaran,
                size: 200,
                margin: 10
            );

            // Create PNG writer
            $writer = new \Endroid\QrCode\Writer\PngWriter();

            // Generate the QR code result
            $result = $writer->write($qrCode);

            // Convert to data URI for embedding in PDF
            $dataUri = $result->getDataUri();

            return $dataUri;
        } catch (\Throwable $e) {
            // Log error and return empty string if QR code generation fails
            $this->logPendaftaran('warning', 'QR Code generation failed - PDF will generate without QR code', [
                'nomor_pendaftaran' => $nomorPendaftaran,
                'error' => $e->getMessage(),
                'error_class' => get_class($e),
            ]);

            // Return empty string - PDF will generate without QR code
            return '';
        }
    }

    /**
     * Generate logo as base64 data URI for embedding in PDF
     * This avoids issues with remote resources in Dompdf
     */
    private function generateLogoDataUri(): string
    {
        try {
            // Define logo path - relative to FCPATH (public directory)
            $logoPath = logo_path(); // logo unggahan superadmin, atau logo bawaan

            // Check if logo file exists
            if (!file_exists($logoPath)) {
                throw new \Exception('Logo file not found at: ' . $logoPath);
            }

            // Read logo file contents
            $imageData = file_get_contents($logoPath);
            if ($imageData === false) {
                throw new \Exception('Failed to read logo file');
            }

            // Get image mime type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $logoPath);
            finfo_close($finfo);

            // Convert to base64 data URI
            $base64 = base64_encode($imageData);
            $dataUri = 'data:' . $mimeType . ';base64,' . $base64;

            return $dataUri;
        } catch (\Throwable $e) {
            // Log error and return empty string if logo generation fails
            $this->logPendaftaran('warning', 'Logo generation failed - PDF will generate without logo', [
                'error' => $e->getMessage(),
                'logo_path' => $logoPath ?? 'unknown',
            ]);

            // Return empty string - PDF will generate without logo (graceful degradation)
            return '';
        }
    }

    /**
     * Comprehensive validation rules for all 7 tables
     */
    private function getValidationRules(): array
    {
        return [
            // Section 1-6 (Data Diri, Alamat, Ayah, Ibu, Wali, Bansos) ada di App\Libraries\FormPendaftaran

            // =====================================================
            // Section 7: Data Asal Sekolah (asal_sekolah table)
            // =====================================================
            'nama_asal_sekolah' => 'required|max_length[200]',
            'jenjang_sekolah' => 'permit_empty|in_list[SD,MI,SMP,MTs,Paket A,Paket B,Lainnya]',
            'status_sekolah' => 'permit_empty|in_list[Negeri,Swasta]',
            'npsn' => 'permit_empty|numeric|exact_length[8]',
            'lokasi_sekolah' => 'permit_empty|max_length[255]',
            'asal_jenjang' => 'permit_empty|max_length[200]',

            // =====================================================
            // Confirmation
            // =====================================================
            'confirm_data' => 'required',
        ];
    }

    /**
     * Validation error messages in Indonesian
     */
    private function getValidationMessages(): array
    {
        return [
            // Data Sekolah
            'nama_asal_sekolah' => [
                'required' => 'Nama asal sekolah wajib diisi.',
                'max_length' => 'Nama asal sekolah maksimal 200 karakter.',
            ],
            'npsn' => [
                'numeric' => 'NPSN harus berupa angka.',
                'exact_length' => 'NPSN harus 8 digit.',
            ],

            // Confirmation
            'confirm_data' => [
                'required' => 'Anda harus menyetujui kebenaran data yang diisi.',
            ],
        ];
    }

    /**
     * Tolak akses form bila pendaftaran periode aktif ditutup / kuota penuh.
     */
    private function cekPendaftaranDibuka(string $jalur)
    {
        $status = (new \App\Models\PeriodeModel())->statusPendaftaran($jalur);
        if (!$status['dibuka']) {
            return redirect()->to(base_url('/'))->with('error', $status['alasan']);
        }

        return null;
    }
}
