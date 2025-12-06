<?php

namespace App\Controllers;

class Landing extends BaseController
{
    /**
     * Display the landing page
     *
     * @return string
     */
    public function index(): string
    {
        $data = [
            'title' => 'Pendaftaran Santri Baru - Pesantren Persatuan Islam 31 Banjaran',
            'meta_description' => 'Pendaftaran Santri Baru Pesantren Persatuan Islam 31 Banjaran untuk Tingkat Tsanawiyyah dan Mu\'allimin. Daftar sekarang!',
            'meta_keywords' => 'pendaftaran santri, pesantren persis 31, banjaran, tsanawiyyah, muallimin, pendaftaran online',
            'year' => date('Y'),
        ];

        return view('landing/index', $data);
    }
}
