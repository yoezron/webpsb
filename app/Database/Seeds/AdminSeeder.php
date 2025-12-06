<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'      => 'admin',
                'password_hash' => password_hash('admin123', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Super Administrator',
                'email'         => 'admin@persis31.com',
                'role_panitia'  => 'superadmin',
                'is_active'     => true,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'panitia_tsn',
                'password_hash' => password_hash('panitia123', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Panitia Tsanawiyyah',
                'email'         => 'tsn@persis31.com',
                'role_panitia'  => 'tsanawiyyah',
                'is_active'     => true,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'panitia_mua',
                'password_hash' => password_hash('panitia123', PASSWORD_DEFAULT),
                'nama_lengkap'  => 'Panitia Mu\'allimin',
                'email'         => 'mua@persis31.com',
                'role_panitia'  => 'muallimin',
                'is_active'     => true,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        // Insert data
        $this->db->table('admin_panitia')->insertBatch($data);

        echo "Admin users seeded successfully!\n";
        echo "Username: admin | Password: admin123\n";
        echo "Username: panitia_tsn | Password: panitia123\n";
        echo "Username: panitia_mua | Password: panitia123\n";
    }
}
