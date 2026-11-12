<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Menambahkan:
 *  - tabel `periode_pendaftaran` (periode / tahun ajaran PSB + format nomor peserta)
 *  - tabel `pengaturan` (key-value: warna tampilan, konten landing page, kontak)
 *  - kolom `pendaftar.id_periode` (untuk arsip per periode)
 *
 * Data pendaftar lama otomatis dimasukkan ke periode awal (2026/2027).
 */
class CreatePengaturanDanPeriode extends Migration
{
    public function up()
    {
        // ---------------------------------------------------------
        // 1. periode_pendaftaran
        // ---------------------------------------------------------
        if (!$this->db->tableExists('periode_pendaftaran')) {
            $this->forge->addField([
                'id_periode' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                'tahun_ajaran' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
                'nama_periode' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '150',
                    'null'       => true,
                ],
                'tanggal_buka' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'tanggal_tutup' => [
                    'type' => 'DATE',
                    'null' => true,
                ],
                'format_nomor' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                    'default'    => '{PREFIX}-{YY1}{YY2}{NOMOR}',
                ],
                'prefix_tsanawiyyah' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '10',
                    'default'    => 'T',
                ],
                'prefix_muallimin' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '10',
                    'default'    => 'M',
                ],
                'digit_nomor' => [
                    'type'       => 'TINYINT',
                    'constraint' => 2,
                    'unsigned'   => true,
                    'default'    => 4,
                ],
                'nomor_mulai' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'default'    => 1,
                ],
                'kuota_tsanawiyyah' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'kuota_muallimin' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ],
                'pendaftaran_dibuka' => [
                    'type'    => 'TINYINT',
                    'constraint' => 1,
                    'default' => 1,
                ],
                'status' => [
                    'type'       => 'ENUM',
                    'constraint' => ['aktif', 'arsip'],
                    'default'    => 'aktif',
                ],
                'catatan' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'diarsipkan_pada' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('id_periode', true);
            $this->forge->addKey('status');
            $this->forge->createTable('periode_pendaftaran', true);

            $now = date('Y-m-d H:i:s');
            $this->db->table('periode_pendaftaran')->insert([
                'tahun_ajaran'       => '2026/2027',
                'nama_periode'       => 'PSB Tahun Ajaran 2026/2027',
                'format_nomor'       => '{PREFIX}-{YY1}{YY2}{NOMOR}',
                'prefix_tsanawiyyah' => 'T',
                'prefix_muallimin'   => 'M',
                'digit_nomor'        => 4,
                'nomor_mulai'        => 1,
                'pendaftaran_dibuka' => 1,
                'status'             => 'aktif',
                'created_at'         => $now,
                'updated_at'         => $now,
            ]);
        }

        // ---------------------------------------------------------
        // 2. pengaturan (key-value)
        // ---------------------------------------------------------
        if (!$this->db->tableExists('pengaturan')) {
            $this->forge->addField([
                'kunci' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '100',
                ],
                'nilai' => [
                    'type' => 'TEXT',
                    'null' => true,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                ],
            ]);
            $this->forge->addKey('kunci', true);
            $this->forge->createTable('pengaturan', true);
        }

        // ---------------------------------------------------------
        // 3. pendaftar.id_periode
        // ---------------------------------------------------------
        if (!$this->db->fieldExists('id_periode', 'pendaftar')) {
            $this->forge->addColumn('pendaftar', [
                'id_periode' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id_pendaftar',
                ],
            ]);
            $this->db->query('ALTER TABLE `pendaftar` ADD INDEX `idx_pendaftar_periode` (`id_periode`)');
        }

        // Masukkan data lama ke periode aktif
        $aktif = $this->db->table('periode_pendaftaran')
            ->where('status', 'aktif')
            ->orderBy('id_periode', 'ASC')
            ->get()->getRowArray();

        if ($aktif) {
            $this->db->table('pendaftar')
                ->where('id_periode', null)
                ->update(['id_periode' => $aktif['id_periode']]);
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('id_periode', 'pendaftar')) {
            $this->forge->dropColumn('pendaftar', 'id_periode');
        }
        $this->forge->dropTable('pengaturan', true);
        $this->forge->dropTable('periode_pendaftaran', true);
    }
}
