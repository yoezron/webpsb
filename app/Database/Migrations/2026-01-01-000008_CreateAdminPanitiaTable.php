<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAdminPanitiaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_admin' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'username' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
                'null'       => false,
            ],
            'password_hash' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => false,
            ],
            'nama_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true,
            ],
            'email' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'role_panitia' => [
                'type'       => 'ENUM',
                'constraint' => ['tsanawiyyah', 'muallimin', 'superadmin'],
                'null'       => false,
            ],
            'is_active' => [
                'type'    => 'BOOLEAN',
                'default' => true,
                'null'    => false,
            ],
            'last_login' => [
                'type' => 'TIMESTAMP',
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

        $this->forge->addKey('id_admin', true);
        // Note: username already has UNIQUE constraint which creates an index
        $this->forge->addKey('role_panitia');

        $this->forge->createTable('admin_panitia', true);
    }

    public function down()
    {
        $this->forge->dropTable('admin_panitia', true);
    }
}
