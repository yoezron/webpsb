<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePengumumanBalasanTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_balasan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_pengumuman' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID balasan parent untuk reply admin',
            ],
            'nama_pengirim' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => false,
            ],
            'email_pengirim' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'isi_balasan' => [
                'type' => 'TEXT',
                'null' => false,
            ],
            'is_admin_reply' => [
                'type'    => 'BOOLEAN',
                'default' => false,
                'null'    => false,
                'comment' => 'True jika balasan dari admin',
            ],
            'id_admin' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'comment'    => 'ID admin jika balasan dari admin',
            ],
            'is_approved' => [
                'type'    => 'BOOLEAN',
                'default' => true,
                'null'    => false,
                'comment' => 'Status moderasi balasan',
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

        $this->forge->addKey('id_balasan', true);
        $this->forge->addKey('id_pengumuman');
        $this->forge->addKey('parent_id');
        $this->forge->addKey('is_admin_reply');
        $this->forge->addKey('created_at');
        $this->forge->addForeignKey('id_pengumuman', 'pengumuman', 'id_pengumuman', 'CASCADE', 'CASCADE');

        $this->forge->createTable('pengumuman_balasan', true);
    }

    public function down()
    {
        $this->forge->dropTable('pengumuman_balasan', true);
    }
}
