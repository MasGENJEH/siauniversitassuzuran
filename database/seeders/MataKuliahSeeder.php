<?php

namespace Database\Seeders;

use App\Models\Prodi;
use App\Models\MataKuliah;
use App\Helpers\MataKuliahHelper;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $prodis = Prodi::all();

        $subjects = [
            'DASAR-DASAR', 'PENGANTAR', 'LANJUTAN', 'PETA TEORI', 'METODOLOGI',
            'ANALISIS', 'KAPITA SELEKTA', 'SEMINAR', 'PRAKTIKUM', 'DESAIN'
        ];

        foreach ($prodis as $prodi) {
            for ($j = 1; $j <= 5; $j++) {
                $subjectName = $subjects[array_rand($subjects)] . ' ' . $prodi->nama_prodi . ' ' . $j;
                
                MataKuliah::create([
                    'id_prodi' => $prodi->id,
                    'kode_mk' => MataKuliahHelper::generateUniqueKodeKelas($prodi->kode_prodi),
                    'nama_mk' => $subjectName,
                    'sks' => mt_rand(2, 4),
                    'semester_plot' => mt_rand(1, 8),
                ]);
            }
        }
    }
}
