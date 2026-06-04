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
        $fakultas = [
            ['kode_fakultas' => 'FT', 'nama_fakultas' => 'FAKULTAS TEKNIK'],
            ['kode_fakultas' => 'FEB', 'nama_fakultas' => 'FAKULTAS EKONOMI DAN BISNIS'],
            ['kode_fakultas' => 'FH', 'nama_fakultas' => 'FAKULTAS HUKUM'],
            ['kode_fakultas' => 'FIB', 'nama_fakultas' => 'FAKULTAS ILMU BUDAYA'],
            ['kode_fakultas' => 'FK', 'nama_fakultas' => 'FAKULTAS KEDOKTERAN'],
        ];

        DB::table('fakultas')->insert($fakultas);
    }
}
