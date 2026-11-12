<?php

namespace App\Libraries;

use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\IncomingRequest;

/**
 * Logika formulir pendaftaran yang dipakai bersama oleh:
 *  - PendaftaranLengkap (pendaftaran baru)
 *  - UpdateDataPendaftar (perbarui data mandiri)
 *
 * Mencakup daftar pilihan, aturan validasi dinamis (Ayah/Ibu/Wali),
 * penyiapan data per tabel, dan penyimpanan file Kartu Keluarga.
 *
 * Bagian yang disesuaikan dengan data EMIS: Data Diri, Alamat, Ayah, Ibu, Wali.
 */
class FormPendaftaran
{
    // ------------------------------------------------------------------
    // Daftar pilihan
    // ------------------------------------------------------------------
    public const AGAMA = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];

    public const YANG_MEMBIAYAI = ['Orang Tua', 'Wali/Orang Tua Asuh', 'Tanggungan Sendiri', 'Lainnya'];

    public const STATUS_ORTU = ['Masih Hidup', 'Sudah Meninggal', 'Tidak Diketahui'];

    public const PENDIDIKAN = ['Tidak Sekolah', 'SD/MI', 'SMP/MTs', 'SMA/MA/SMK', 'D1', 'D2', 'D3', 'D4/S1', 'S2', 'S3'];

    public const PEKERJAAN = [
        'Tidak Bekerja', 'Pensiun', 'PNS', 'TNI/Polri', 'Guru/Dosen', 'Pegawai Swasta', 'Wiraswasta',
        'Pengacara/Jaksa/Hakim/Notaris', 'Seniman/Pelukis/Artis/Sejenis', 'Dokter/Bidan/Perawat',
        'Pilot/Pramugara', 'Pedagang', 'Petani/Peternak', 'Nelayan', 'Buruh (Tani/Pabrik/Bangunan)',
        'Sopir/Masinis/Kondektur', 'Politikus', 'Lainnya',
    ];

    public const PEKERJAAN_IBU_TAMBAHAN = ['Ibu Rumah Tangga'];

    public const PENGHASILAN = [
        'Dibawah 800.000'       => 'Dibawah Rp 800.000',
        '800.001-1.200.000'     => 'Rp 800.001 - Rp 1.200.000',
        '1.200.001-1.800.000'   => 'Rp 1.200.001 - Rp 1.800.000',
        '1.800.001-2.500.000'   => 'Rp 1.800.001 - Rp 2.500.000',
        '2.500.001-3.500.000'   => 'Rp 2.500.001 - Rp 3.500.000',
        '3.500.001-4.800.000'   => 'Rp 3.500.001 - Rp 4.800.000',
        '4.800.001-6.500.000'   => 'Rp 4.800.001 - Rp 6.500.000',
        '6.500.001-10.000.000'  => 'Rp 6.500.001 - Rp 10.000.000',
        '10.000.001-20.000.000' => 'Rp 10.000.001 - Rp 20.000.000',
        'Diatas 20.000.000'     => 'Diatas Rp 20.000.000',
    ];

    public const DOMISILI = ['Dalam Negeri', 'Luar Negeri'];

    public const STATUS_TEMPAT_TINGGAL = ['Milik Sendiri', 'Rumah Orang Tua', 'Rumah Saudara/Kerabat', 'Rumah Dinas', 'Sewa/Kontrak', 'Lainnya'];

    public const HUBUNGAN_WALI = [
        'Sama dengan Ayah Kandung', 'Sama dengan Ibu Kandung',
        'Kakek', 'Nenek', 'Paman', 'Bibi', 'Kakak', 'Ayah/Ibu Tiri', 'Lainnya',
    ];

    // Pilihan Jarak / Waktu Tempuh / Transportasi mengikuti isian EMIS agar data bisa langsung dipindahkan
    public const JARAK = ['Kurang dari 5 km', 'Antara 5 - 10 Km', 'Antara 11 - 20 Km', 'Antara 21 - 30 Km', 'Lebih dari 30 Km'];
    public const WAKTU_TEMPUH = ['1-10 menit', '10-19 menit', '20-29 menit', '30-39 menit', '40-59 menit', '1-2 jam', 'Lebih dari 2 jam'];
    public const TRANSPORTASI = ['Jalan Kaki', 'Sepeda', 'Sepeda Motor', 'Mobil Pribadi', 'Antar Jemput Sekolah', 'Angkutan Umum', 'Ojek', 'Perahu/Sampan', 'Lainnya'];

    /** Batas ukuran file KK (KB) */
    public const KK_MAX_KB = 1024;

    /** Folder penyimpanan KK (di dalam writable/uploads, tidak bisa diakses publik) */
    public const KK_DIR = 'kartu_keluarga';

    public function __construct(private IncomingRequest $request)
    {
    }

    // ------------------------------------------------------------------
    // Validasi
    // ------------------------------------------------------------------

    public static function pekerjaanUntuk(string $peran): array
    {
        return $peran === 'ibu' ? array_merge(self::PEKERJAAN, self::PEKERJAAN_IBU_TAMBAHAN) : self::PEKERJAAN;
    }

    private static function inList(array $opsi): string
    {
        return 'in_list[' . implode(',', $opsi) . ']';
    }

    /**
     * Apakah data orang (ayah/ibu) wajib lengkap berdasarkan statusnya.
     */
    public function ortuWajib(string $peran): bool
    {
        return $this->request->getPost('status_' . $peran) === 'Masih Hidup';
    }

    public function adaWali(): bool
    {
        return $this->request->getPost('ada_wali') === 'Ya';
    }

    /**
     * Aturan validasi untuk bagian Data Diri, Alamat, Ayah, Ibu, Wali, Bansos.
     * Aturan Asal Sekolah & konfirmasi tetap di masing-masing controller.
     */
    public function rules(): array
    {
        $rules = [
            // ---------------- Data Diri ----------------
            'nisn'                   => 'required|numeric|exact_length[10]',
            'nik'                    => 'required|numeric|exact_length[16]',
            'nama_lengkap'           => 'required|min_length[3]|max_length[150]',
            'jenis_kelamin'          => 'required|in_list[L,P]',
            'tempat_lahir'           => 'required|max_length[100]',
            'tanggal_lahir'          => 'required|valid_date[Y-m-d]',
            'agama'                  => 'required|' . self::inList(self::AGAMA),
            'status_keluarga'        => 'permit_empty|in_list[Anak Kandung,Anak Tiri,Anak Angkat]',
            'anak_ke'                => 'required|is_natural_no_zero|less_than[20]',
            'jumlah_saudara'         => 'required|is_natural|less_than[20]',
            'hobi'                   => 'required|max_length[100]',
            'cita_cita'              => 'required|max_length[100]',
            'no_hp'                  => 'required|numeric|min_length[9]|max_length[20]',
            'yang_membiayai_sekolah' => 'required|' . self::inList(self::YANG_MEMBIAYAI),
            'kebutuhan_disabilitas'  => 'required',
            'kebutuhan_khusus'       => 'required|max_length[100]',
            'ukuran_baju'            => 'permit_empty|in_list[XS,S,M,L,XL,XXL,XXXL]',
            'prestasi'               => 'permit_empty|max_length[500]',

            // ---------------- Alamat ----------------
            'nomor_kk'             => 'permit_empty|numeric|exact_length[16]',
            'nama_kepala_keluarga' => 'required|max_length[150]',
            'jenis_tempat_tinggal' => 'permit_empty|in_list[Milik Sendiri,Rumah Orang Tua,Rumah Saudara,Rumah Dinas,Sewa/Kontrak,Lainnya]',
            'alamat'               => 'required|max_length[500]',
            'rt_rw'                => 'required|max_length[20]',
            'desa'                 => 'required|max_length[100]',
            'kecamatan'            => 'required|max_length[100]',
            'kabupaten'            => 'required|max_length[100]',
            'provinsi'             => 'required|max_length[100]',
            'kode_pos'             => 'required|numeric|exact_length[5]',
            'tinggal_bersama'      => 'required|max_length[100]',
            'jarak_ke_sekolah'     => 'required|' . self::inList(self::JARAK),
            'waktu_tempuh'         => 'required|' . self::inList(self::WAKTU_TEMPUH),
            'transportasi'         => 'required|' . self::inList(self::TRANSPORTASI),
            'email'                => 'required|valid_email|max_length[100]',
            'media_sosial'         => 'permit_empty|max_length[255]',

            // ---------------- Wali (pilihan ada/tidak) ----------------
            'ada_wali' => 'required|in_list[Ya,Tidak]',

            // ---------------- Bansos (opsional) ----------------
            'no_kks' => 'permit_empty|max_length[50]',
            'no_pkh' => 'permit_empty|max_length[50]',
            'no_kip' => 'permit_empty|max_length[50]',
        ];

        foreach (['ayah', 'ibu'] as $peran) {
            $rules['status_' . $peran] = 'required|' . self::inList(self::STATUS_ORTU);
            $rules += $this->rulesOrang($peran, $this->ortuWajib($peran));
        }

        $rules += $this->rulesOrang('wali', $this->adaWali());
        $rules['hubungan_wali'] = ($this->adaWali() ? 'required|' : 'permit_empty|') . self::inList(self::HUBUNGAN_WALI);

        return $rules;
    }

    /**
     * Aturan untuk satu orang (ayah/ibu/wali). Bila tidak wajib, field tetap boleh diisi
     * tetapi formatnya tetap diperiksa.
     */
    private function rulesOrang(string $p, bool $wajib): array
    {
        $w = $wajib ? 'required|' : 'permit_empty|';

        return [
            'nama_' . $p                  => $w . 'max_length[150]',
            'nik_' . $p                   => $w . 'numeric|exact_length[16]',
            'tempat_lahir_' . $p          => $w . 'max_length[100]',
            'tanggal_lahir_' . $p         => $w . 'valid_date[Y-m-d]',
            'pendidikan_' . $p            => $w . self::inList(self::PENDIDIKAN),
            'pekerjaan_' . $p             => $w . self::inList(self::pekerjaanUntuk($p)),
            'domisili_' . $p              => $w . self::inList(self::DOMISILI),
            'hp_' . $p                    => $w . 'numeric|max_length[20]',
            'penghasilan_' . $p           => $w . self::inList(array_keys(self::PENGHASILAN)),
            'alamat_' . $p                => $w . 'max_length[500]',
            'status_tempat_tinggal_' . $p => $w . self::inList(self::STATUS_TEMPAT_TINGGAL),
        ];
    }

    /**
     * Aturan file Kartu Keluarga.
     *
     * @param bool $wajib true bila belum ada file KK tersimpan
     */
    public function rulesFileKk(bool $wajib): array
    {
        $file = $this->request->getFile('file_kk');
        $adaUpload = $file instanceof UploadedFile && $file->getError() !== UPLOAD_ERR_NO_FILE;

        if (!$wajib && !$adaUpload) {
            return [];
        }

        $max = self::KK_MAX_KB;

        return [
            'file_kk' => ($wajib ? 'uploaded[file_kk]|' : '')
                . "max_size[file_kk,{$max}]|ext_in[file_kk,jpg,jpeg]|mime_in[file_kk,image/jpeg,image/pjpeg]|is_image[file_kk]",
        ];
    }

    /**
     * Cek NISN / NIK yang sudah terdaftar pada periode yang sama.
     *
     * Nomor pendaftaran milik data lain sengaja tidak ditampilkan agar tidak bisa
     * dipakai orang lain untuk masuk ke menu "Perbarui Data".
     *
     * @return array [field => pesan] (kosong bila tidak ada duplikat)
     */
    public function cekDuplikat(?int $idPeriode, ?int $kecualiId = null): array
    {
        $db     = \Config\Database::connect();
        $errors = [];

        foreach (['nisn' => 'NISN', 'nik' => 'NIK'] as $field => $label) {
            $nilai = $this->teks($field);
            if ($nilai === '') {
                continue;
            }

            $builder = $db->table('pendaftar')
                ->where($field, $nilai)
                ->where('deleted_at', null);
            $idPeriode ? $builder->where('id_periode', $idPeriode) : $builder->where('id_periode', null);
            if ($kecualiId) {
                $builder->where('id_pendaftar !=', $kecualiId);
            }

            if ($builder->countAllResults() > 0) {
                $errors[$field] = "{$label} {$nilai} sudah terdaftar pada periode pendaftaran ini. "
                    . 'Jika ingin memperbaiki data, gunakan menu "Perbarui Data Pendaftaran" atau hubungi panitia.';
            }
        }

        return $errors;
    }

    public function messages(): array
    {
        $label = [
            'nisn' => 'NISN', 'nik' => 'NIK', 'nama_lengkap' => 'Nama lengkap', 'jenis_kelamin' => 'Jenis kelamin',
            'tempat_lahir' => 'Tempat lahir', 'tanggal_lahir' => 'Tanggal lahir', 'agama' => 'Agama',
            'anak_ke' => 'Anak ke', 'jumlah_saudara' => 'Jumlah saudara', 'hobi' => 'Hobi', 'cita_cita' => 'Cita-cita',
            'no_hp' => 'No. HP santri', 'yang_membiayai_sekolah' => 'Yang membiayai sekolah',
            'kebutuhan_disabilitas' => 'Kebutuhan disabilitas', 'kebutuhan_khusus' => 'Kebutuhan khusus',
            'nomor_kk' => 'Nomor KK', 'nama_kepala_keluarga' => 'Nama kepala keluarga', 'alamat' => 'Alamat',
            'rt_rw' => 'RT/RW', 'desa' => 'Desa/Kelurahan', 'kecamatan' => 'Kecamatan', 'kabupaten' => 'Kabupaten/Kota',
            'provinsi' => 'Provinsi', 'kode_pos' => 'Kode pos', 'tinggal_bersama' => 'Status tempat tinggal santri',
            'jarak_ke_sekolah' => 'Jarak ke sekolah', 'waktu_tempuh' => 'Waktu tempuh', 'transportasi' => 'Transportasi',
            'email' => 'Email santri', 'ada_wali' => 'Keberadaan wali', 'hubungan_wali' => 'Status/hubungan wali',
        ];
        foreach (['ayah' => 'Ayah', 'ibu' => 'Ibu', 'wali' => 'Wali'] as $p => $P) {
            $label += [
                'status_' . $p => "Status {$P}", 'nama_' . $p => "Nama {$P}", 'nik_' . $p => "NIK {$P}",
                'tempat_lahir_' . $p => "Tempat lahir {$P}", 'tanggal_lahir_' . $p => "Tanggal lahir {$P}",
                'pendidikan_' . $p => "Pendidikan {$P}", 'pekerjaan_' . $p => "Pekerjaan {$P}",
                'domisili_' . $p => "Domisili {$P}", 'hp_' . $p => "No. HP {$P}", 'penghasilan_' . $p => "Penghasilan {$P}",
                'alamat_' . $p => "Alamat {$P}", 'status_tempat_tinggal_' . $p => "Status tempat tinggal {$P}",
            ];
        }

        $pesan = [];
        foreach ($label as $field => $l) {
            $pesan[$field] = [
                'required'           => "{$l} wajib diisi.",
                'in_list'            => "{$l} tidak valid.",
                'numeric'            => "{$l} harus berupa angka.",
                'exact_length'       => "{$l} harus {param} digit.",
                'min_length'         => "{$l} minimal {param} karakter.",
                'max_length'         => "{$l} maksimal {param} karakter.",
                'valid_date'         => "Format {$l} tidak valid.",
                'valid_email'        => "Format {$l} tidak valid.",
                'is_natural'         => "{$l} harus berupa angka 0 atau lebih.",
                'is_natural_no_zero' => "{$l} harus berupa angka 1 atau lebih.",
                'less_than'          => "{$l} harus kurang dari {param}.",
            ];
        }

        $pesan['file_kk'] = [
            'uploaded' => 'Scan/foto Kartu Keluarga wajib diunggah.',
            'max_size' => 'Ukuran file Kartu Keluarga maksimal 1 MB.',
            'ext_in'   => 'File Kartu Keluarga harus berformat JPG/JPEG.',
            'mime_in'  => 'File Kartu Keluarga harus berupa gambar JPG/JPEG.',
            'is_image' => 'File Kartu Keluarga harus berupa gambar JPG/JPEG.',
        ];

        return $pesan;
    }

    // ------------------------------------------------------------------
    // Penyiapan data
    // ------------------------------------------------------------------

    private function teks(string $field): string
    {
        return trim((string) ($this->request->getPost($field) ?? ''));
    }

    private function nullable(string $field): ?string
    {
        $v = $this->teks($field);

        return $v === '' ? null : $v;
    }

    private function angka(string $field): ?int
    {
        $v = $this->teks($field);

        return $v === '' ? null : (int) $v;
    }

    public static function hurufBesar(?string $nama): string
    {
        return mb_strtoupper(preg_replace('/\s+/', ' ', trim((string) $nama)), 'UTF-8');
    }

    private function gabung(string $field): ?string
    {
        $v = $this->request->getPost($field);

        return is_array($v) && $v !== [] ? implode(', ', array_map('trim', $v)) : null;
    }

    public function dataPendaftar(): array
    {
        return [
            'nisn'                   => $this->teks('nisn'),
            'nik'                    => $this->teks('nik'),
            'nama_lengkap'           => self::hurufBesar($this->teks('nama_lengkap')),
            'jenis_kelamin'          => $this->request->getPost('jenis_kelamin'),
            'agama'                  => $this->nullable('agama'),
            'tempat_lahir'           => $this->teks('tempat_lahir'),
            'tanggal_lahir'          => $this->nullable('tanggal_lahir'),
            'status_keluarga'        => $this->nullable('status_keluarga'),
            'anak_ke'                => $this->angka('anak_ke'),
            'jumlah_saudara'         => $this->angka('jumlah_saudara'),
            'hobi'                   => $this->teks('hobi'),
            'cita_cita'              => $this->teks('cita_cita'),
            'yang_membiayai_sekolah' => $this->nullable('yang_membiayai_sekolah'),
            'pernah_paud'            => $this->request->getPost('pernah_paud') ? 1 : 0,
            'pernah_tk'              => $this->request->getPost('pernah_tk') ? 1 : 0,
            'kebutuhan_disabilitas'  => $this->gabung('kebutuhan_disabilitas'),
            'kebutuhan_khusus'       => $this->teks('kebutuhan_khusus'),
            'imunisasi'              => $this->gabung('imunisasi'),
            'no_hp'                  => $this->teks('no_hp'),
            'ukuran_baju'            => $this->nullable('ukuran_baju'),
            'prestasi'               => $this->teks('prestasi'),
            'minat_bakat'            => $this->teks('minat_bakat'),
        ];
    }

    public function dataAlamat(): array
    {
        $data = [];
        foreach ([
            'nomor_kk', 'nama_kepala_keluarga', 'jenis_tempat_tinggal', 'alamat', 'rt_rw', 'desa', 'kecamatan',
            'kabupaten', 'provinsi', 'kode_pos', 'tinggal_bersama', 'jarak_ke_sekolah', 'waktu_tempuh',
            'transportasi', 'email', 'media_sosial',
        ] as $f) {
            $data[$f] = $this->teks($f);
        }
        $data['jenis_tempat_tinggal'] = $data['jenis_tempat_tinggal'] ?: null;

        return $data;
    }

    /**
     * Data ayah / ibu / wali (tanpa id_pendaftar).
     */
    public function dataOrang(string $p): array
    {
        $data = [
            'nama_' . $p                  => self::hurufBesar($this->teks('nama_' . $p)),
            'nik_' . $p                   => $this->teks('nik_' . $p),
            'tempat_lahir_' . $p          => $this->teks('tempat_lahir_' . $p),
            'tanggal_lahir_' . $p         => $this->nullable('tanggal_lahir_' . $p),
            'pendidikan_' . $p            => $this->nullable('pendidikan_' . $p),
            'pekerjaan_' . $p             => $this->nullable('pekerjaan_' . $p),
            'domisili_' . $p              => $this->nullable('domisili_' . $p),
            'penghasilan_' . $p           => $this->nullable('penghasilan_' . $p),
            'hp_' . $p                    => $this->teks('hp_' . $p),
            'alamat_' . $p                => $this->teks('alamat_' . $p),
            'status_tempat_tinggal_' . $p => $this->nullable('status_tempat_tinggal_' . $p),
        ];

        if ($p === 'wali') {
            $data['hubungan_wali'] = $this->nullable('hubungan_wali');
        } else {
            $data['status_' . $p] = $this->nullable('status_' . $p);
        }

        return $data;
    }

    public function dataBansos(): array
    {
        return [
            'no_kks' => $this->teks('no_kks'),
            'no_pkh' => $this->teks('no_pkh'),
            'no_kip' => $this->teks('no_kip'),
        ];
    }

    public function adaBansos(): bool
    {
        return implode('', $this->dataBansos()) !== '';
    }

    // ------------------------------------------------------------------
    // File Kartu Keluarga
    // ------------------------------------------------------------------

    public static function folderKk(): string
    {
        return WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . self::KK_DIR . DIRECTORY_SEPARATOR;
    }

    /**
     * Pindahkan file KK yang diunggah ke folder privat.
     *
     * @return string|null path relatif (disimpan di kolom pendaftar.file_kk), null bila tidak ada upload
     */
    public function simpanFileKk(string $nisn): ?string
    {
        $file = $this->request->getFile('file_kk');
        if (!$file instanceof UploadedFile || !$file->isValid() || $file->hasMoved()) {
            return null;
        }

        $folder = self::folderKk();
        if (!is_dir($folder)) {
            mkdir($folder, 0755, true);
            @file_put_contents($folder . 'index.html', '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><p>Directory access is forbidden.</p></body></html>');
        }

        $nama = 'kk_' . preg_replace('/\D/', '', $nisn) . '_' . date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.jpg';
        $file->move($folder, $nama);

        return self::KK_DIR . '/' . $nama;
    }

    /**
     * Path absolut file KK dari nilai kolom file_kk (null bila tidak ada / tidak valid).
     */
    public static function pathKk(?string $relatif): ?string
    {
        if (!$relatif || !preg_match('#^' . self::KK_DIR . '/[A-Za-z0-9_\-]+\.jpe?g$#', $relatif)) {
            return null;
        }

        $path = WRITEPATH . 'uploads' . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relatif);

        return is_file($path) ? $path : null;
    }

    public static function hapusFileKk(?string $relatif): void
    {
        $path = self::pathKk($relatif);
        if ($path) {
            @unlink($path);
        }
    }

    /**
     * Nama file unduhan: "Nama Siswa_NISN.jpg", mis. "Aqila Qurota Ayuni_0116077774.jpg".
     */
    public static function namaFileUnduhKk(array $pendaftar): string
    {
        $nama = mb_convert_case(mb_strtolower(trim((string) ($pendaftar['nama_lengkap'] ?? 'Tanpa Nama')), 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
        $nama = preg_replace('/[\\\\\/:*?"<>|]+/', '', $nama);
        $nama = trim(preg_replace('/\s+/', ' ', $nama)) ?: 'Tanpa Nama';
        $nisn = preg_replace('/[^0-9A-Za-z]/', '', (string) ($pendaftar['nisn'] ?? '')) ?: 'TanpaNISN';

        return $nama . '_' . $nisn . '.jpg';
    }
}
