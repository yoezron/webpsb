<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        echo "==========================================\n";
        echo " DATABASE SEEDING - PSB PERSIS 31 BANJARAN\n";
        echo "==========================================\n\n";

        // Run Admin Seeder
        echo "[1/2] Seeding Admin Users...\n";
        $this->call('AdminSeeder');
        echo "\n";

        // Run Pendaftar Seeder
        echo "[2/2] Seeding Sample Pendaftar Data...\n";
        $this->call('PendaftarSeeder');
        echo "\n";

        echo "==========================================\n";
        echo " SEEDING COMPLETED SUCCESSFULLY!\n";
        echo "==========================================\n\n";

        echo "Default Admin Credentials:\n";
        echo "┌─────────────────┬──────────────┬─────────────┐\n";
        echo "│ Username        │ Password     │ Role        │\n";
        echo "├─────────────────┼──────────────┼─────────────┤\n";
        echo "│ admin           │ admin123     │ superadmin  │\n";
        echo "│ panitia_tsn     │ panitia123   │ tsanawiyyah │\n";
        echo "│ panitia_mua     │ panitia123   │ muallimin   │\n";
        echo "└─────────────────┴──────────────┴─────────────┘\n\n";

        echo "Sample Pendaftar:\n";
        echo "- T2026-001: Ahmad Fauzi (Tsanawiyyah)\n";
        echo "- M2026-001: Fatimah Zahra (Muallimin)\n\n";

        echo "⚠️  IMPORTANT: Change default passwords after first login!\n\n";
    }
}
