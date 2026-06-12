<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAkademikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ta = [
            ['code' => '20241', 'name' => 'GANJIL 2024/2025', 'start_date' => '2024-09-01', 'end_date' => '2025-02-28', 'status' => false],
            ['code' => '20242', 'name' => 'GENAP 2024/2025', 'start_date' => '2025-03-01', 'end_date' => '2025-08-31', 'status' => false],
            ['code' => '20251', 'name' => 'GANJIL 2025/2026', 'start_date' => '2025-09-01', 'end_date' => '2026-02-28', 'status' => false],
            ['code' => '20252', 'name' => 'GENAP 2025/2026', 'start_date' => '2026-03-01', 'end_date' => '2026-08-31', 'status' => true],
        ];

        DB::table('academic_years')->insert($ta);
    }
}
