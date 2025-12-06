<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PendaftarSeeder extends Seeder
{
    public function run()
    {
        // Sample data for Tsanawiyyah
        $pendaftarTsn = [
            'nomor_pendaftaran' => 'T2026-001',
            'jalur_pendaftaran' => 'TSANAWIYYAH',
            'nisn'              => '0051234567',
            'nik'               => '3273011234567890',
            'nama_lengkap'      => 'Ahmad Fauzi',
            'jenis_kelamin'     => 'L',
            'tempat_lahir'      => 'Bandung',
            'tanggal_lahir'     => '2012-05-15',
            'status_keluarga'   => 'Anak Kandung',
            'anak_ke'           => 1,
            'jumlah_saudara'    => 2,
            'hobi'              => 'Membaca',
            'cita_cita'         => 'Ustadz',
            'pernah_paud'       => true,
            'pernah_tk'         => true,
            'kebutuhan_disabilitas' => 'Tidak Ada',
            'imunisasi'         => 'Lengkap',
            'no_hp'             => '081234567890',
            'ukuran_baju'       => 'M',
            'prestasi'          => 'Juara 1 Hafalan Quran Tingkat Kecamatan',
        ];

        $this->db->table('pendaftar')->insert($pendaftarTsn);
        $idPendaftarTsn = $this->db->insertID();

        // Alamat for Tsanawiyyah
        $alamatTsn = [
            'id_pendaftar'         => $idPendaftarTsn,
            'nomor_kk'             => '3273011234567890',
            'jenis_tempat_tinggal' => 'Rumah Sendiri',
            'alamat'               => 'Jl. Raya Banjaran No. 123',
            'desa'                 => 'Banjaran',
            'kecamatan'            => 'Banjaran',
            'kabupaten'            => 'Bandung',
            'provinsi'             => 'Jawa Barat',
            'kode_pos'             => '40377',
            'jarak_ke_sekolah'     => '2 Km',
            'waktu_tempuh'         => '10 Menit',
            'transportasi'         => 'Sepeda',
            'email'                => 'ahmad.fauzi@example.com',
            'media_sosial'         => '@ahmadfauzi',
        ];

        $this->db->table('alamat_pendaftar')->insert($alamatTsn);

        // Data Ayah
        $ayahTsn = [
            'id_pendaftar'       => $idPendaftarTsn,
            'nama_ayah'          => 'Bapak Ahmad Fauzi Sr.',
            'nik_ayah'           => '3273011234567891',
            'tempat_lahir_ayah'  => 'Bandung',
            'tanggal_lahir_ayah' => '1980-01-15',
            'status_ayah'        => 'Hidup',
            'pendidikan_ayah'    => 'S1',
            'pekerjaan_ayah'     => 'Wiraswasta',
            'penghasilan_ayah'   => 'Rp 5.000.000 - Rp 10.000.000',
            'hp_ayah'            => '081234567891',
            'alamat_ayah'        => 'Jl. Raya Banjaran No. 123',
        ];

        $this->db->table('data_ayah')->insert($ayahTsn);

        // Data Ibu
        $ibuTsn = [
            'id_pendaftar'      => $idPendaftarTsn,
            'nama_ibu'          => 'Ibu Siti Nurhaliza',
            'nik_ibu'           => '3273011234567892',
            'tempat_lahir_ibu'  => 'Bandung',
            'tanggal_lahir_ibu' => '1982-03-20',
            'status_ibu'        => 'Hidup',
            'pendidikan_ibu'    => 'SMA',
            'pekerjaan_ibu'     => 'Ibu Rumah Tangga',
            'penghasilan_ibu'   => '< Rp 1.000.000',
            'hp_ibu'            => '081234567892',
            'alamat_ibu'        => 'Jl. Raya Banjaran No. 123',
        ];

        $this->db->table('data_ibu')->insert($ibuTsn);

        // Data Wali (optional, null in this case)
        $waliTsn = [
            'id_pendaftar'      => $idPendaftarTsn,
            'nama_wali'         => null,
            'nik_wali'          => null,
            'tahun_lahir_wali'  => null,
            'pendidikan_wali'   => null,
            'pekerjaan_wali'    => null,
            'penghasilan_wali'  => null,
            'hp_wali'           => null,
        ];

        $this->db->table('data_wali')->insert($waliTsn);

        // Data Bansos
        $bansosTsn = [
            'id_pendaftar' => $idPendaftarTsn,
            'no_kks'       => null,
            'no_pkh'       => null,
            'no_kip'       => '123456789012',
        ];

        $this->db->table('bansos_pendaftar')->insert($bansosTsn);

        // Data Asal Sekolah
        $sekolahTsn = [
            'id_pendaftar'      => $idPendaftarTsn,
            'nama_asal_sekolah' => 'MI Al-Ikhlas Banjaran',
            'jenjang_sekolah'   => 'MI',
            'status_sekolah'    => 'Swasta',
            'npsn'              => '10234567',
            'lokasi_sekolah'    => 'Banjaran, Bandung',
            'asal_jenjang'      => 'Ibtidaiyyah',
        ];

        $this->db->table('asal_sekolah')->insert($sekolahTsn);

        // Sample data for Muallimin
        $pendaftarMua = [
            'nomor_pendaftaran' => 'M2026-001',
            'jalur_pendaftaran' => 'MUALLIMIN',
            'nisn'              => '0059876543',
            'nik'               => '3273019876543210',
            'nama_lengkap'      => 'Fatimah Zahra',
            'jenis_kelamin'     => 'P',
            'tempat_lahir'      => 'Bandung',
            'tanggal_lahir'     => '2009-08-20',
            'status_keluarga'   => 'Anak Kandung',
            'anak_ke'           => 2,
            'jumlah_saudara'    => 3,
            'hobi'              => 'Menulis',
            'cita_cita'         => 'Guru',
            'pernah_paud'       => true,
            'pernah_tk'         => true,
            'kebutuhan_disabilitas' => 'Tidak Ada',
            'imunisasi'         => 'Lengkap',
            'no_hp'             => '082345678901',
            'ukuran_baju'       => 'S',
            'prestasi'          => 'Juara 2 Lomba Karya Tulis Ilmiah',
        ];

        $this->db->table('pendaftar')->insert($pendaftarMua);
        $idPendaftarMua = $this->db->insertID();

        // Alamat for Muallimin
        $alamatMua = [
            'id_pendaftar'         => $idPendaftarMua,
            'nomor_kk'             => '3273019876543210',
            'jenis_tempat_tinggal' => 'Rumah Orang Tua',
            'alamat'               => 'Jl. Pesantren No. 45',
            'desa'                 => 'Banjaran Wetan',
            'kecamatan'            => 'Banjaran',
            'kabupaten'            => 'Bandung',
            'provinsi'             => 'Jawa Barat',
            'kode_pos'             => '40377',
            'jarak_ke_sekolah'     => '1 Km',
            'waktu_tempuh'         => '5 Menit',
            'transportasi'         => 'Jalan Kaki',
            'email'                => 'fatimah.zahra@example.com',
            'media_sosial'         => '@fatimazhz',
        ];

        $this->db->table('alamat_pendaftar')->insert($alamatMua);

        // Data Ayah Muallimin
        $ayahMua = [
            'id_pendaftar'       => $idPendaftarMua,
            'nama_ayah'          => 'H. Abdullah Rahman',
            'nik_ayah'           => '3273019876543211',
            'tempat_lahir_ayah'  => 'Bandung',
            'tanggal_lahir_ayah' => '1975-05-10',
            'status_ayah'        => 'Hidup',
            'pendidikan_ayah'    => 'S2',
            'pekerjaan_ayah'     => 'PNS',
            'penghasilan_ayah'   => 'Rp 10.000.000 - Rp 20.000.000',
            'hp_ayah'            => '082345678902',
            'alamat_ayah'        => 'Jl. Pesantren No. 45',
        ];

        $this->db->table('data_ayah')->insert($ayahMua);

        // Data Ibu Muallimin
        $ibuMua = [
            'id_pendaftar'      => $idPendaftarMua,
            'nama_ibu'          => 'Hj. Khadijah Aminah',
            'nik_ibu'           => '3273019876543212',
            'tempat_lahir_ibu'  => 'Bandung',
            'tanggal_lahir_ibu' => '1978-07-25',
            'status_ibu'        => 'Hidup',
            'pendidikan_ibu'    => 'S1',
            'pekerjaan_ibu'     => 'Guru',
            'penghasilan_ibu'   => 'Rp 3.000.000 - Rp 5.000.000',
            'hp_ibu'            => '082345678903',
            'alamat_ibu'        => 'Jl. Pesantren No. 45',
        ];

        $this->db->table('data_ibu')->insert($ibuMua);

        // Data Wali Muallimin (optional)
        $waliMua = [
            'id_pendaftar'      => $idPendaftarMua,
            'nama_wali'         => null,
            'nik_wali'          => null,
            'tahun_lahir_wali'  => null,
            'pendidikan_wali'   => null,
            'pekerjaan_wali'    => null,
            'penghasilan_wali'  => null,
            'hp_wali'           => null,
        ];

        $this->db->table('data_wali')->insert($waliMua);

        // Data Bansos Muallimin
        $bansosMua = [
            'id_pendaftar' => $idPendaftarMua,
            'no_kks'       => '987654321098',
            'no_pkh'       => null,
            'no_kip'       => null,
        ];

        $this->db->table('bansos_pendaftar')->insert($bansosMua);

        // Data Asal Sekolah Muallimin
        $sekolahMua = [
            'id_pendaftar'      => $idPendaftarMua,
            'nama_asal_sekolah' => 'MTs Persis 31 Banjaran',
            'jenjang_sekolah'   => 'MTs',
            'status_sekolah'    => 'Swasta',
            'npsn'              => '20345678',
            'lokasi_sekolah'    => 'Banjaran, Bandung',
            'asal_jenjang'      => 'Tsanawiyyah',
        ];

        $this->db->table('asal_sekolah')->insert($sekolahMua);

        echo "Sample pendaftar data seeded successfully!\n";
        echo "- Tsanawiyyah: Ahmad Fauzi (T2026-001)\n";
        echo "- Muallimin: Fatimah Zahra (M2026-001)\n";
    }
}
