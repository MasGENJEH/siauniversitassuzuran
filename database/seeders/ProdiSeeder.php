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
        $prodis = [
            // FT (id_fakultas: 1)
            ['id_fakultas' => 1, 'kode_prodi' => 'IF', 'prefix_nim' => '411', 'nama_prodi' => 'TEKNIK INFORMATIKA', 'jenjang' => 'S1'],
            ['id_fakultas' => 1, 'kode_prodi' => 'EL', 'prefix_nim' => '412', 'nama_prodi' => 'TEKNIK ELEKTRO', 'jenjang' => 'S1'],
            ['id_fakultas' => 1, 'kode_prodi' => 'SI', 'prefix_nim' => '413', 'nama_prodi' => 'TEKNIK SIPIL', 'jenjang' => 'S1'],

            // FEB (id_fakultas: 2)
            ['id_fakultas' => 2, 'kode_prodi' => 'AKT', 'prefix_nim' => '421', 'nama_prodi' => 'AKUNTANSI', 'jenjang' => 'S1'],
            ['id_fakultas' => 2, 'kode_prodi' => 'MNJ', 'prefix_nim' => '422', 'nama_prodi' => 'MANAJEMEN', 'jenjang' => 'S1'],

            // FH (id_fakultas: 3)
            ['id_fakultas' => 3, 'kode_prodi' => 'IH', 'prefix_nim' => '431', 'nama_prodi' => 'ILMU HUKUM', 'jenjang' => 'S1'],
            ['id_fakultas' => 3, 'kode_prodi' => 'HK', 'prefix_nim' => '432', 'nama_prodi' => 'HUKUM KELUARGA', 'jenjang' => 'S1'],

            // FIB (id_fakultas: 4)
            ['id_fakultas' => 4, 'kode_prodi' => 'SIND', 'prefix_nim' => '441', 'nama_prodi' => 'SASTRA INDONESIA', 'jenjang' => 'S1'],
            ['id_fakultas' => 4, 'kode_prodi' => 'SING', 'prefix_nim' => '442', 'nama_prodi' => 'SASTRA INGGRIS', 'jenjang' => 'S1'],

            // FK (id_fakultas: 5)
            ['id_fakultas' => 5, 'kode_prodi' => 'PD', 'prefix_nim' => '451', 'nama_prodi' => 'PENDIDIKAN DOKTER', 'jenjang' => 'S1'],
            ['id_fakultas' => 5, 'kode_prodi' => 'FAR', 'prefix_nim' => '452', 'nama_prodi' => 'FARMASI', 'jenjang' => 'S1'],
        ];

        DB::table('prodis')->insert($prodis);
    }
}
