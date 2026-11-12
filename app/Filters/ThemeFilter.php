<?php

namespace App\Filters;

use App\Models\PengaturanModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * ThemeFilter - menerapkan warna tampilan dari menu Pengaturan ke semua halaman HTML.
 *
 * Semua view memakai warna bawaan (#1AB34A, #158a3a, #F3C623, #f5d565) baik lewat
 * CSS variable maupun ditulis langsung. Filter ini:
 *   1. menyisipkan override CSS variable sebelum </head>, dan
 *   2. mengganti warna bawaan yang ditulis langsung dengan warna pilihan superadmin.
 */
class ThemeFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        $contentType = $response->getHeaderLine('Content-Type');
        if ($contentType !== '' && stripos($contentType, 'text/html') === false) {
            return;
        }

        $body = $response->getBody();
        if (!is_string($body) || $body === '' || stripos($body, '</head>') === false) {
            return;
        }

        try {
            $pengaturan = (new PengaturanModel())->semua();
        } catch (\Throwable $e) {
            return;
        }

        $default = PengaturanModel::defaults();
        $map = [
            'warna_primer'          => '--primary-green',
            'warna_primer_gelap'    => '--dark-green',
            'warna_sekunder'        => '--secondary-yellow',
            'warna_sekunder_terang' => '--light-yellow',
        ];

        $warna    = [];
        $berubah  = false;
        foreach ($map as $kunci => $var) {
            $nilai = $this->validHex($pengaturan[$kunci] ?? '') ?? $default[$kunci];
            $warna[$kunci] = $nilai;
            if (strcasecmp($nilai, $default[$kunci]) !== 0) {
                $berubah = true;
            }
        }

        if (!$berubah) {
            return;
        }

        // 1. Ganti warna yang ditulis langsung (hex & rgba).
        //    Halaman Pengaturan dikecualikan agar nilai "bawaan" yang ditampilkan di sana tidak ikut terganti.
        $path = $request instanceof IncomingRequest ? trim($request->getPath(), '/') : '';
        if (!str_starts_with($path, 'admin/pengaturan')) {
            foreach ($map as $kunci => $var) {
                $body = str_ireplace($default[$kunci], $warna[$kunci], $body);
            }

            $rgbaMap = [
                '/rgba\(\s*26\s*,\s*179\s*,\s*74\s*,/i'  => 'rgba(' . hex_ke_rgb($warna['warna_primer']) . ',',
                '/rgba\(\s*21\s*,\s*138\s*,\s*58\s*,/i'  => 'rgba(' . hex_ke_rgb($warna['warna_primer_gelap']) . ',',
                '/rgba\(\s*243\s*,\s*198\s*,\s*35\s*,/i' => 'rgba(' . hex_ke_rgb($warna['warna_sekunder']) . ',',
            ];
            $body = preg_replace(array_keys($rgbaMap), array_values($rgbaMap), $body);
        }

        // 2. Override CSS variables
        $css = ':root{';
        foreach ($map as $kunci => $var) {
            $css .= $var . ':' . $warna[$kunci] . ';';
        }
        $css .= '--primary-rgb:' . hex_ke_rgb($warna['warna_primer']) . ';}';

        $style = '<style id="psb-theme">' . $css . '</style>';
        $pos   = strripos($body, '</head>');
        $body  = substr($body, 0, $pos) . $style . substr($body, $pos);

        $response->setBody($body);

        return $response;
    }

    private function validHex(string $hex): ?string
    {
        $hex = trim($hex);

        return preg_match('/^#[0-9a-fA-F]{6}$/', $hex) ? $hex : null;
    }
}
