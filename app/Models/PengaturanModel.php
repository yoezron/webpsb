<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * Pengaturan aplikasi (key-value): warna tampilan, konten landing page, kontak, dll.
 * Nilai yang belum pernah disimpan memakai nilai default dari self::defaults().
 */
class PengaturanModel extends Model
{
    protected $table         = 'pengaturan';
    protected $primaryKey    = 'kunci';
    protected $useAutoIncrement = false;
    protected $returnType    = 'array';
    protected $allowedFields = ['kunci', 'nilai', 'updated_at'];

    private const CACHE_KEY = 'psb_pengaturan';

    private static ?array $memo = null;

    /**
     * Nilai default semua pengaturan.
     */
    public static function defaults(): array
    {
        return [
            // ---------------- Warna tampilan ----------------
            'warna_primer'          => '#1AB34A',
            'warna_primer_gelap'    => '#158a3a',
            'warna_sekunder'        => '#F3C623',
            'warna_sekunder_terang' => '#f5d565',

            // ---------------- Teks & latar halaman utama ----------------
            'hero_warna_judul'    => '#FFFFFF',
            'hero_warna_subjudul' => '',        // kosong = ikuti Warna Aksen (Terang)
            'hero_warna_teks'     => '#FFFFFF',
            'hero_bayangan_teks'  => '1',
            'hero_overlay'        => '95',      // kepekatan lapisan warna di atas gambar latar (0-100 %)
            'latar_file'          => '',        // gambar latar unggahan (relatif thd folder public)

            // ---------------- Identitas ----------------
            'nama_lembaga'   => 'Pesantren Persatuan Islam 31 Banjaran',
            'nama_singkat'   => 'PSB Persis 31',
            'meta_deskripsi' => "Pendaftaran Santri Baru Pesantren Persatuan Islam 31 Banjaran untuk Tingkat Tsanawiyyah dan Mu'allimin. Daftar sekarang!",
            'meta_keywords'  => 'pendaftaran santri, pesantren persis 31, banjaran, tsanawiyyah, muallimin, pendaftaran online',

            // ---------------- Hero landing ----------------
            'hero_judul'          => 'Pendaftaran Santri Baru',
            'hero_subjudul'       => 'Pesantren Persatuan Islam 31 Banjaran',
            'hero_teks'           => 'Selamat datang di sistem pendaftaran online Pesantren Persatuan Islam 31 Banjaran. Silakan pilih tingkat pendidikan yang Anda inginkan untuk memulai proses pendaftaran. Kami menawarkan pendidikan Islam yang berkualitas dengan metode pembelajaran modern.',
            'badge_dibuka'        => 'Pendaftaran Dibuka!',
            'badge_ditutup'       => 'Pendaftaran Ditutup',
            'label_tombol_tsn'    => 'Daftar Tingkat Tsanawiyyah',
            'label_tombol_mua'    => "Daftar Tingkat Mu'allimin",
            'tampil_tombol_update'    => '1',
            'tampil_tombol_statistik' => '1',
            'tampil_pengumuman'       => '1',

            // ---------------- Keunggulan ----------------
            'tampil_keunggulan'    => '1',
            'keunggulan_judul'     => 'Mengapa Memilih Kami?',
            'keunggulan_subjudul'  => 'Keunggulan Pesantren Persatuan Islam 31 Banjaran',
            'keunggulan_items'     => json_encode([
                ['ikon' => 'icofont-book-alt', 'judul' => 'Kurikulum Terpadu', 'teks' => 'Menggabungkan kurikulum Kementerian Agama dengan kurikulum pesantren untuk pembelajaran yang komprehensif.'],
                ['ikon' => 'icofont-teacher', 'judul' => 'Tenaga Pengajar Berkualitas', 'teks' => 'Didukung oleh ustadz dan ustadzah yang berpengalaman dan berkompeten di bidangnya.'],
                ['ikon' => 'icofont-home', 'judul' => 'Fasilitas Lengkap', 'teks' => 'Ruang kelas nyaman, masjid luas, perpustakaan, laboratorium, dan fasilitas olahraga yang memadai.'],
                ['ikon' => 'icofont-award', 'judul' => 'Prestasi Gemilang', 'teks' => 'Santri kami berprestasi dalam bidang akademik, tahfidz, dan kegiatan ekstrakurikuler.'],
                ['ikon' => 'icofont-ui-calendar', 'judul' => 'Pendaftaran Online', 'teks' => 'Proses pendaftaran mudah dan cepat melalui sistem online yang user-friendly.'],
                ['ikon' => 'icofont-heart', 'judul' => 'Pembinaan Karakter', 'teks' => 'Fokus pada pembentukan akhlak mulia dan karakter Islami yang kuat.'],
            ], JSON_UNESCAPED_UNICODE),
            'cta_judul'            => 'Siap Bergabung Bersama Kami?',

            // ---------------- Kontak ----------------
            'tampil_kontak'   => '1',
            'kontak_judul'    => 'Hubungi Kami',
            'kontak_alamat'   => 'Jl. Pajagalan No. 115, Banjaran, Kab. Bandung, Jawa Barat',
            'kontak_telepon'  => '',
            'kontak_whatsapp' => '+6281320206395',
            'kontak_email'    => 'pesantrenpersis31@gmail.com',
            'kontak_website'  => 'psb.mapersis31banjaran.sch.id',
            'kontak_jam'      => '',
            'kontak_maps'     => '',
            'sosmed_instagram' => '',
            'sosmed_facebook'  => '',
            'sosmed_youtube'   => '',
            'sosmed_tiktok'    => '',

            // ---------------- Footer ----------------
            'footer_teks' => 'All Rights Reserved.',

            // ---------------- Kartu & dokumen PDF ----------------
            'ketua_panitia' => 'Hilman Latief, M.Pd.',
            'jabatan_ttd'   => 'Ketua Panitia PSB',
            'kota_ttd'      => 'Banjaran',

            // ---------------- Logo (path relatif terhadap folder public) ----------------
            'logo_file' => '',
        ];
    }

