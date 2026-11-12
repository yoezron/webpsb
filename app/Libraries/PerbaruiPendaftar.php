<?php

namespace App\Libraries;

use App\Models\AlamatModel;
use App\Models\AyahModel;
use App\Models\BansosModel;
use App\Models\IbuModel;
use App\Models\PendaftarModel;
use App\Models\SekolahModel;
use App\Models\WaliModel;
use CodeIgniter\HTTP\IncomingRequest;

/**
 * Menyimpan perubahan data pendaftar (7 tabel) dalam satu transaksi.
 *
 * Dipakai oleh:
 *  - UpdateDataPendaftar (pendaftar memperbarui datanya sendiri)
 *  - Dashboard::update   (panitia/superadmin membantu memperbaiki data)
 */
class PerbaruiPendaftar
{
    private FormPendaftaran $form;

    public function __construct(private IncomingRequest $request)
    {
        $this->form = new FormPendaftaran($request);
    }

    /**
     * Aturan & pesan validasi untuk perubahan data.
     *
     * @return array{0: array, 1: array} [rules, messages]
     */
    public function aturan(array $pendaftarLama): array
    {
        $kkWajib = FormPendaftaran::pathKk($pendaftarLama['file_kk'] ?? null) === null;

        $rules = array_merge($this->form->rules(), $this->form->rulesFileKk($kkWajib), [
            'nama_asal_sekolah' => 'required|max_length[200]',
            'jenjang_sekolah'   => 'permit_empty|in_list[SD,MI,SMP,MTs,Paket A,Paket B,Lainnya]',
            'status_sekolah'    => 'permit_empty|in_list[Negeri,Swasta]',
            'npsn'              => 'permit_empty|numeric|exact_length[8]',
            'lokasi_sekolah'    => 'permit_empty|max_length[255]',
            'alamat_sekolah'    => 'permit_empty|max_length[500]',
            'tahun_lulus'       => 'permit_empty|numeric|exact_length[4]',
            'rata_rata_rapor'   => 'permit_empty|decimal|greater_than[0]|less_than_equal_to[100]',
            'nilai_tka'         => 'permit_empty|decimal|greater_than[0]|less_than_equal_to[100]',
            'sekolah_md'        => 'permit_empty|max_length[255]',
            'asal_jenjang'      => 'permit_empty|max_length[200]',
            'confirm_data'      => 'required',
        ]);

        $messages = array_merge($this->form->messages(), [
            'nama_asal_sekolah' => ['required' => 'Nama asal sekolah wajib diisi.'],
            'npsn'              => ['numeric' => 'NPSN harus berupa angka.', 'exact_length' => 'NPSN harus 8 digit.'],
            'confirm_data'      => ['required' => 'Anda harus menyetujui kebenaran data yang diisi.'],
        ]);

        return [$rules, $messages];
    }

    /**
     * NISN/NIK tidak boleh sama dengan pendaftar lain pada periode yang sama.
     */
    public function cekDuplikat(array $pendaftarLama): array
    {
        $idPeriode = !empty($pendaftarLama['id_periode']) ? (int) $pendaftarLama['id_periode'] : null;

        return $this->form->cekDuplikat($idPeriode, (int) $pendaftarLama['id_pendaftar']);
    }

    public function dataSekolah(): array
    {
        $p = fn (string $k) => trim((string) ($this->request->getPost($k) ?? ''));

        return [
            'nama_asal_sekolah' => $p('nama_asal_sekolah'),
            'jenjang_sekolah'   => $p('jenjang_sekolah') ?: null,
            'status_sekolah'    => $p('status_sekolah') ?: null,
            'npsn'              => $p('npsn'),
            'lokasi_sekolah'    => $p('lokasi_sekolah'),
            'alamat_sekolah'    => $p('alamat_sekolah'),
            'tahun_lulus'       => $p('tahun_lulus') ?: null,
            'rata_rata_rapor'   => $p('rata_rata_rapor') ?: null,
            'nilai_tka'         => $p('nilai_tka') ?: null,
            'sekolah_md'        => $p('sekolah_md'),
            'asal_jenjang'      => $p('asal_jenjang') ?: null,
        ];
    }

