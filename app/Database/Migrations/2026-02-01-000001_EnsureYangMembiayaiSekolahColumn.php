<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsureYangMembiayaiSekolahColumn extends Migration
{
    public function up()
    {
        if (!$this->db->fieldExists('yang_membiayai_sekolah', 'pendaftar')) {
            $this->forge->addColumn('pendaftar', [
                'yang_membiayai_sekolah' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'null'       => true,
                    'after'      => 'cita_cita',
                ],
            ]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('yang_membiayai_sekolah', 'pendaftar')) {
            $this->forge->dropColumn('pendaftar', 'yang_membiayai_sekolah');
        }
    }
}