    /**
     * Semua pengaturan (default digabung dengan nilai tersimpan).
     */
    public function semua(): array
    {
        if (self::$memo !== null) {
            return self::$memo;
        }

        $tersimpan = null;

        try {
            $tersimpan = cache(self::CACHE_KEY);
        } catch (\Throwable $e) {
            $tersimpan = null;
        }

        if (!is_array($tersimpan)) {
            $tersimpan = [];
            try {
                if ($this->db->tableExists($this->table)) {
                    foreach ($this->findAll() as $row) {
                        $tersimpan[$row['kunci']] = $row['nilai'];
                    }
                }
                try {
                    cache()->save(self::CACHE_KEY, $tersimpan, 3600);
                } catch (\Throwable $e) {
                    // abaikan kegagalan cache
                }
            } catch (\Throwable $e) {
                log_message('error', 'PengaturanModel::semua gagal: ' . $e->getMessage());
            }
        }

        return self::$memo = array_merge(self::defaults(), $tersimpan);
    }

    public function ambil(string $kunci, $default = null)
    {
        $semua = $this->semua();

        return array_key_exists($kunci, $semua) ? $semua[$kunci] : $default;
    }

    /**
     * Simpan banyak pengaturan sekaligus.
     */
    public function simpanBanyak(array $data): bool
    {
        $now  = date('Y-m-d H:i:s');
        $rows = [];

        foreach ($data as $kunci => $nilai) {
            if (!array_key_exists($kunci, self::defaults())) {
                continue; // hanya kunci yang dikenal
            }
            $rows[] = ['kunci' => $kunci, 'nilai' => (string) $nilai, 'updated_at' => $now];
        }

        if ($rows === []) {
            return true;
        }

        $this->db->transStart();
        foreach ($rows as $row) {
            $this->db->table($this->table)->replace($row);
        }
        $this->db->transComplete();

        $this->bersihkanCache();

        return $this->db->transStatus();
    }

    /**
     * Hapus pengaturan (kembali ke nilai default).
     */
    public function resetKunci(array $kunci): bool
    {
        if ($kunci !== []) {
            $this->db->table($this->table)->whereIn('kunci', $kunci)->delete();
        }
        $this->bersihkanCache();

        return true;
    }

    public function bersihkanCache(): void
    {
        self::$memo = null;
        try {
            cache()->delete(self::CACHE_KEY);
        } catch (\Throwable $e) {
            // abaikan
        }
    }
}
