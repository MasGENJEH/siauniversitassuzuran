<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $study_programs = [
            // FT (faculty_id: 1)
            ['faculty_id' => 1, 'code' => 'IF', 'nim_prefix' => '411', 'name' => 'TEKNIK INFORMATIKA', 'jenjang' => 'S1'],
            ['faculty_id' => 1, 'code' => 'EL', 'nim_prefix' => '412', 'name' => 'TEKNIK ELEKTRO', 'jenjang' => 'S1'],
            ['faculty_id' => 1, 'code' => 'SI', 'nim_prefix' => '413', 'name' => 'TEKNIK SIPIL', 'jenjang' => 'S1'],

            // FEB (faculty_id: 2)
            ['faculty_id' => 2, 'code' => 'AKT', 'nim_prefix' => '421', 'name' => 'AKUNTANSI', 'jenjang' => 'S1'],
            ['faculty_id' => 2, 'code' => 'MNJ', 'nim_prefix' => '422', 'name' => 'MANAJEMEN', 'jenjang' => 'S1'],

            // FH (faculty_id: 3)
            ['faculty_id' => 3, 'code' => 'IH', 'nim_prefix' => '431', 'name' => 'ILMU HUKUM', 'jenjang' => 'S1'],
            ['faculty_id' => 3, 'code' => 'HK', 'nim_prefix' => '432', 'name' => 'HUKUM KELUARGA', 'jenjang' => 'S1'],

            // FIB (faculty_id: 4)
            ['faculty_id' => 4, 'code' => 'SIND', 'nim_prefix' => '441', 'name' => 'SASTRA INDONESIA', 'jenjang' => 'S1'],
            ['faculty_id' => 4, 'code' => 'SING', 'nim_prefix' => '442', 'name' => 'SASTRA INGGRIS', 'jenjang' => 'S1'],

            // FK (faculty_id: 5)
            ['faculty_id' => 5, 'code' => 'PD', 'nim_prefix' => '451', 'name' => 'PENDIDIKAN DOKTER', 'jenjang' => 'S1'],
            ['faculty_id' => 5, 'code' => 'FAR', 'nim_prefix' => '452', 'name' => 'FARMASI', 'jenjang' => 'S1'],
        ];

        DB::table('study_programs')->insert($study_programs);
    }
}