    /**
     * Simpan semua perubahan. Melempar \Exception bila gagal (transaksi dibatalkan,
     * file KK baru dihapus kembali).
     *
     * @return array data pendaftar yang disimpan
     */
    public function simpan(int $idPendaftar, array $pendaftarLama): array
    {
        $db         = \Config\Database::connect();
        $kkWajib    = FormPendaftaran::pathKk($pendaftarLama['file_kk'] ?? null) === null;
        $fileKkBaru = null;

        $db->transStart();

        try {
            $fileKkBaru = $this->form->simpanFileKk((string) $this->request->getPost('nisn'));
            if ($kkWajib && !$fileKkBaru) {
                throw new \Exception('File Kartu Keluarga gagal diunggah. Silakan pilih ulang file JPG/JPEG maksimal 1 MB.');
            }

            // 1. pendaftar
            $pendaftarModel = new PendaftarModel();
            $pendaftarData  = $this->form->dataPendaftar();
            if ($fileKkBaru) {
                $pendaftarData['file_kk'] = $fileKkBaru;
            }
            if (!$pendaftarModel->update($idPendaftar, $pendaftarData)) {
                throw new \Exception('Gagal memperbarui data pendaftar: ' . implode(', ', $pendaftarModel->errors()));
            }

            // 2-4. alamat, ayah, ibu (selalu ada)
            $this->upsert(new AlamatModel(), 'id_alamat', $idPendaftar, $this->form->dataAlamat(), 'alamat');
            $this->upsert(new AyahModel(), 'id_ayah', $idPendaftar, $this->form->dataOrang('ayah'), 'ayah');
            $this->upsert(new IbuModel(), 'id_ibu', $idPendaftar, $this->form->dataOrang('ibu'), 'ibu');

            // 5. wali (hanya bila "Ya, ada wali")
            $waliModel = new WaliModel();
            if ($this->form->adaWali()) {
                $this->upsert($waliModel, 'id_wali', $idPendaftar, $this->form->dataOrang('wali'), 'wali');
            } else {
                $waliModel->where('id_pendaftar', $idPendaftar)->delete();
            }

            // 6. bansos (opsional)
            $bansosModel = new BansosModel();
            if ($this->form->adaBansos()) {
                $this->upsert($bansosModel, 'id_bansos', $idPendaftar, $this->form->dataBansos(), 'bansos');
            } else {
                $bansosModel->where('id_pendaftar', $idPendaftar)->delete();
            }

            // 7. asal sekolah
            $this->upsert(new SekolahModel(), 'id_sekolah', $idPendaftar, $this->dataSekolah(), 'sekolah');

            $db->transComplete();
            if ($db->transStatus() === false) {
                throw new \Exception('Transaksi database gagal. Silakan coba lagi.');
            }
        } catch (\Throwable $e) {
            $db->transRollback();
            FormPendaftaran::hapusFileKk($fileKkBaru);
            throw $e instanceof \Exception ? $e : new \Exception($e->getMessage(), 0, $e);
        }

        // File KK lama dihapus setelah file pengganti tersimpan
        if ($fileKkBaru && !empty($pendaftarLama['file_kk']) && $pendaftarLama['file_kk'] !== $fileKkBaru) {
            FormPendaftaran::hapusFileKk($pendaftarLama['file_kk']);
        }

        return $pendaftarData;
    }

    private function upsert($model, string $pk, int $idPendaftar, array $data, string $label): void
    {
        $lama = $model->where('id_pendaftar', $idPendaftar)->first();

        if ($lama) {
            if (!$model->update($lama[$pk], $data)) {
                throw new \Exception("Gagal memperbarui data {$label}.");
            }

            return;
        }

        if (!$model->insert(['id_pendaftar' => $idPendaftar] + $data)) {
            throw new \Exception("Gagal menyimpan data {$label}.");
        }
    }
}
