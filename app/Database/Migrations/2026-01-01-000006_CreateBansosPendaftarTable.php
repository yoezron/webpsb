<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBansosPendaftarTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_bansos' => [
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
            'no_kks' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'no_pkh' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
                'null'       => true,
            ],
            'no_kip' => [
                'type'       => 'VARCHAR',
                'constraint' => '30',
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

        $this->forge->addKey('id_bansos', true);
        $this->forge->addKey('id_pendaftar');
        
        // Foreign Key
        $this->forge->addForeignKey('id_pendaftar', 'pendaftar', 'id_pendaftar', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('bansos_pendaftar', true);
    }

    public function down()
    {
        $this->forge->dropTable('bansos_pendaftar', true);
    }
}
