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

class UpdateDataPendaftar extends BaseController
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
     * Halaman verifikasi - form untuk masukkan nomor peserta dan NISN
     */
    public function index()
    {
        $data = [
            'title' => 'Update Data Pendaftar - Pesantren Persatuan Islam 31 Banjaran',
            'year' => 2026,
        ];

        return view('update_data/verifikasi', $data);
    }

    /**
     * Proses verifikasi nomor peserta dan NISN
     */
    public function verifikasi()
    {
        $nomorPendaftaran = trim($this->request->getPost('nomor_pendaftaran'));
        $nisn = trim($this->request->getPost('nisn'));

        // Validasi input
        if (empty($nomorPendaftaran) || empty($nisn)) {
            return redirect()->back()->withInput()
                ->with('error', 'Nomor Pendaftaran dan NISN wajib diisi.');
        }

        // Cari pendaftar dengan nomor pendaftaran dan NISN yang cocok
        $pendaftar = $this->pendaftarModel
            ->where('nomor_pendaftaran', $nomorPendaftaran)
            ->where('nisn', $nisn)
            ->first();

        if (!$pendaftar) {
            $this->logUpdate('warning', 'Verifikasi gagal - data tidak ditemukan', [
                'nomor_pendaftaran' => $nomorPendaftaran,
                'nisn' => $nisn,
                'ip_address' => $this->request->getIPAddress(),
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'Data tidak ditemukan. Pastikan Nomor Pendaftaran dan NISN yang Anda masukkan sudah benar.');
        }

        // Simpan ke session untuk digunakan di halaman edit
        session()->set('update_pendaftar_id', $pendaftar['id_pendaftar']);
        session()->set('update_nomor_pendaftaran', $pendaftar['nomor_pendaftaran']);

        $this->logUpdate('info', 'Verifikasi berhasil', [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nama' => $pendaftar['nama_lengkap'],
            'ip_address' => $this->request->getIPAddress(),
        ]);

        // Redirect ke halaman edit
        return redirect()->to(base_url('update-data/edit'));
    }

    /**
     * Halaman edit data pendaftar
     */
    public function edit()
    {
        // Cek apakah sudah verifikasi
        $idPendaftar = session()->get('update_pendaftar_id');
        $nomorPendaftaran = session()->get('update_nomor_pendaftaran');

        if (!$idPendaftar || !$nomorPendaftaran) {
            return redirect()->to(base_url('update-data'))
                ->with('error', 'Silakan verifikasi terlebih dahulu.');
        }

        // Ambil data pendaftar lengkap
        $pendaftar = $this->pendaftarModel->find($idPendaftar);

        if (!$pendaftar) {
            session()->remove(['update_pendaftar_id', 'update_nomor_pendaftaran']);
            return redirect()->to(base_url('update-data'))
                ->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Ambil data terkait
        $alamat = $this->alamatModel->where('id_pendaftar', $idPendaftar)->first();
        $ayah = $this->ayahModel->where('id_pendaftar', $idPendaftar)->first();
        $ibu = $this->ibuModel->where('id_pendaftar', $idPendaftar)->first();
        $wali = $this->waliModel->where('id_pendaftar', $idPendaftar)->first();
        $bansos = $this->bansosModel->where('id_pendaftar', $idPendaftar)->first();
        $sekolah = $this->sekolahModel->where('id_pendaftar', $idPendaftar)->first();

        $jalurLabel = $pendaftar['jalur_pendaftaran'] === 'TSANAWIYYAH' ? 'Tsanawiyyah' : "Mu'allimin";

        $data = [
            'title' => 'Edit Data Pendaftar - Pesantren Persatuan Islam 31 Banjaran',
            'jalur' => $pendaftar['jalur_pendaftaran'],
            'jalur_label' => $jalurLabel,
            'year' => 2026,
            'pendaftar' => $pendaftar,
            'alamat' => $alamat,
            'ayah' => $ayah,
            'ibu' => $ibu,
            'wali' => $wali,
            'bansos' => $bansos,
            'sekolah' => $sekolah,
        ];

        return view('update_data/form_edit', $data);
    }

    /**
     * Proses update data pendaftar
     */
    public function update()
    {
        // Cek apakah sudah verifikasi
        $idPendaftar = session()->get('update_pendaftar_id');
        $nomorPendaftaran = session()->get('update_nomor_pendaftaran');

        if (!$idPendaftar || !$nomorPendaftaran) {
            return redirect()->to(base_url('update-data'))
                ->with('error', 'Silakan verifikasi terlebih dahulu.');
        }

        // Log update attempt
        $this->logUpdate('info', 'Update data dimulai', [
            'id_pendaftar' => $idPendaftar,
            'nomor_pendaftaran' => $nomorPendaftaran,
            'ip_address' => $this->request->getIPAddress(),
        ]);

        // Validasi
        $validationRules = $this->getValidationRules();
        $validationMessages = $this->getValidationMessages();

        if (!$this->validate($validationRules, $validationMessages)) {
            $errors = $this->validator->getErrors();
            $this->logUpdate('warning', 'Validasi gagal', [
                'id_pendaftar' => $idPendaftar,
                'errors' => $errors,
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Data tidak valid! Mohon periksa kembali formulir Anda.')
                ->with('validation_errors', $errors);
        }

        // Start database transaction
        $this->db->transStart();

        try {
            // =====================================================
            // UPDATE TABLE 1: pendaftar (Data Diri)
            // =====================================================
            $pendaftarData = $this->preparePendaftarData();

            if (!$this->pendaftarModel->update($idPendaftar, $pendaftarData)) {
                throw new \Exception('Gagal memperbarui data pendaftar: ' . implode(', ', $this->pendaftarModel->errors()));
            }

            $this->logUpdate('info', 'Data pendaftar berhasil diperbarui', [
                'id_pendaftar' => $idPendaftar,
            ]);

            // =====================================================
            // UPDATE TABLE 2: alamat_pendaftar (Data Alamat)
            // =====================================================
            $alamatData = $this->prepareAlamatData();
            $existingAlamat = $this->alamatModel->where('id_pendaftar', $idPendaftar)->first();

            if ($existingAlamat) {
                if (!$this->alamatModel->update($existingAlamat['id_alamat'], $alamatData)) {
                    throw new \Exception('Gagal memperbarui data alamat.');
                }
            } else {
                $alamatData['id_pendaftar'] = $idPendaftar;
                if (!$this->alamatModel->insert($alamatData)) {
                    throw new \Exception('Gagal menyimpan data alamat.');
                }
            }

            // =====================================================
            // UPDATE TABLE 3: data_ayah
            // =====================================================
            $ayahData = $this->prepareAyahData();
            $existingAyah = $this->ayahModel->where('id_pendaftar', $idPendaftar)->first();

            if ($existingAyah) {
                if (!$this->ayahModel->update($existingAyah['id_ayah'], $ayahData)) {
                    throw new \Exception('Gagal memperbarui data ayah.');
                }
            } else {
                $ayahData['id_pendaftar'] = $idPendaftar;
                if (!$this->ayahModel->insert($ayahData)) {
                    throw new \Exception('Gagal menyimpan data ayah.');
                }
            }

            // =====================================================
            // UPDATE TABLE 4: data_ibu
            // =====================================================
            $ibuData = $this->prepareIbuData();
            $existingIbu = $this->ibuModel->where('id_pendaftar', $idPendaftar)->first();

            if ($existingIbu) {
                if (!$this->ibuModel->update($existingIbu['id_ibu'], $ibuData)) {
                    throw new \Exception('Gagal memperbarui data ibu.');
                }
            } else {
                $ibuData['id_pendaftar'] = $idPendaftar;
                if (!$this->ibuModel->insert($ibuData)) {
                    throw new \Exception('Gagal menyimpan data ibu.');
                }
            }

            // =====================================================
            // UPDATE TABLE 5: data_wali (Optional)
            // =====================================================
            $namaWali = trim($this->request->getPost('nama_wali') ?? '');
            $existingWali = $this->waliModel->where('id_pendaftar', $idPendaftar)->first();

            if (!empty($namaWali)) {
                $waliData = $this->prepareWaliData();
                if ($existingWali) {
                    if (!$this->waliModel->update($existingWali['id_wali'], $waliData)) {
                        throw new \Exception('Gagal memperbarui data wali.');
                    }
                } else {
                    $waliData['id_pendaftar'] = $idPendaftar;
                    if (!$this->waliModel->insert($waliData)) {
                        throw new \Exception('Gagal menyimpan data wali.');
                    }
                }
            } elseif ($existingWali) {
                // Hapus data wali jika nama wali dikosongkan
                $this->waliModel->delete($existingWali['id_wali']);
            }

            // =====================================================
            // UPDATE TABLE 6: bansos_pendaftar (Optional)
            // =====================================================
            $noKks = trim($this->request->getPost('no_kks') ?? '');
            $noPkh = trim($this->request->getPost('no_pkh') ?? '');
            $noKip = trim($this->request->getPost('no_kip') ?? '');
            $existingBansos = $this->bansosModel->where('id_pendaftar', $idPendaftar)->first();

            if (!empty($noKks) || !empty($noPkh) || !empty($noKip)) {
                $bansosData = $this->prepareBansosData();
                if ($existingBansos) {
                    if (!$this->bansosModel->update($existingBansos['id_bansos'], $bansosData)) {
                        throw new \Exception('Gagal memperbarui data bansos.');
                    }
                } else {
                    $bansosData['id_pendaftar'] = $idPendaftar;
                    if (!$this->bansosModel->insert($bansosData)) {
                        throw new \Exception('Gagal menyimpan data bansos.');
                    }
                }
            } elseif ($existingBansos) {
                // Hapus data bansos jika semua field dikosongkan
                $this->bansosModel->delete($existingBansos['id_bansos']);
            }

            // =====================================================
            // UPDATE TABLE 7: asal_sekolah
            // =====================================================
            $sekolahData = $this->prepareSekolahData();
            $existingSekolah = $this->sekolahModel->where('id_pendaftar', $idPendaftar)->first();

            if ($existingSekolah) {
                if (!$this->sekolahModel->update($existingSekolah['id_sekolah'], $sekolahData)) {
                    throw new \Exception('Gagal memperbarui data sekolah.');
                }
            } else {
                $sekolahData['id_pendaftar'] = $idPendaftar;
                if (!$this->sekolahModel->insert($sekolahData)) {
                    throw new \Exception('Gagal menyimpan data sekolah.');
                }
            }

            // =====================================================
            // Commit Transaction
            // =====================================================
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal. Silakan coba lagi.');
            }

            // Log successful update
            $this->logUpdate('info', 'UPDATE DATA BERHASIL', [
                'nomor_pendaftaran' => $nomorPendaftaran,
                'nama' => $pendaftarData['nama_lengkap'],
            ]);

            // Hapus session
            session()->remove(['update_pendaftar_id', 'update_nomor_pendaftaran']);

            // Redirect ke halaman sukses
            return redirect()->to(base_url('update-data/sukses/' . $nomorPendaftaran))
                ->with('success', 'Data berhasil diperbarui!');

        } catch (DatabaseException $e) {
            $this->db->transRollback();

            $this->logUpdate('error', 'Database error saat update', [
                'id_pendaftar' => $idPendaftar,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan database. Silakan coba lagi.');

        } catch (\Exception $e) {
            $this->db->transRollback();

            $this->logUpdate('error', 'Error saat update', [
                'id_pendaftar' => $idPendaftar,
                'error_message' => $e->getMessage(),
            ]);

            return redirect()->back()->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Halaman sukses update
     */
    public function sukses($nomorPendaftaran = null)
    {
        if (!$nomorPendaftaran) {
            return redirect()->to(base_url('update-data'));
        }

        $pendaftar = $this->pendaftarModel->where('nomor_pendaftaran', $nomorPendaftaran)->first();

        if (!$pendaftar) {
            return redirect()->to(base_url('update-data'))
                ->with('error', 'Data pendaftaran tidak ditemukan.');
        }

        $idPendaftar = $pendaftar['id_pendaftar'];

        // Ambil semua data terkait
        $alamat = $this->alamatModel->where('id_pendaftar', $idPendaftar)->first();
        $ayah = $this->ayahModel->where('id_pendaftar', $idPendaftar)->first();
        $ibu = $this->ibuModel->where('id_pendaftar', $idPendaftar)->first();
        $wali = $this->waliModel->where('id_pendaftar', $idPendaftar)->first();
        $bansos = $this->bansosModel->where('id_pendaftar', $idPendaftar)->first();
        $sekolah = $this->sekolahModel->where('id_pendaftar', $idPendaftar)->first();

        $data = [
            'title' => 'Update Data Berhasil - Pesantren Persatuan Islam 31 Banjaran',
            'pendaftar' => $pendaftar,
            'alamat' => $alamat,
            'ayah' => $ayah,
            'ibu' => $ibu,
            'wali' => $wali,
            'bansos' => $bansos,
            'sekolah' => $sekolah,
            'year' => 2026,
        ];

        return view('update_data/sukses', $data);
    }

    /**
     * Batalkan proses update (logout dari session)
     */
    public function batal()
    {
        session()->remove(['update_pendaftar_id', 'update_nomor_pendaftaran']);
        return redirect()->to(base_url('update-data'))
            ->with('info', 'Proses update dibatalkan.');
    }

    /**
     * Prepare pendaftar data array
     */
    private function preparePendaftarData(): array
    {
        // Handle checkbox arrays
        $kebutuhanDisabilitasArray = $this->request->getPost('kebutuhan_disabilitas');
        $kebutuhanDisabilitas = is_array($kebutuhanDisabilitasArray) ? implode(', ', $kebutuhanDisabilitasArray) : '';

        $imunisasiArray = $this->request->getPost('imunisasi');
        $imunisasi = is_array($imunisasiArray) ? implode(', ', $imunisasiArray) : '';

        return [
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
            'pernah_paud' => $this->request->getPost('pernah_paud') ? 1 : 0,
            'pernah_tk' => $this->request->getPost('pernah_tk') ? 1 : 0,
            'kebutuhan_disabilitas' => $kebutuhanDisabilitas,
            'kebutuhan_khusus' => trim($this->request->getPost('kebutuhan_khusus') ?? ''),  // Sprint 2 NEW
            'imunisasi' => $imunisasi,
            'no_hp' => trim($this->request->getPost('no_hp') ?? ''),
            'ukuran_baju' => $this->request->getPost('ukuran_baju'),
            'prestasi' => trim($this->request->getPost('prestasi') ?? ''),
            'minat_bakat' => trim($this->request->getPost('minat_bakat') ?? ''),  // Sprint 2 NEW
        ];
    }

    /**
     * Prepare alamat data array
     */
    private function prepareAlamatData(): array
    {
        return [
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
    private function prepareAyahData(): array
    {
        return [
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
    private function prepareIbuData(): array
    {
        return [
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
    private function prepareWaliData(): array
    {
        return [
            'nama_wali' => trim($this->request->getPost('nama_wali')),
            'hubungan_wali' => $this->request->getPost('hubungan_wali'),  // NEW: Hubungan wali dengan santri
            'nik_wali' => trim($this->request->getPost('nik_wali') ?? ''),
            'tempat_lahir_wali' => trim($this->request->getPost('tempat_lahir_wali') ?? ''),  // Sprint 2 NEW
            'tanggal_lahir_wali' => $this->request->getPost('tanggal_lahir_wali') ?: null,  // Sprint 2 NEW (replaces tahun_lahir_wali)
            'pendidikan_wali' => $this->request->getPost('pendidikan_wali'),
            'pekerjaan_wali' => $this->request->getPost('pekerjaan_wali'),
            'penghasilan_wali' => $this->request->getPost('penghasilan_wali'),
            'hp_wali' => trim($this->request->getPost('hp_wali') ?? ''),
        ];
    }

    /**
     * Prepare bansos data array
     */
    private function prepareBansosData(): array
    {
        return [
            'no_kks' => trim($this->request->getPost('no_kks') ?? ''),
            'no_pkh' => trim($this->request->getPost('no_pkh') ?? ''),
            'no_kip' => trim($this->request->getPost('no_kip') ?? ''),
        ];
    }

    /**
     * Prepare asal sekolah data array
     */
    private function prepareSekolahData(): array
    {
        return [
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
     * Log update events
     */
    private function logUpdate(string $level, string $message, array $context = []): void
    {
        $logContext = array_merge([
            'timestamp' => date('Y-m-d H:i:s'),
            'module' => 'UPDATE_DATA',
        ], $context);

        log_message($level, '[PSB-UPDATE] ' . $message . ' | Context: ' . json_encode($logContext));
    }

    /**
     * Validation rules for update
     */
    private function getValidationRules(): array
    {
        return [
            // Data Diri
            'nama_lengkap' => 'required|min_length[3]|max_length[150]',
            'jenis_kelamin' => 'required|in_list[L,P]',
            'nisn' => 'required|numeric|exact_length[10]',
            'nik' => 'permit_empty|numeric|exact_length[16]',
            'tempat_lahir' => 'permit_empty|max_length[100]',
            'tanggal_lahir' => 'permit_empty|valid_date[Y-m-d]',
            'status_keluarga' => 'permit_empty|in_list[Anak Kandung,Anak Tiri,Anak Angkat]',
            'anak_ke' => 'permit_empty|numeric|greater_than[0]|less_than[20]',
            'jumlah_saudara' => 'permit_empty|numeric|greater_than_equal_to[0]|less_than[20]',
            'hobi' => 'required|max_length[255]',
            'cita_cita' => 'required|max_length[255]',
            'kebutuhan_khusus' => 'required|max_length[255]',
            'no_hp' => 'permit_empty|max_length[20]',
            'ukuran_baju' => 'permit_empty|in_list[XS,S,M,L,XL,XXL,XXXL]',
            'prestasi' => 'permit_empty|max_length[500]',

            // Data Alamat
            'nomor_kk' => 'permit_empty|numeric|exact_length[16]',
            'nama_kepala_keluarga' => 'required|max_length[150]',
            'jenis_tempat_tinggal' => 'permit_empty|in_list[Milik Sendiri,Rumah Orang Tua,Rumah Saudara,Rumah Dinas,Sewa/Kontrak,Lainnya]',
            'alamat' => 'permit_empty|max_length[500]',
            'rt_rw' => 'required|max_length[20]',
            'desa' => 'permit_empty|max_length[100]',
            'kecamatan' => 'permit_empty|max_length[100]',
            'kabupaten' => 'permit_empty|max_length[100]',
            'provinsi' => 'permit_empty|max_length[100]',
            'kode_pos' => 'permit_empty|numeric|exact_length[5]',
            'tinggal_bersama' => 'required|max_length[100]',
            'jarak_ke_sekolah' => 'permit_empty|in_list[< 1 km,1-5 km,5-10 km,10-20 km,> 20 km]',
            'waktu_tempuh' => 'permit_empty|in_list[< 15 menit,15-30 menit,30-60 menit,> 60 menit]',
            'transportasi' => 'permit_empty|in_list[Jalan Kaki,Sepeda,Motor,Mobil,Angkutan Umum,Ojek Online,Lainnya]',
            'email' => 'permit_empty|valid_email|max_length[100]',
            'media_sosial' => 'permit_empty|max_length[255]',

            // Data Ayah
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

            // Data Ibu
            'nama_ibu' => 'permit_empty|max_length[150]',
            'nik_ibu' => 'permit_empty|numeric|exact_length[16]',
            'tempat_lahir_ibu' => 'permit_empty|max_length[100]',
            'tanggal_lahir_ibu' => 'permit_empty|valid_date[Y-m-d]',
            'status_ibu' => 'permit_empty|in_list[Masih Hidup,Sudah Meninggal,Tidak Diketahui]',
            'pendidikan_ibu' => 'permit_empty|in_list[Tidak Sekolah,SD/MI,SMP/MTs,SMA/MA/SMK,D1,D2,D3,D4/S1,S2,S3]',
            'pekerjaan_ibu' => 'required|in_list[Tidak Bekerja,Ibu Rumah Tangga,Pensiun,PNS,TNI/Polri,Guru/Dosen,Pegawai Swasta,Wiraswasta,Pengacara/Jaksa/Hakim/Notaris,Seniman/Pelukis/Artis/Sejenis,Dokter/Bidan/Perawat,Pilot/Pramugara,Pedagang,Petani/Peternak,Nelayan,Buruh (Tani/Pabrik/Bangunan),Sopir/Masinis/Kondektur,Politikus,Lainnya]',
            'penghasilan_ibu' => 'required|in_list[Dibawah 800.000,800.001-1.200.000,1.200.001-1.800.000,1.800.001-2.500.000,2.500.001-3.500.000,3.500.001-4.800.000,4.800.001-6.500.000,6.500.001-10.000.000,10.000.001-20.000.000,Diatas 20.000.000]',
            'hp_ibu' => 'required|max_length[20]',
            'alamat_ibu' => 'permit_empty|max_length[500]',

            // Data Wali (Optional)
            'nama_wali' => 'permit_empty|max_length[150]',
            'hubungan_wali' => 'permit_empty|in_list[Kakek,Nenek,Paman,Bibi,Kakak,Ayah/Ibu Tiri,Lainnya]',
            'nik_wali' => 'permit_empty|numeric|exact_length[16]',
            'tempat_lahir_wali' => 'permit_empty|max_length[100]',
            'tanggal_lahir_wali' => 'permit_empty|valid_date[Y-m-d]',
            'pendidikan_wali' => 'permit_empty|in_list[Tidak Sekolah,SD/MI,SMP/MTs,SMA/MA/SMK,D1,D2,D3,D4/S1,S2,S3]',
            'pekerjaan_wali' => 'permit_empty|in_list[Tidak Bekerja,Pensiun,PNS,TNI/Polri,Guru/Dosen,Pegawai Swasta,Wiraswasta,Pengacara/Jaksa/Hakim/Notaris,Seniman/Pelukis/Artis/Sejenis,Dokter/Bidan/Perawat,Pilot/Pramugara,Pedagang,Petani/Peternak,Nelayan,Buruh (Tani/Pabrik/Bangunan),Sopir/Masinis/Kondektur,Politikus,Lainnya]',
            'penghasilan_wali' => 'permit_empty|in_list[Dibawah 800.000,800.001-1.200.000,1.200.001-1.800.000,1.800.001-2.500.000,2.500.001-3.500.000,3.500.001-4.800.000,4.800.001-6.500.000,6.500.001-10.000.000,10.000.001-20.000.000,Diatas 20.000.000]',
            'hp_wali' => 'permit_empty|max_length[20]',

            // Data Bansos (Optional)
            'no_kks' => 'permit_empty|max_length[50]',
            'no_pkh' => 'permit_empty|max_length[50]',
            'no_kip' => 'permit_empty|max_length[50]',

            // Data Asal Sekolah
            'nama_asal_sekolah' => 'required|max_length[200]',
            'jenjang_sekolah' => 'permit_empty|in_list[SD,MI,SMP,MTs,Paket A,Paket B,Lainnya]',
            'status_sekolah' => 'permit_empty|in_list[Negeri,Swasta]',
            'npsn' => 'permit_empty|numeric|exact_length[8]',
            'lokasi_sekolah' => 'permit_empty|max_length[255]',
            'alamat_sekolah' => 'permit_empty|max_length[500]',
            'tahun_lulus' => 'permit_empty|numeric|exact_length[4]',
            'rata_rata_rapor' => 'permit_empty|decimal|greater_than[0]|less_than_equal_to[100]',
            'nilai_tka' => 'permit_empty|decimal|greater_than[0]|less_than_equal_to[100]',
            'sekolah_md' => 'permit_empty|max_length[255]',
            'asal_jenjang' => 'permit_empty|max_length[200]',

            // Confirmation
            'confirm_data' => 'required',
        ];
    }

    /**
     * Validation error messages
     */
    private function getValidationMessages(): array
    {
        return [
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
                'numeric' => 'NIK harus berupa angka.',
                'exact_length' => 'NIK harus 16 digit.',
            ],
            'tanggal_lahir' => [
                'valid_date' => 'Format tanggal lahir tidak valid.',
            ],
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
            'hp_ayah' => [
                'required' => 'No. HP Ayah wajib diisi.',
            ],
            'hp_ibu' => [
                'required' => 'No. HP Ibu wajib diisi.',
            ],
            'nama_kepala_keluarga' => [
                'required' => 'Nama Kepala Keluarga wajib diisi.',
            ],
            'rt_rw' => [
                'required' => 'RT/RW wajib diisi.',
            ],
            'tinggal_bersama' => [
                'required' => 'Calon siswa tinggal bersama wajib dipilih.',
            ],
            'hobi' => [
                'required' => 'Hobi wajib dipilih.',
            ],
            'cita_cita' => [
                'required' => 'Cita-cita wajib dipilih.',
            ],
            'kebutuhan_khusus' => [
                'required' => 'Kebutuhan khusus wajib dipilih.',
            ],
            'nama_asal_sekolah' => [
                'required' => 'Nama asal sekolah wajib diisi.',
            ],
            'confirm_data' => [
                'required' => 'Anda harus menyetujui kebenaran data yang diisi.',
            ],
        ];
    }
}
