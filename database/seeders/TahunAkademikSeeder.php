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
            ['kode_ta' => '20231', 'nama_ta' => 'GANJIL 2023/2024', 'status' => false],
            ['kode_ta' => '20232', 'nama_ta' => 'GENAP 2023/2024', 'status' => false],
            ['kode_ta' => '20241', 'nama_ta' => 'GANJIL 2024/2025', 'status' => true],
        ];

        DB::table('tahun_akademiks')->insert($ta);
    }
}
