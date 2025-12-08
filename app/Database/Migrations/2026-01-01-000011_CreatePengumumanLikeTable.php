<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengumumanLikeTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_like' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pengumuman' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID pengumuman yang di-like (null jika like pada balasan)',
            ],
            'id_balasan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID balasan yang di-like (null jika like pada pengumuman)',
            ],
            'session_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '128',
                'null'       => false,
                'comment'    => 'Session ID untuk identifikasi user publik',
            ],
            'ip_address' => [
                'type'       => 'VARCHAR',
                'constraint' => '45',
                'null'       => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => new \CodeIgniter\Database\RawSql('CURRENT_TIMESTAMP'),
            ],
        ]);

        $this->forge->addKey('id_like', true);
        $this->forge->addKey('id_pengumuman');
        $this->forge->addKey('id_balasan');
        $this->forge->addKey('session_id');
        $this->forge->addForeignKey('id_pengumuman', 'pengumuman', 'id_pengumuman', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('id_balasan', 'pengumuman_balasan', 'id_balasan', 'CASCADE', 'CASCADE');

        $this->forge->createTable('pengumuman_like', true);
    }

    public function down()
    {
        $this->forge->dropTable('pengumuman_like', true);
    }
}
