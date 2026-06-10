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
            ['code' => '20231', 'name' => 'GANJIL 2023/2024', 'status' => false],
            ['code' => '20232', 'name' => 'GENAP 2023/2024', 'status' => false],
            ['code' => '20241', 'name' => 'GANJIL 2024/2025', 'status' => true],
        ];

        DB::table('academic_years')->insert($ta);
    }
}
