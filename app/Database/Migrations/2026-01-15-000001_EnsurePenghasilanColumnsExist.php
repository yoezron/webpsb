<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class EnsurePenghasilanColumnsExist extends Migration
{
    public function up()
    {
        // Check and add penghasilan_ayah column if it doesn't exist
        if (!$this->db->fieldExists('penghasilan_ayah', 'data_ayah')) {
            $fields = [
                'penghasilan_ayah' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'pekerjaan_ayah',
                ],
            ];
            $this->forge->addColumn('data_ayah', $fields);
        }

        // Check and add penghasilan_ibu column if it doesn't exist
        if (!$this->db->fieldExists('penghasilan_ibu', 'data_ibu')) {
            $fields = [
                'penghasilan_ibu' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'pekerjaan_ibu',
                ],
            ];
            $this->forge->addColumn('data_ibu', $fields);
        }

        // Check and add penghasilan_wali column if it doesn't exist
        if (!$this->db->fieldExists('penghasilan_wali', 'data_wali')) {
            $fields = [
                'penghasilan_wali' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '50',
                    'null'       => true,
                    'after'      => 'pekerjaan_wali',
                ],
            ];
            $this->forge->addColumn('data_wali', $fields);
        }
    }

    public function down()
    {
        // Remove columns if they exist
        if ($this->db->fieldExists('penghasilan_ayah', 'data_ayah')) {
            $this->forge->dropColumn('data_ayah', 'penghasilan_ayah');
        }

        if ($this->db->fieldExists('penghasilan_ibu', 'data_ibu')) {
            $this->forge->dropColumn('data_ibu', 'penghasilan_ibu');
        }

        if ($this->db->fieldExists('penghasilan_wali', 'data_wali')) {
            $this->forge->dropColumn('data_wali', 'penghasilan_wali');
        }
    }
}
