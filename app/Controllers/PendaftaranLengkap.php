<?php

namespace App\Controllers;

use App\Models\PendaftarModel;
use App\Models\AlamatModel;
use App\Models\AyahModel;
use App\Models\IbuModel;
use App\Models\WaliModel;
use App\Models\BansosModel;
use App\Models\SekolahModel;

class PendaftaranLengkap extends BaseController
{
    protected $pendaftarModel;
    protected $alamatModel;
    protected $ayahModel;
    protected $ibuModel;
    protected $waliModel;
    protected $bansosModel;
    protected $sekolahModel;

    public function __construct()
    {
        $this->pendaftarModel = new PendaftarModel();
        $this->alamatModel = new AlamatModel();
        $this->ayahModel = new AyahModel();
        $this->ibuModel = new IbuModel();
        $this->waliModel = new WaliModel();
        $this->bansosModel = new BansosModel();
        $this->sekolahModel = new SekolahModel();
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
            'year' => date('Y'),
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
            'year' => date('Y'),
        ];

        return view('pendaftaran/form_lengkap', $data);
    }

    /**
     * Submit complete form
     */
    public function submit($jalur = null)
    {
        if (!$jalur || !in_array(strtoupper($jalur), ['TSANAWIYYAH', 'MUALLIMIN'])) {
            return redirect()->to(base_url('/'))->with('error', 'Jalur pendaftaran tidak valid.');
        }

        $jalurUpper = strtoupper($jalur);

        // Validate
        if (!$this->validate($this->getValidationRules())) {
            return redirect()->back()->withInput()->with('error', 'Data tidak valid! ' . implode(', ', $this->validator->getErrors()));
        }

        // Start transaction
        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // 1. Insert Pendaftar (Data Diri)
            $pendaftarData = [
                'jalur_pendaftaran' => $jalurUpper,
                'nisn' => $this->request->getPost('nisn'),
                'nik' => $this->request->getPost('nik'),
                'nama_lengkap' => $this->request->getPost('nama_lengkap'),
                'jenis_kelamin' => $this->request->getPost('jenis_kelamin'),
                'tempat_lahir' => $this->request->getPost('tempat_lahir'),
                'tanggal_lahir' => $this->request->getPost('tanggal_lahir'),
                'status_keluarga' => $this->request->getPost('status_keluarga'),
                'anak_ke' => $this->request->getPost('anak_ke'),
                'jumlah_saudara' => $this->request->getPost('jumlah_saudara'),
                'hobi' => $this->request->getPost('hobi'),
                'cita_cita' => $this->request->getPost('cita_cita'),
                'pernah_paud' => $this->request->getPost('pernah_paud') ? 1 : 0,
                'pernah_tk' => $this->request->getPost('pernah_tk') ? 1 : 0,
                'kebutuhan_disabilitas' => $this->request->getPost('kebutuhan_disabilitas'),
                'imunisasi' => $this->request->getPost('imunisasi'),
                'no_hp' => $this->request->getPost('no_hp'),
                'ukuran_baju' => $this->request->getPost('ukuran_baju'),
                'prestasi' => $this->request->getPost('prestasi'),
            ];

            $idPendaftar = $this->pendaftarModel->insert($pendaftarData);

            if (!$idPendaftar) {
                throw new \Exception('Gagal menyimpan data pendaftar');
            }

            // Get nomor pendaftaran
            $pendaftar = $this->pendaftarModel->find($idPendaftar);
            $nomorPendaftaran = $pendaftar['nomor_pendaftaran'];

            // 2. Insert Alamat
            $alamatData = [
                'id_pendaftar' => $idPendaftar,
                'nomor_kk' => $this->request->getPost('nomor_kk'),
                'jenis_tempat_tinggal' => $this->request->getPost('jenis_tempat_tinggal'),
                'alamat' => $this->request->getPost('alamat'),
                'desa' => $this->request->getPost('desa'),
                'kecamatan' => $this->request->getPost('kecamatan'),
                'kabupaten' => $this->request->getPost('kabupaten'),
                'provinsi' => $this->request->getPost('provinsi'),
                'kode_pos' => $this->request->getPost('kode_pos'),
                'jarak_ke_sekolah' => $this->request->getPost('jarak_ke_sekolah'),
                'waktu_tempuh' => $this->request->getPost('waktu_tempuh'),
                'transportasi' => $this->request->getPost('transportasi'),
                'email' => $this->request->getPost('email'),
                'media_sosial' => $this->request->getPost('media_sosial'),
            ];

            if (!$this->alamatModel->insert($alamatData)) {
                throw new \Exception('Gagal menyimpan data alamat');
            }

            // 3. Insert Data Ayah
            $ayahData = [
                'id_pendaftar' => $idPendaftar,
                'nama_ayah' => $this->request->getPost('nama_ayah'),
                'nik_ayah' => $this->request->getPost('nik_ayah'),
                'tempat_lahir_ayah' => $this->request->getPost('tempat_lahir_ayah'),
                'tanggal_lahir_ayah' => $this->request->getPost('tanggal_lahir_ayah'),
                'status_ayah' => $this->request->getPost('status_ayah'),
                'pendidikan_ayah' => $this->request->getPost('pendidikan_ayah'),
                'pekerjaan_ayah' => $this->request->getPost('pekerjaan_ayah'),
                'penghasilan_ayah' => $this->request->getPost('penghasilan_ayah'),
                'hp_ayah' => $this->request->getPost('hp_ayah'),
                'alamat_ayah' => $this->request->getPost('alamat_ayah'),
            ];

            if (!$this->ayahModel->insert($ayahData)) {
                throw new \Exception('Gagal menyimpan data ayah');
            }

            // 4. Insert Data Ibu
            $ibuData = [
                'id_pendaftar' => $idPendaftar,
                'nama_ibu' => $this->request->getPost('nama_ibu'),
                'nik_ibu' => $this->request->getPost('nik_ibu'),
                'tempat_lahir_ibu' => $this->request->getPost('tempat_lahir_ibu'),
                'tanggal_lahir_ibu' => $this->request->getPost('tanggal_lahir_ibu'),
                'status_ibu' => $this->request->getPost('status_ibu'),
                'pendidikan_ibu' => $this->request->getPost('pendidikan_ibu'),
                'pekerjaan_ibu' => $this->request->getPost('pekerjaan_ibu'),
                'penghasilan_ibu' => $this->request->getPost('penghasilan_ibu'),
                'hp_ibu' => $this->request->getPost('hp_ibu'),
                'alamat_ibu' => $this->request->getPost('alamat_ibu'),
            ];

            if (!$this->ibuModel->insert($ibuData)) {
                throw new \Exception('Gagal menyimpan data ibu');
            }

            // 5. Insert Data Wali (if provided)
            if ($this->request->getPost('nama_wali')) {
                $waliData = [
                    'id_pendaftar' => $idPendaftar,
                    'nama_wali' => $this->request->getPost('nama_wali'),
                    'nik_wali' => $this->request->getPost('nik_wali'),
                    'tahun_lahir_wali' => $this->request->getPost('tahun_lahir_wali'),
                    'pendidikan_wali' => $this->request->getPost('pendidikan_wali'),
                    'pekerjaan_wali' => $this->request->getPost('pekerjaan_wali'),
                    'penghasilan_wali' => $this->request->getPost('penghasilan_wali'),
                    'hp_wali' => $this->request->getPost('hp_wali'),
                ];

                if (!$this->waliModel->insert($waliData)) {
                    throw new \Exception('Gagal menyimpan data wali');
                }
            }

            // 6. Insert Data Bansos (if provided)
            if ($this->request->getPost('no_kks') || $this->request->getPost('no_pkh') || $this->request->getPost('no_kip')) {
                $bansosData = [
                    'id_pendaftar' => $idPendaftar,
                    'no_kks' => $this->request->getPost('no_kks'),
                    'no_pkh' => $this->request->getPost('no_pkh'),
                    'no_kip' => $this->request->getPost('no_kip'),
                ];

                if (!$this->bansosModel->insert($bansosData)) {
                    throw new \Exception('Gagal menyimpan data bansos');
                }
            }

            // 7. Insert Data Asal Sekolah
            $sekolahData = [
                'id_pendaftar' => $idPendaftar,
                'nama_asal_sekolah' => $this->request->getPost('nama_asal_sekolah'),
                'jenjang_sekolah' => $this->request->getPost('jenjang_sekolah'),
                'status_sekolah' => $this->request->getPost('status_sekolah'),
                'npsn' => $this->request->getPost('npsn'),
                'lokasi_sekolah' => $this->request->getPost('lokasi_sekolah'),
                'asal_jenjang' => $this->request->getPost('asal_jenjang'),
            ];

            if (!$this->sekolahModel->insert($sekolahData)) {
                throw new \Exception('Gagal menyimpan data sekolah');
            }

            // Commit transaction
            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal');
            }

            // Redirect to success page
            return redirect()->to(base_url('pendaftaran/sukses/' . $nomorPendaftaran))
                ->with('success', 'Pendaftaran berhasil! Nomor pendaftaran Anda: ' . $nomorPendaftaran);

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Pendaftaran error: ' . $e->getMessage());
            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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

        // Get related data
        $alamat = $this->alamatModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();
        $sekolah = $this->sekolahModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();

        $data = [
            'title' => 'Pendaftaran Berhasil - Pesantren Persatuan Islam 31 Banjaran',
            'pendaftar' => $pendaftar,
            'alamat' => $alamat,
            'sekolah' => $sekolah,
            'year' => date('Y'),
        ];

        return view('pendaftaran/sukses_lengkap', $data);
    }

    /**
     * Download PDF registration card
     */
    public function downloadPdf($nomorPendaftaran = null)
    {
        if (!$nomorPendaftaran) {
            return redirect()->to(base_url('/'));
        }

        $pendaftar = $this->pendaftarModel->where('nomor_pendaftaran', $nomorPendaftaran)->first();

        if (!$pendaftar) {
            return redirect()->to(base_url('/'))->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        // Get related data
        $sekolah = $this->sekolahModel->where('id_pendaftar', $pendaftar['id_pendaftar'])->first();

        // Generate PDF (simple HTML to PDF)
        $data = [
            'pendaftar' => $pendaftar,
            'sekolah' => $sekolah,
        ];

        $html = view('pendaftaran/pdf_template', $data);

        // Set headers for PDF download
        $this->response->setHeader('Content-Type', 'text/html');
        $this->response->setHeader('Content-Disposition', 'attachment; filename="Kartu_Pendaftaran_' . $nomorPendaftaran . '.html"');

        return $this->response->setBody($html);
    }

    /**
     * Validation rules
     */
    private function getValidationRules()
    {
        return [
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'confirm_data' => 'required',
        ];
    }
}
