<?php

/**
 * Helper PSB: akses cepat ke pengaturan & periode aktif.
 * Di-autoload melalui Config\Autoload::$helpers.
 */

use App\Models\PengaturanModel;
use App\Models\PeriodeModel;

if (!function_exists('pengaturan')) {
    /**
     * Ambil nilai pengaturan (atau semua pengaturan bila $kunci null).
     */
    function pengaturan(?string $kunci = null, $default = null)
    {
        static $model = null;
        $model ??= new PengaturanModel();

        return $kunci === null ? $model->semua() : $model->ambil($kunci, $default);
    }
}

if (!function_exists('periode_aktif')) {
    function periode_aktif(): ?array
    {
        static $model = null;
        $model ??= new PeriodeModel();

        return $model->getAktif();
    }
}

if (!function_exists('periode_aktif_id')) {
    function periode_aktif_id(): ?int
    {
        $p = periode_aktif();

        return $p ? (int) $p['id_periode'] : null;
    }
}

if (!function_exists('tahun_ajaran_aktif')) {
    /**
     * Mis. "2026/2027".
     */
    function tahun_ajaran_aktif(): string
    {
        $p = periode_aktif();
        if ($p && !empty($p['tahun_ajaran'])) {
            return $p['tahun_ajaran'];
        }
        $y = (int) date('Y');

        return $y . '/' . ($y + 1);
    }
}

if (!function_exists('periode_tahun_mulai')) {
    /**
     * Tahun mulai periode aktif, mis. 2026 (dipakai view: "Tahun Ajaran $year/$year+1").
     */
    function periode_tahun_mulai(): int
    {
        return PeriodeModel::tahunMulai(tahun_ajaran_aktif());
    }
}

if (!function_exists('tahun_ajaran_pendaftar')) {
    /**
     * Tahun ajaran milik seorang pendaftar (berdasarkan id_periode-nya).
     */
    function tahun_ajaran_pendaftar(?array $pendaftar): string
    {
        static $cache = [];

        $id = (int) ($pendaftar['id_periode'] ?? 0);
        if ($id <= 0) {
            return tahun_ajaran_aktif();
        }

        if (!isset($cache[$id])) {
            $periode    = (new PeriodeModel())->find($id);
            $cache[$id] = $periode['tahun_ajaran'] ?? tahun_ajaran_aktif();
        }

        return $cache[$id];
    }
}

if (!function_exists('nama_lembaga')) {
    function nama_lembaga(): string
    {
        return (string) pengaturan('nama_lembaga', 'Pesantren Persatuan Islam 31 Banjaran');
    }
}

if (!function_exists('hex_ke_rgb')) {
    /**
     * "#1AB34A" -> "26, 179, 74"
     */
    function hex_ke_rgb(string $hex): string
    {
        $hex = ltrim(trim($hex), '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }
        if (!preg_match('/^[0-9a-fA-F]{6}$/', $hex)) {
            return '0, 0, 0';
        }

        return hexdec(substr($hex, 0, 2)) . ', ' . hexdec(substr($hex, 2, 2)) . ', ' . hexdec(substr($hex, 4, 2));
    }
}

if (!function_exists('link_whatsapp')) {
    /**
     * "0812-3456-789" -> "https://wa.me/628123456789"
     */
    function link_whatsapp(string $nomor): string
    {
        $digits = preg_replace('/\D+/', '', $nomor);
        if (str_starts_with($digits, '0')) {
            $digits = '62' . substr($digits, 1);
        }

        return 'https://wa.me/' . $digits;
    }
}

if (!function_exists('url_aman')) {
    /**
     * Pastikan URL memakai skema http(s); selain itu kembalikan string kosong.
     */
    function url_aman(?string $url): string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return '';
        }
        if (!preg_match('#^https?://#i', $url)) {
            $url = 'https://' . ltrim($url, '/');
        }

        return filter_var($url, FILTER_VALIDATE_URL) ? $url : '';
    }
}

if (!function_exists('logo_path')) {
    /**
     * Path file logo aktif (logo unggahan superadmin, atau logo bawaan).
     */
    function logo_path(): string
    {
        $file = (string) pengaturan('logo_file', '');
        if ($file !== '' && preg_match('#^uploads/logo/[A-Za-z0-9_\-]+\.(png|jpe?g)$#', $file) && is_file(FCPATH . $file)) {
            return FCPATH . $file;
        }

        return FCPATH . 'assets/images/logo/01.png';
    }
}

if (!function_exists('logo_kustom')) {
    function logo_kustom(): bool
    {
        return logo_path() !== FCPATH . 'assets/images/logo/01.png';
    }
}

if (!function_exists('logo_url')) {
    /**
     * URL logo aktif (dengan versi agar cache browser diperbarui saat logo diganti).
     */
    function logo_url(): string
    {
        $path = logo_path();
        $rel  = str_replace('\\', '/', substr($path, strlen(FCPATH)));

        return base_url($rel) . (logo_kustom() ? '?v=' . filemtime($path) : '');
    }
}

if (!function_exists('logo_aset')) {
    /**
     * Pengganti base_url('assets/images/logo/...') di view:
     * bila superadmin mengunggah logo, semua logo & favicon memakai logo tersebut.
     */
    function logo_aset(string $fileBawaan): string
    {
        return logo_kustom() ? logo_url() : base_url('assets/images/logo/' . $fileBawaan);
    }
}

if (!function_exists('latar_kustom')) {
    /**
     * Apakah superadmin sudah mengunggah gambar latar halaman utama.
     */
    function latar_kustom(): bool
    {
        $file = (string) pengaturan('latar_file', '');

        return $file !== '' && preg_match('#^uploads/latar/[A-Za-z0-9_\-]+\.(png|jpe?g|webp)$#', $file) && is_file(FCPATH . $file);
    }
}

if (!function_exists('latar_url')) {
    /**
     * URL gambar latar halaman utama (unggahan superadmin, atau gambar bawaan).
     */
    function latar_url(): string
    {
        if (latar_kustom()) {
            $file = (string) pengaturan('latar_file');

            return base_url($file) . '?v=' . filemtime(FCPATH . $file);
        }

        return base_url('assets/images/banner/01.png');
    }
}

if (!function_exists('logo_data_uri')) {
    /**
     * Logo sebagai data URI (untuk PDF Dompdf).
     */
    function logo_data_uri(): string
    {
        $path = logo_path();
        $data = @file_get_contents($path);
        if ($data === false) {
            return '';
        }
        $mime = (function_exists('mime_content_type') ? mime_content_type($path) : null) ?: 'image/png';

        return 'data:' . $mime . ';base64,' . base64_encode($data);
    }
}

if (!function_exists('kontak_ringkas')) {
    /**
     * Baris kontak untuk dokumen, mis. "WA: +62... | Email: ... | Website: ...".
     */
    function kontak_ringkas(): string
    {
        $bagian = array_filter([
            pengaturan('kontak_whatsapp') ? 'WA: ' . pengaturan('kontak_whatsapp') : '',
            pengaturan('kontak_telepon') ? 'Telp: ' . pengaturan('kontak_telepon') : '',
            pengaturan('kontak_email') ? 'Email: ' . pengaturan('kontak_email') : '',
            pengaturan('kontak_website') ? 'Website: ' . pengaturan('kontak_website') : '',
        ]);

        return implode(' | ', $bagian);
    }
}
