<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsPendaftaran extends Migration
{
    public function up()
    {
        // =====================================================
        // 1. Tabel pendaftar - Tambah 3 field
        // =====================================================
        $fields_pendaftar = [
            'yang_membiayai_sekolah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'cita_cita'
            ],
            'minat_bakat' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'prestasi'
            ],
            'kebutuhan_khusus' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'kebutuhan_disabilitas'
            ],
        ];

        // Add columns if they don't exist
        foreach ($fields_pendaftar as $field => $config) {
            if (!$this->db->fieldExists($field, 'pendaftar')) {
                $this->forge->addColumn('pendaftar', [$field => $config]);
            }
        }

        // =====================================================
        // 2. Tabel alamat_pendaftar - Tambah 3 field
        // =====================================================
        $fields_alamat = [
            'rt_rw' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
                'after'      => 'alamat'
            ],
            'nama_kepala_keluarga' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
                'after'      => 'nomor_kk'
            ],
            'tinggal_bersama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'after'      => 'kode_pos'
            ],
        ];

        // Add columns if they don't exist
        foreach ($fields_alamat as $field => $config) {
            if (!$this->db->fieldExists($field, 'alamat_pendaftar')) {
                $this->forge->addColumn('alamat_pendaftar', [$field => $config]);
            }
        }

        // =====================================================
        // 3. Tabel asal_sekolah - Tambah 5 field
        // =====================================================
        $fields_sekolah = [
            'alamat_sekolah' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'lokasi_sekolah'
            ],
            'tahun_lulus' => [
                'type'       => 'YEAR',
                'constraint' => 4,
                'null'       => true,
                'after'      => 'npsn'
            ],
            'rata_rata_rapor' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'tahun_lulus'
            ],
            'nilai_tka' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'after'      => 'rata_rata_rapor'
            ],
            'sekolah_md' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
                'null'       => true,
                'after'      => 'nilai_tka'
            ],
        ];

        // Add columns if they don't exist
        foreach ($fields_sekolah as $field => $config) {
            if (!$this->db->fieldExists($field, 'asal_sekolah')) {
                $this->forge->addColumn('asal_sekolah', [$field => $config]);
            }
        }

        // =====================================================
        // 4. Tabel data_wali - Tambah 2 field, Drop 1 field
        // =====================================================

        // Add tempat_lahir_wali if it doesn't exist
        if (!$this->db->fieldExists('tempat_lahir_wali', 'data_wali')) {
            $this->forge->addColumn('data_wali', [
                'tempat_lahir_wali' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'nik_wali'
                ]
            ]);
        }

        // Add tanggal_lahir_wali if it doesn't exist
        if (!$this->db->fieldExists('tanggal_lahir_wali', 'data_wali')) {
            $this->forge->addColumn('data_wali', [
                'tanggal_lahir_wali' => [
                    'type' => 'DATE',
                    'null' => true,
                    'after' => 'tempat_lahir_wali'
                ]
            ]);
        }

        // Drop old column tahun_lahir_wali (will be replaced by tanggal_lahir_wali)
        if ($this->db->fieldExists('tahun_lahir_wali', 'data_wali')) {
            $this->forge->dropColumn('data_wali', 'tahun_lahir_wali');
        }
    }

    public function down()
    {
        // Rollback: Drop added columns
        if ($this->db->fieldExists('yang_membiayai_sekolah', 'pendaftar')) {
            $this->forge->dropColumn('pendaftar', 'yang_membiayai_sekolah');
        }
        if ($this->db->fieldExists('minat_bakat', 'pendaftar')) {
            $this->forge->dropColumn('pendaftar', 'minat_bakat');
        }
        if ($this->db->fieldExists('kebutuhan_khusus', 'pendaftar')) {
            $this->forge->dropColumn('pendaftar', 'kebutuhan_khusus');
        }

        if ($this->db->fieldExists('rt_rw', 'alamat_pendaftar')) {
            $this->forge->dropColumn('alamat_pendaftar', 'rt_rw');
        }
        if ($this->db->fieldExists('nama_kepala_keluarga', 'alamat_pendaftar')) {
            $this->forge->dropColumn('alamat_pendaftar', 'nama_kepala_keluarga');
        }
        if ($this->db->fieldExists('tinggal_bersama', 'alamat_pendaftar')) {
            $this->forge->dropColumn('alamat_pendaftar', 'tinggal_bersama');
        }

        if ($this->db->fieldExists('alamat_sekolah', 'asal_sekolah')) {
            $this->forge->dropColumn('asal_sekolah', 'alamat_sekolah');
        }
        if ($this->db->fieldExists('tahun_lulus', 'asal_sekolah')) {
            $this->forge->dropColumn('asal_sekolah', 'tahun_lulus');
        }
        if ($this->db->fieldExists('rata_rata_rapor', 'asal_sekolah')) {
            $this->forge->dropColumn('asal_sekolah', 'rata_rata_rapor');
        }
        if ($this->db->fieldExists('nilai_tka', 'asal_sekolah')) {
            $this->forge->dropColumn('asal_sekolah', 'nilai_tka');
        }
        if ($this->db->fieldExists('sekolah_md', 'asal_sekolah')) {
            $this->forge->dropColumn('asal_sekolah', 'sekolah_md');
        }

        if ($this->db->fieldExists('tempat_lahir_wali', 'data_wali')) {
            $this->forge->dropColumn('data_wali', 'tempat_lahir_wali');
        }
        if ($this->db->fieldExists('tanggal_lahir_wali', 'data_wali')) {
            $this->forge->dropColumn('data_wali', 'tanggal_lahir_wali');
        }

        // Re-add tahun_lahir_wali
        if (!$this->db->fieldExists('tahun_lahir_wali', 'data_wali')) {
            $this->forge->addColumn('data_wali', [
                'tahun_lahir_wali' => [
                    'type'       => 'YEAR',
                    'constraint' => 4,
                    'null'       => true,
                ]
            ]);
        }
    }
}
