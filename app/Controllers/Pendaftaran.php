<?php

namespace App\Controllers;

use App\Models\PendaftarModel;
use App\Models\AlamatModel;

class Pendaftaran extends BaseController
{
    protected $pendaftarModel;
    protected $alamatModel;
    protected $session;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
        $this->alamatModel = new AlamatModel();
        $this->session = \Config\Services::session();
    }

    /**
     * Form pendaftaran Tsanawiyyah
     */
    public function tsanawiyyah()
    {
        // Set jalur pendaftaran in session
        $this->session->set('jalur_pendaftaran', 'TSANAWIYYAH');

        // Handle form submission
        if ($this->request->getMethod() === 'post') {
            return $this->processForm('TSANAWIYYAH');
        }

        // Get session data if exists
        $sessionData = [
            'data_diri' => $this->session->get('data_diri') ?? [],
            'data_alamat' => $this->session->get('data_alamat') ?? [],
        ];

        $data = [
            'title' => 'Pendaftaran Tsanawiyyah - Pesantren Persatuan Islam 31 Banjaran',
            'jalur' => 'TSANAWIYYAH',
            'jalur_label' => 'Tsanawiyyah',
            'year' => 2026,
            'session_data' => $sessionData,
            'validation' => $this->validator,
        ];

        return view('pendaftaran/form', $data);
    }

    /**
     * Form pendaftaran Muallimin
     */
    public function muallimin()
    {
        // Set jalur pendaftaran in session
        $this->session->set('jalur_pendaftaran', 'MUALLIMIN');

        // Handle form submission
        if ($this->request->getMethod() === 'post') {
            return $this->processForm('MUALLIMIN');
        }

        // Get session data if exists
        $sessionData = [
            'data_diri' => $this->session->get('data_diri') ?? [],
            'data_alamat' => $this->session->get('data_alamat') ?? [],
        ];

        $data = [
            'title' => 'Pendaftaran Mu\'allimin - Pesantren Persatuan Islam 31 Banjaran',
            'jalur' => 'MUALLIMIN',
            'jalur_label' => 'Mu\'allimin',
            'year' => 2026,
            'session_data' => $sessionData,
            'validation' => $this->validator,
        ];

        return view('pendaftaran/form', $data);
    }

    /**
     * Process form submission
     */
    private function processForm($jalur)
    {
        $step = $this->request->getPost('step');

        // Step 1: Data Diri
        if ($step === 'data_diri') {
            $rules = $this->getDataDiriValidationRules();

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            // Save to session
            $dataDiri = $this->request->getPost();
            unset($dataDiri['step']);
            $this->session->set('data_diri', $dataDiri);

            return redirect()->back()->with('success', 'Data diri berhasil disimpan. Silakan lanjutkan ke Data Tempat Tinggal.');
        }

        // Step 2: Data Alamat & Final Submit
        if ($step === 'data_alamat') {
            $rules = $this->getDataAlamatValidationRules();

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('validation', $this->validator);
            }

            // Save to session
            $dataAlamat = $this->request->getPost();
            unset($dataAlamat['step']);
            $this->session->set('data_alamat', $dataAlamat);

            // Process final submission
            return $this->savePendaftaran($jalur);
        }

        return redirect()->back()->with('error', 'Invalid step.');
    }

    /**
     * Save pendaftaran to database
     */
    private function savePendaftaran($jalur)
    {
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Get data from session
            $dataDiri = $this->session->get('data_diri');
            $dataAlamat = $this->session->get('data_alamat');

            // Prepare pendaftar data
            $pendaftarData = [
                'jalur_pendaftaran' => $jalur,
                'nisn' => $dataDiri['nisn'] ?? null,
                'nik' => $dataDiri['nik'] ?? null,
                'nama_lengkap' => $dataDiri['nama_lengkap'],
                'jenis_kelamin' => $dataDiri['jenis_kelamin'],
                'tempat_lahir' => $dataDiri['tempat_lahir'] ?? null,
                'tanggal_lahir' => $dataDiri['tanggal_lahir'] ?? null,
                'status_keluarga' => $dataDiri['status_keluarga'] ?? null,
                'anak_ke' => $dataDiri['anak_ke'] ?? null,
                'jumlah_saudara' => $dataDiri['jumlah_saudara'] ?? null,
                'hobi' => $dataDiri['hobi'] ?? null,
                'cita_cita' => $dataDiri['cita_cita'] ?? null,
                'pernah_paud' => isset($dataDiri['pernah_paud']) ? 1 : 0,
                'pernah_tk' => isset($dataDiri['pernah_tk']) ? 1 : 0,
                'kebutuhan_disabilitas' => $dataDiri['kebutuhan_disabilitas'] ?? null,
                'imunisasi' => $dataDiri['imunisasi'] ?? null,
                'no_hp' => $dataDiri['no_hp'] ?? null,
                'ukuran_baju' => $dataDiri['ukuran_baju'] ?? null,
                'prestasi' => $dataDiri['prestasi'] ?? null,
            ];

            // Insert pendaftar
            $idPendaftar = $this->pendaftarModel->insert($pendaftarData);

            if (!$idPendaftar) {
                throw new \Exception('Failed to insert pendaftar data');
            }

            // Get nomor pendaftaran
            $pendaftar = $this->pendaftarModel->find($idPendaftar);
            $nomorPendaftaran = $pendaftar['nomor_pendaftaran'];

            // Prepare alamat data
            $alamatData = [
                'id_pendaftar' => $idPendaftar,
                'nomor_kk' => $dataAlamat['nomor_kk'] ?? null,
                'jenis_tempat_tinggal' => $dataAlamat['jenis_tempat_tinggal'] ?? null,
                'alamat' => $dataAlamat['alamat'] ?? null,
                'desa' => $dataAlamat['desa'] ?? null,
                'kecamatan' => $dataAlamat['kecamatan'] ?? null,
                'kabupaten' => $dataAlamat['kabupaten'] ?? null,
                'provinsi' => $dataAlamat['provinsi'] ?? null,
                'kode_pos' => $dataAlamat['kode_pos'] ?? null,
                'jarak_ke_sekolah' => $dataAlamat['jarak_ke_sekolah'] ?? null,
                'waktu_tempuh' => $dataAlamat['waktu_tempuh'] ?? null,
                'transportasi' => $dataAlamat['transportasi'] ?? null,
                'email' => $dataAlamat['email'] ?? null,
                'media_sosial' => $dataAlamat['media_sosial'] ?? null,
            ];

            // Insert alamat
            if (!$this->alamatModel->insert($alamatData)) {
                throw new \Exception('Failed to insert alamat data');
            }

            $db->transComplete();

            // Clear session data
            $this->session->remove(['data_diri', 'data_alamat', 'jalur_pendaftaran']);

            // Redirect to success page
            return redirect()->to(base_url('pendaftaran/sukses/' . $nomorPendaftaran))
                ->with('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $nomorPendaftaran);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Pendaftaran error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan data. Silakan coba lagi.');
        }
    }

    /**
     * Success page
     */
    public function sukses($nomorPendaftaran = null)
    {
        if (!$nomorPendaftaran) {
            return redirect()->to(base_url('/'));
        }

        $pendaftar = $this->pendaftarModel->where('nomor_pendaftaran', $nomorPendaftaran)->first();

        if (!$pendaftar) {
            return redirect()->to(base_url('/'))->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $data = [
            'title' => 'Pendaftaran Berhasil - Pesantren Persatuan Islam 31 Banjaran',
            'pendaftar' => $pendaftar,
            'year' => 2026,
        ];

        return view('pendaftaran/sukses', $data);
    }

    /**
     * Data Diri Validation Rules
     */
    private function getDataDiriValidationRules()
    {
        return [
            'nisn' => 'permit_empty|numeric|max_length[20]',
            'nik' => 'permit_empty|numeric|max_length[20]',
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'tempat_lahir' => 'permit_empty|max_length[100]',
            'tanggal_lahir' => 'permit_empty|valid_date',
            'status_keluarga' => 'permit_empty|max_length[50]',
            'anak_ke' => 'permit_empty|numeric|max_length[2]',
            'jumlah_saudara' => 'permit_empty|numeric|max_length[2]',
            'hobi' => 'permit_empty|max_length[100]',
            'cita_cita' => 'permit_empty|max_length[100]',
            'kebutuhan_disabilitas' => 'permit_empty|max_length[100]',
            'imunisasi' => 'permit_empty|max_length[100]',
            'no_hp' => 'permit_empty|numeric|max_length[20]',
            'ukuran_baju' => 'permit_empty|max_length[10]',
            'prestasi' => 'permit_empty',
        ];
    }

    /**
     * Data Alamat Validation Rules
     */
    private function getDataAlamatValidationRules()
    {
        return [
            'nomor_kk' => 'permit_empty|numeric|max_length[20]',
            'jenis_tempat_tinggal' => 'permit_empty|max_length[50]',
            'alamat' => 'permit_empty',
            'desa' => 'permit_empty|max_length[100]',
            'kecamatan' => 'permit_empty|max_length[100]',
            'kabupaten' => 'permit_empty|max_length[100]',
            'provinsi' => 'permit_empty|max_length[100]',
            'kode_pos' => 'permit_empty|numeric|max_length[10]',
            'jarak_ke_sekolah' => 'permit_empty|max_length[50]',
            'waktu_tempuh' => 'permit_empty|max_length[50]',
            'transportasi' => 'permit_empty|max_length[100]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'media_sosial' => 'permit_empty|max_length[100]',
        ];
    }
}
