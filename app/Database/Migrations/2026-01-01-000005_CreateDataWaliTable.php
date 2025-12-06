<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDataWaliTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_wali' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pendaftar' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'nama_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'nik_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'tahun_lahir_wali' => [
                'type'       => 'YEAR',
                'constraint' => 4,
                'null'       => true,
            ],
            'pendidikan_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'pekerjaan_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'penghasilan_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'hp_wali' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id_wali', true);
        $this->forge->addKey('id_pendaftar');
        
        // Foreign Key
        $this->forge->addForeignKey('id_pendaftar', 'pendaftar', 'id_pendaftar', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('data_wali', true);
    }

    public function down()
    {
        $this->forge->dropTable('data_wali', true);
    }
}
