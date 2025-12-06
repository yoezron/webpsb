<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDataAyahTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_ayah' => [
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
            'nama_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'nik_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'tempat_lahir_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'tanggal_lahir_ayah' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'status_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'pendidikan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'pekerjaan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'penghasilan_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true,
            ],
            'hp_ayah' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'alamat_ayah' => [
                'type' => 'TEXT',
                'null' => true,
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

        $this->forge->addKey('id_ayah', true);
        $this->forge->addKey('id_pendaftar');
        
        // Foreign Key
        $this->forge->addForeignKey('id_pendaftar', 'pendaftar', 'id_pendaftar', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('data_ayah', true);
    }

    public function down()
    {
        $this->forge->dropTable('data_ayah', true);
    }
}
