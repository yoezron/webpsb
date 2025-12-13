<?php

namespace App\Controllers;

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
        $data = [
            'title' => 'Pendaftaran Tsanawiyyah - Pesantren Persatuan Islam 31 Banjaran',
            'jalur' => 'TSANAWIYYAH',
            'jalur_label' => 'Tsanawiyyah',
            'year' => 2026,
        ];

        return view('pendaftaran/form_lengkap', $data);
    }

    /**
     * Form pendaftaran Muallimin
     */
    public function muallimin()
    {
        $data = [
            'title' => 'Pendaftaran Mu\'allimin - Pesantren Persatuan Islam 31 Banjaran',
            'jalur' => 'MUALLIMIN',
            'jalur_label' => 'Mu\'allimin',
            'year' => 2026,
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

        // Comprehensive validation
        $validationRules = $this->getValidationRules();
        $validationMessages = $this->getValidationMessages();

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

        // Start database transaction
        $this->db->transStart();

        try {
            // Track inserted tables for rollback logging
            $insertedTables = [];

            // =====================================================
            // TABLE 1: pendaftar (Data Diri)
            // =====================================================
            $pendaftarData = $this->preparePendaftarData($jalurUpper);

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
            $alamatData = $this->prepareAlamatData($idPendaftar);

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
            $ayahData = $this->prepareAyahData($idPendaftar);

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
            $ibuData = $this->prepareIbuData($idPendaftar);

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
            $namaWali = trim($this->request->getPost('nama_wali') ?? '');
            if (!empty($namaWali)) {
                $waliData = $this->prepareWaliData($idPendaftar);

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
            $noKks = trim($this->request->getPost('no_kks') ?? '');
            $noPkh = trim($this->request->getPost('no_pkh') ?? '');
            $noKip = trim($this->request->getPost('no_kip') ?? '');

            if (!empty($noKks) || !empty($noPkh) || !empty($noKip)) {
                $bansosData = $this->prepareBansosData($idPendaftar);

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
    private function preparePendaftarData(string $jalur): array
    {
        return [
            'jalur_pendaftaran' => $jalur,
            'nisn' => trim($this->request->getPost('nisn') ?? ''),
            'nik' => trim($this->request->getPost('nik') ?? ''),
            'nama_lengkap' => trim($this->request->getPost('nama_lengkap')),
            'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
            'tempat_lahir' => trim($this->request->getPost('tempat_lahir') ?? ''),
            'tanggal_lahir' => $this->request->getPost('tanggal_lahir') ?: null,
            'status_keluarga' => $this->request->getPost('status_keluarga'),
            'anak_ke' => $this->request->getPost('anak_ke') ?: null,
            'jumlah_saudara' => $this->request->getPost('jumlah_saudara') ?: null,
            'hobi' => trim($this->request->getPost('hobi') ?? ''),
            'cita_cita' => trim($this->request->getPost('cita_cita') ?? ''),
            'yang_membiayai_sekolah' => $this->request->getPost('yang_membiayai_sekolah'),  // Sprint 2 NEW
            'pernah_paud' => $this->request->getPost('pernah_paud') ? 1 : 0,
            'pernah_tk' => $this->request->getPost('pernah_tk') ? 1 : 0,
            'kebutuhan_disabilitas' => $this->request->getPost('kebutuhan_disabilitas') ? implode(', ', $this->request->getPost('kebutuhan_disabilitas')) : null,
            'kebutuhan_khusus' => $this->request->getPost('kebutuhan_khusus'),  // Sprint 2 NEW
            'imunisasi' => $this->request->getPost('imunisasi') ? implode(', ', $this->request->getPost('imunisasi')) : null,
            'no_hp' => trim($this->request->getPost('no_hp') ?? ''),
            'ukuran_baju' => $this->request->getPost('ukuran_baju'),
            'prestasi' => trim($this->request->getPost('prestasi') ?? ''),
            'minat_bakat' => trim($this->request->getPost('minat_bakat') ?? ''),  // Sprint 2 NEW
        ];
    }

    /**
     * Prepare alamat data array
     */
    private function prepareAlamatData(int $idPendaftar): array
    {
        return [
            'id_pendaftar' => $idPendaftar,
            'nomor_kk' => trim($this->request->getPost('nomor_kk') ?? ''),
            'nama_kepala_keluarga' => trim($this->request->getPost('nama_kepala_keluarga') ?? ''),  // Sprint 2 NEW
            'jenis_tempat_tinggal' => $this->request->getPost('jenis_tempat_tinggal'),
            'alamat' => trim($this->request->getPost('alamat') ?? ''),
            'rt_rw' => trim($this->request->getPost('rt_rw') ?? ''),  // Sprint 2 NEW
            'desa' => trim($this->request->getPost('desa') ?? ''),
            'kecamatan' => trim($this->request->getPost('kecamatan') ?? ''),
            'kabupaten' => trim($this->request->getPost('kabupaten') ?? ''),
            'provinsi' => trim($this->request->getPost('provinsi') ?? ''),
            'kode_pos' => trim($this->request->getPost('kode_pos') ?? ''),
            'tinggal_bersama' => $this->request->getPost('tinggal_bersama'),  // Sprint 2 NEW
            'jarak_ke_sekolah' => $this->request->getPost('jarak_ke_sekolah'),
            'waktu_tempuh' => $this->request->getPost('waktu_tempuh'),
            'transportasi' => $this->request->getPost('transportasi'),
            'email' => trim($this->request->getPost('email') ?? ''),
            'media_sosial' => trim($this->request->getPost('media_sosial') ?? ''),
        ];
    }

    /**
     * Prepare data ayah array
     */
    private function prepareAyahData(int $idPendaftar): array
    {
        return [
            'id_pendaftar' => $idPendaftar,
            'nama_ayah' => trim($this->request->getPost('nama_ayah') ?? ''),
            'nik_ayah' => trim($this->request->getPost('nik_ayah') ?? ''),
            'tempat_lahir_ayah' => trim($this->request->getPost('tempat_lahir_ayah') ?? ''),
            'tanggal_lahir_ayah' => $this->request->getPost('tanggal_lahir_ayah') ?: null,
            'status_ayah' => $this->request->getPost('status_ayah'),
            'pendidikan_ayah' => $this->request->getPost('pendidikan_ayah'),
            'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
            'penghasilan_ayah' => $this->request->getPost('penghasilan_ayah'),
            'hp_ayah' => trim($this->request->getPost('hp_ayah') ?? ''),
            'alamat_ayah' => trim($this->request->getPost('alamat_ayah') ?? ''),
        ];
    }

    /**
     * Prepare data ibu array
     */
    private function prepareIbuData(int $idPendaftar): array
    {
        return [
            'id_pendaftar' => $idPendaftar,
            'nama_ibu' => trim($this->request->getPost('nama_ibu') ?? ''),
            'nik_ibu' => trim($this->request->getPost('nik_ibu') ?? ''),
            'tempat_lahir_ibu' => trim($this->request->getPost('tempat_lahir_ibu') ?? ''),
            'tanggal_lahir_ibu' => $this->request->getPost('tanggal_lahir_ibu') ?: null,
            'status_ibu' => $this->request->getPost('status_ibu'),
            'pendidikan_ibu' => $this->request->getPost('pendidikan_ibu'),
            'pekerjaan_ibu' => $this->request->getPost('pekerjaan_ibu'),
            'penghasilan_ibu' => $this->request->getPost('penghasilan_ibu'),
            'hp_ibu' => trim($this->request->getPost('hp_ibu') ?? ''),
            'alamat_ibu' => trim($this->request->getPost('alamat_ibu') ?? ''),
        ];
    }

    /**
     * Prepare data wali array
     */
    private function prepareWaliData(int $idPendaftar): array
    {
        return [
            'id_pendaftar' => $idPendaftar,
            'nama_wali' => trim($this->request->getPost('nama_wali')),
            'nik_wali' => trim($this->request->getPost('nik_wali') ?? ''),
            'tempat_lahir_wali' => trim($this->request->getPost('tempat_lahir_wali') ?? ''),  // Sprint 2 NEW
            'tanggal_lahir_wali' => $this->request->getPost('tanggal_lahir_wali') ?: null,  // Sprint 2 NEW
            'pendidikan_wali' => $this->request->getPost('pendidikan_wali'),
            'pekerjaan_wali' => $this->request->getPost('pekerjaan_wali'),
            'penghasilan_wali' => $this->request->getPost('penghasilan_wali'),
            'hp_wali' => trim($this->request->getPost('hp_wali') ?? ''),
        ];
    }

    /**
     * Prepare bansos data array
     */
    private function prepareBansosData(int $idPendaftar): array
    {
        return [
            'id_pendaftar' => $idPendaftar,
            'no_kks' => trim($this->request->getPost('no_kks') ?? ''),
            'no_pkh' => trim($this->request->getPost('no_pkh') ?? ''),
            'no_kip' => trim($this->request->getPost('no_kip') ?? ''),
        ];
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
            'year' => 2026,
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
        $dompdf->stream($filename, [
            'Attachment' => true,
            'compress' => true,
        ]);
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
        $dompdf->stream($filename, [
            'Attachment' => true,
            'compress' => true,
        ]);
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
            $logoPath = FCPATH . 'assets/images/logo/01.png';

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
            // =====================================================
            // Section 1: Data Diri (pendaftar table)
            // =====================================================
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'nisn' => 'required|numeric|exact_length[10]',
            'nik' => 'required|numeric|exact_length[16]',
            'tempat_lahir' => 'permit_empty|max_length[100]',
            'tanggal_lahir' => 'permit_empty|valid_date[Y-m-d]',
            'status_keluarga' => 'permit_empty|in_list[Anak Kandung,Anak Tiri,Anak Angkat]',
            'anak_ke' => 'permit_empty|numeric|greater_than[0]|less_than[20]',
            'jumlah_saudara' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than[20]',
            'hobi' => 'required|in_list[Olah Raga,Kesenian,Membaca,Menulis,Jalan-jalan,Lainnya]',
            'cita_cita' => 'required|in_list[PNS,TNI/Polri,Guru/Dosen,Dokter,Politikus,Wiraswasta,Seniman/Artis,Ilmuwan,Agamawan,Lainnya]',
            'kebutuhan_disabilitas' => 'permit_empty|max_length[255]',
            'imunisasi' => 'permit_empty|in_list[Lengkap,Tidak Lengkap,Tidak Tahu]',
            'no_hp' => 'permit_empty|max_length[20]',
            'ukuran_baju' => 'permit_empty|in_list[XS,S,M,L,XL,XXL,XXXL]',
            'prestasi' => 'permit_empty|max_length[500]',

            // =====================================================
            // Section 2: Data Alamat (alamat_pendaftar table)
            // =====================================================
            'nomor_kk' => 'permit_empty|numeric|exact_length[16]',
            'jenis_tempat_tinggal' => 'permit_empty|in_list[Milik Sendiri,Rumah Orang Tua,Rumah Saudara,Rumah Dinas,Sewa/Kontrak,Lainnya]',
            'alamat' => 'permit_empty|max_length[500]',
            'desa' => 'permit_empty|max_length[100]',
            'kecamatan' => 'permit_empty|max_length[100]',
            'kabupaten' => 'permit_empty|max_length[100]',
            'provinsi' => 'permit_empty|max_length[100]',
            'kode_pos' => 'permit_empty|numeric|exact_length[5]',
            'jarak_ke_sekolah' => 'permit_empty|in_list[< 1 km,1-5 km,5-10 km,10-20 km,> 20 km]',
            'waktu_tempuh' => 'permit_empty|in_list[< 15 menit,15-30 menit,30-60 menit,> 60 menit]',
            'transportasi' => 'permit_empty|in_list[Jalan Kaki,Sepeda,Motor,Mobil,Angkutan Umum,Ojek Online,Lainnya]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'media_sosial' => 'permit_empty|max_length[255]',

            // =====================================================
            // Section 3: Data Ayah (data_ayah table)
            // =====================================================
            'nama_ayah' => 'permit_empty|max_length[150]',
            'nik_ayah' => 'permit_empty|numeric|exact_length[16]',
            'tempat_lahir_ayah' => 'permit_empty|max_length[100]',
            'tanggal_lahir_ayah' => 'permit_empty|valid_date[Y-m-d]',
            'status_ayah' => 'permit_empty|in_list[Masih Hidup,Sudah Meninggal,Tidak Diketahui]',
            'pendidikan_ayah' => 'permit_empty|in_list[Tidak Sekolah,SD/MI,SMP/MTs,SMA/MA/SMK,D1,D2,D3,D4/S1,S2,S3]',
            'pekerjaan_ayah' => 'required|in_list[Tidak Bekerja,Pensiun,PNS,TNI/Polri,Guru/Dosen,Pegawai Swasta,Wiraswasta,Pengacara/Jaksa/Hakim/Notaris,Seniman/Pelukis/Artis/Sejenis,Dokter/Bidan/Perawat,Pilot/Pramugara,Pedagang,Petani/Peternak,Nelayan,Buruh (Tani/Pabrik/Bangunan),Sopir/Masinis/Kondektur,Politikus,Lainnya]',
            'penghasilan_ayah' => 'required|in_list[Dibawah 800.000,800.001-1.200.000,1.200.001-1.800.000,1.800.001-2.500.000,2.500.001-3.500.000,3.500.001-4.800.000,4.800.001-6.500.000,6.500.001-10.000.000,10.000.001-20.000.000,Diatas 20.000.000]',
            'hp_ayah' => 'required|max_length[20]',
            'alamat_ayah' => 'permit_empty|max_length[500]',

            // =====================================================
            // Section 4: Data Ibu (data_ibu table)
            // =====================================================
            'nama_ibu' => 'permit_empty|max_length[150]',
            'nik_ibu' => 'permit_empty|numeric|exact_length[16]',
            'tempat_lahir_ibu' => 'permit_empty|max_length[100]',
            'tanggal_lahir_ibu' => 'permit_empty|valid_date[Y-m-d]',
            'status_ibu' => 'permit_empty|in_list[Masih Hidup,Sudah Meninggal,Tidak Diketahui]',
            'pendidikan_ibu' => 'permit_empty|in_list[Tidak Sekolah,SD/MI,SMP/MTs,SMA/MA/SMK,D1,D2,D3,D4/S1,S2,S3]',
            'pekerjaan_ibu' => 'required|in_list[Tidak Bekerja,Pensiun,PNS,TNI/Polri,Guru/Dosen,Pegawai Swasta,Wiraswasta,Pengacara/Jaksa/Hakim/Notaris,Seniman/Pelukis/Artis/Sejenis,Dokter/Bidan/Perawat,Pilot/Pramugara,Pedagang,Petani/Peternak,Nelayan,Buruh (Tani/Pabrik/Bangunan),Sopir/Masinis/Kondektur,Politikus,Lainnya]',
            'penghasilan_ibu' => 'required|in_list[Dibawah 800.000,800.001-1.200.000,1.200.001-1.800.000,1.800.001-2.500.000,2.500.001-3.500.000,3.500.001-4.800.000,4.800.001-6.500.000,6.500.001-10.000.000,10.000.001-20.000.000,Diatas 20.000.000]',
            'hp_ibu' => 'required|max_length[20]',
            'alamat_ibu' => 'permit_empty|max_length[500]',

            // =====================================================
            // Section 5: Data Wali (data_wali table - Optional)
            // =====================================================
            'nama_wali' => 'permit_empty|max_length[150]',
            'nik_wali' => 'permit_empty|numeric|exact_length[16]',
            'tahun_lahir_wali' => 'permit_empty|numeric|exact_length[4]',
            'pendidikan_wali' => 'permit_empty|in_list[Tidak Sekolah,SD/MI,SMP/MTs,SMA/MA/SMK,D1,D2,D3,D4/S1,S2,S3]',
            'pekerjaan_wali' => 'permit_empty|in_list[Tidak Bekerja,Pensiun,PNS,TNI/Polri,Guru/Dosen,Pegawai Swasta,Wiraswasta,Pengacara/Jaksa/Hakim/Notaris,Seniman/Pelukis/Artis/Sejenis,Dokter/Bidan/Perawat,Pilot/Pramugara,Pedagang,Petani/Peternak,Nelayan,Buruh (Tani/Pabrik/Bangunan),Sopir/Masinis/Kondektur,Politikus,Lainnya]',
            'penghasilan_wali' => 'permit_empty|in_list[Dibawah 800.000,800.001-1.200.000,1.200.001-1.800.000,1.800.001-2.500.000,2.500.001-3.500.000,3.500.001-4.800.000,4.800.001-6.500.000,6.500.001-10.000.000,10.000.001-20.000.000,Diatas 20.000.000]',
            'hp_wali' => 'permit_empty|max_length[20]',

            // =====================================================
            // Section 6: Data Bansos (bansos_pendaftar table - Optional)
            // =====================================================
            'no_kks' => 'permit_empty|max_length[50]',
            'no_pkh' => 'permit_empty|max_length[50]',
            'no_kip' => 'permit_empty|max_length[50]',

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
            // Data Diri
            'nama_lengkap' => [
                'required' => 'Nama lengkap wajib diisi.',
                'min_length' => 'Nama lengkap minimal 3 karakter.',
                'max_length' => 'Nama lengkap maksimal 150 karakter.',
            ],
            'jenis_kelamin' => [
                'required' => 'Jenis kelamin wajib dipilih.',
                'in_list' => 'Jenis kelamin tidak valid.',
            ],
            'nisn' => [
                'required' => 'NISN wajib diisi.',
                'numeric' => 'NISN harus berupa angka.',
                'exact_length' => 'NISN harus 10 digit.',
            ],
            'nik' => [
                'required' => 'NIK wajib diisi.',
                'numeric' => 'NIK harus berupa angka.',
                'exact_length' => 'NIK harus 16 digit.',
            ],
            'tanggal_lahir' => [
                'valid_date' => 'Format tanggal lahir tidak valid.',
            ],
            'anak_ke' => [
                'numeric' => 'Anak ke harus berupa angka.',
                'greater_than' => 'Anak ke harus lebih dari 0.',
            ],
            'jumlah_saudara' => [
                'numeric' => 'Jumlah saudara harus berupa angka.',
            ],

            // Data Alamat
            'nomor_kk' => [
                'numeric' => 'Nomor KK harus berupa angka.',
                'exact_length' => 'Nomor KK harus 16 digit.',
            ],
            'kode_pos' => [
                'numeric' => 'Kode pos harus berupa angka.',
                'exact_length' => 'Kode pos harus 5 digit.',
            ],
            'email' => [
                'valid_email' => 'Format email tidak valid.',
            ],

            // Data Ayah
            'nik_ayah' => [
                'numeric' => 'NIK Ayah harus berupa angka.',
                'exact_length' => 'NIK Ayah harus 16 digit.',
            ],
            'tanggal_lahir_ayah' => [
                'valid_date' => 'Format tanggal lahir ayah tidak valid.',
            ],
            'hp_ayah' => [
                'required' => 'No. HP Ayah wajib diisi.',
                'max_length' => 'No. HP Ayah maksimal 20 karakter.',
            ],

            // Data Ibu
            'nik_ibu' => [
                'numeric' => 'NIK Ibu harus berupa angka.',
                'exact_length' => 'NIK Ibu harus 16 digit.',
            ],
            'tanggal_lahir_ibu' => [
                'valid_date' => 'Format tanggal lahir ibu tidak valid.',
            ],
            'hp_ibu' => [
                'required' => 'No. HP Ibu wajib diisi.',
                'max_length' => 'No. HP Ibu maksimal 20 karakter.',
            ],

            // Data Wali
            'nik_wali' => [
                'numeric' => 'NIK Wali harus berupa angka.',
                'exact_length' => 'NIK Wali harus 16 digit.',
            ],
            'tahun_lahir_wali' => [
                'numeric' => 'Tahun lahir wali harus berupa angka.',
                'exact_length' => 'Tahun lahir wali harus 4 digit.',
            ],

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
}
