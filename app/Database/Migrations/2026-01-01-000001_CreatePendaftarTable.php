<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePendaftarTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_pendaftar' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nomor_pendaftaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'jalur_pendaftaran' => [
                'type'       => 'ENUM',
                'constraint' => ['TSANAWIYYAH', 'MUALLIMIN'],
                'null'       => false,
            ],
            
            // Data Diri
            'nisn' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'nik' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => false,
            ],
            'jenis_kelamin' => [
                'type'       => 'ENUM',
                'constraint' => ['L', 'P'],
                'null'       => false,
            ],
            'tempat_lahir' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'tanggal_lahir' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status_keluarga' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'anak_ke' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
            ],
            'jumlah_saudara' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'unsigned'   => true,
                'null'       => true,
            ],
            'hobi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'cita_cita' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'pernah_paud' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
            'pernah_tk' => [
                'type'       => 'BOOLEAN',
                'default'    => false,
                'null'       => false,
            ],
            'kebutuhan_disabilitas' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'imunisasi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'no_hp' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'ukuran_baju' => [
                'type'       => 'VARCHAR',
                'constraint' => '10',
                'null'       => true,
            ],
            'prestasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            
            // Timestamps
            'tanggal_daftar' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
            'deleted_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id_pendaftar', true);
        $this->forge->addKey('nomor_pendaftaran');
        $this->forge->addKey('jalur_pendaftaran');
        $this->forge->addKey('tanggal_daftar');
        $this->forge->addKey('nama_lengkap');
        
        $this->forge->createTable('pendaftar', true);
    }

    public function down()
    {
        $this->forge->dropTable('pendaftar', true);
    }
}
