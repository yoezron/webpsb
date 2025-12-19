<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHubunganWaliColumn extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('hubungan_wali', 'data_wali')) {
            $fields = [
                'hubungan_wali' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'nama_wali',
                ],
            ];

            $this->forge->addColumn('data_wali', $fields);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('hubungan_wali', 'data_wali')) {
            $this->forge->dropColumn('data_wali', 'hubungan_wali');
        }
    }
}
