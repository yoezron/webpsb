<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menyesuaikan nilai Jarak & Transportasi lama ke pilihan versi EMIS.
 *
 * Hanya nilai yang bisa dipetakan dengan pasti yang diubah. Nilai yang ambigu
 * (jarak "> 20 km", semua nilai waktu tempuh lama) dibiarkan apa adanya; saat
 * data diperbarui, pendaftar/panitia diminta memilih ulang sesuai pilihan EMIS.
 */
class SesuaikanPilihanEmis extends Migration
{
    private array $jarak = [
        '< 1 km'   => 'Kurang dari 5 km',
        '1-5 km'   => 'Kurang dari 5 km',
        '5-10 km'  => 'Antara 5 - 10 Km',
        '10-20 km' => 'Antara 11 - 20 Km',
    ];

    private array $transportasi = [
        'Motor'       => 'Sepeda Motor',
        'Mobil'       => 'Mobil Pribadi',
        'Ojek Online' => 'Ojek',
    ];

    public function up()
    {
        foreach ($this->jarak as $lama => $baru) {
            $this->db->table('alamat_pendaftar')->where('jarak_ke_sekolah', $lama)->update(['jarak_ke_sekolah' => $baru]);
        }
        foreach ($this->transportasi as $lama => $baru) {
            $this->db->table('alamat_pendaftar')->where('transportasi', $lama)->update(['transportasi' => $baru]);
        }
    }

    public function down()
    {
        // Jarak: "Kurang dari 5 km" dikembalikan ke "1-5 km" (asal "< 1 km" tidak bisa dibedakan lagi)
        $balikJarak = ['Kurang dari 5 km' => '1-5 km', 'Antara 5 - 10 Km' => '5-10 km', 'Antara 11 - 20 Km' => '10-20 km'];
        foreach ($balikJarak as $baru => $lama) {
            $this->db->table('alamat_pendaftar')->where('jarak_ke_sekolah', $baru)->update(['jarak_ke_sekolah' => $lama]);
        }
        foreach ($this->transportasi as $lama => $baru) {
            $this->db->table('alamat_pendaftar')->where('transportasi', $baru)->update(['transportasi' => $lama]);
        }
    }
}
