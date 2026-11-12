<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Melengkapi isian formulir agar sesuai data EMIS (sampai bagian Wali):
 *  - pendaftar   : agama, file_kk (upload Kartu Keluarga)
 *  - data_ayah   : domisili_ayah, status_tempat_tinggal_ayah
 *  - data_ibu    : domisili_ibu, status_tempat_tinggal_ibu
 *  - data_wali   : domisili_wali, alamat_wali, status_tempat_tinggal_wali
 *
 * Juga menyeragamkan nama santri/ayah/ibu/wali yang sudah ada menjadi HURUF BESAR.
 */
class AddKolomEmisDanFileKk extends Migration
{
    private array $kolom = [
        'pendaftar' => [
            'agama'   => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'jenis_kelamin'],
            'file_kk' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
        ],
        'data_ayah' => [
            'domisili_ayah'              => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'pekerjaan_ayah'],
            'status_tempat_tinggal_ayah' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'alamat_ayah'],
        ],
        'data_ibu' => [
            'domisili_ibu'              => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'pekerjaan_ibu'],
            'status_tempat_tinggal_ibu' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'alamat_ibu'],
        ],
        'data_wali' => [
            'domisili_wali'              => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true, 'after' => 'pekerjaan_wali'],
            'alamat_wali'                => ['type' => 'TEXT', 'null' => true, 'after' => 'hp_wali'],
            'status_tempat_tinggal_wali' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true, 'after' => 'alamat_wali'],
        ],
    ];

    public function up()
    {
        foreach ($this->kolom as $tabel => $fields) {
            foreach ($fields as $nama => $def) {
                if (!$this->db->fieldExists($nama, $tabel)) {
                    $this->forge->addColumn($tabel, [$nama => $def]);
                }
            }
        }

        // Seragamkan nama menjadi huruf besar
        $this->db->query('UPDATE `pendaftar` SET `nama_lengkap` = UPPER(TRIM(`nama_lengkap`)) WHERE `nama_lengkap` IS NOT NULL');
        $this->db->query('UPDATE `data_ayah` SET `nama_ayah` = UPPER(TRIM(`nama_ayah`)) WHERE `nama_ayah` IS NOT NULL');
        $this->db->query('UPDATE `data_ibu` SET `nama_ibu` = UPPER(TRIM(`nama_ibu`)) WHERE `nama_ibu` IS NOT NULL');
        $this->db->query('UPDATE `data_wali` SET `nama_wali` = UPPER(TRIM(`nama_wali`)) WHERE `nama_wali` IS NOT NULL');
    }

    public function down()
    {
        foreach ($this->kolom as $tabel => $fields) {
            foreach (array_keys($fields) as $nama) {
                if ($this->db->fieldExists($nama, $tabel)) {
                    $this->forge->dropColumn($tabel, $nama);
                }
            }
        }
    }
}
