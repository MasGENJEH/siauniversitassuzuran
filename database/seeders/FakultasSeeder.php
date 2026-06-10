<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakultasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faculties = [
            ['code' => 'FT', 'name' => 'FAKULTAS TEKNIK'],
            ['code' => 'FEB', 'name' => 'FAKULTAS EKONOMI DAN BISNIS'],
            ['code' => 'FH', 'name' => 'FAKULTAS HUKUM'],
            ['code' => 'FIB', 'name' => 'FAKULTAS ILMU BUDAYA'],
            ['code' => 'FK', 'name' => 'FAKULTAS KEDOKTERAN'],
        ];

        DB::table('faculties')->insert($faculties);
    }
}
