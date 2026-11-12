<?php

namespace App\Controllers;

use App\Libraries\FormPendaftaran;
use App\Libraries\PerbaruiPendaftar;
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
            'year' => periode_tahun_mulai(),
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

        // Data periode yang sudah diarsipkan tidak dapat diubah lagi
        $idPeriodeAktif = periode_aktif_id();
        if ($idPeriodeAktif && !empty($pendaftar['id_periode']) && (int) $pendaftar['id_periode'] !== $idPeriodeAktif) {
            return redirect()->back()->withInput()
                ->with('error', 'Data pendaftaran Anda berasal dari periode Tahun Ajaran ' . tahun_ajaran_pendaftar($pendaftar) . ' yang sudah diarsipkan dan tidak dapat diubah lagi.');
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
            'year' => periode_tahun_mulai(),
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

        $pendaftarLama = $this->pendaftarModel->find($idPendaftar);
        if (!$pendaftarLama) {
            session()->remove(['update_pendaftar_id', 'update_nomor_pendaftaran']);
            return redirect()->to(base_url('update-data'))->with('error', 'Data pendaftar tidak ditemukan.');
        }

        // Validasi (aturan Ayah/Ibu/Wali dinamis; KK wajib bila belum pernah diunggah)
        $perbarui = new PerbaruiPendaftar($this->request);
        [$rules, $messages] = $perbarui->aturan($pendaftarLama);

        if (!$this->validate($rules, $messages)) {
            $errors = $this->validator->getErrors();
            $this->logUpdate('warning', 'Validasi gagal', [
                'id_pendaftar' => $idPendaftar,
                'errors' => $errors,
            ]);
            return redirect()->back()->withInput()
                ->with('error', 'Data tidak valid! Mohon periksa kembali formulir Anda.')
                ->with('validation_errors', $errors);
        }

        $duplikat = $perbarui->cekDuplikat($pendaftarLama);
        if ($duplikat !== []) {
            return redirect()->back()->withInput()
                ->with('error', 'NISN/NIK sudah dipakai pendaftar lain pada periode ini.')
                ->with('validation_errors', $duplikat);
        }

        try {
            $pendaftarData = $perbarui->simpan((int) $idPendaftar, $pendaftarLama);
        } catch (\Exception $e) {
            $this->logUpdate('error', 'Error saat update', [
                'id_pendaftar' => $idPendaftar,
                'error_message' => $e->getMessage(),
            ]);

            $pesan = $e instanceof DatabaseException
                ? 'Terjadi kesalahan database. Silakan coba lagi.'
                : 'Terjadi kesalahan: ' . $e->getMessage();

            return redirect()->back()->withInput()->with('error', $pesan);
        }

        $this->logUpdate('info', 'UPDATE DATA BERHASIL', [
            'nomor_pendaftaran' => $nomorPendaftaran,
            'nama' => $pendaftarData['nama_lengkap'],
        ]);

        session()->remove(['update_pendaftar_id', 'update_nomor_pendaftaran']);

        return redirect()->to(base_url('update-data/sukses/' . $nomorPendaftaran))
            ->with('success', 'Data berhasil diperbarui!');
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
            'year' => periode_tahun_mulai(),
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


}
