<?php

namespace Database\Seeders;

use App\Helpers\MataKuliahHelper;
use App\Models\MataKuliah;
use App\Models\Prodi;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $study_programs = Prodi::all();

        $subjects = [
            'DASAR-DASAR', 'PENGANTAR', 'LANJUTAN', 'PETA TEORI', 'METODOLOGI',
            'ANALISIS', 'KAPITA SELEKTA', 'SEMINAR', 'PRAKTIKUM', 'DESAIN',
        ];

        foreach ($study_programs as $prodi) {
            for ($j = 1; $j <= 2; $j++) {
                $subjectName = $subjects[array_rand($subjects)].' '.$prodi->name.' '.$j;

                MataKuliah::create([
                    'study_program_id' => $prodi->id,
                    'code' => MataKuliahHelper::generateUniqueKodeKelas($prodi->code),
                    'name' => $subjectName,
                    'sks' => mt_rand(2, 4),
                    'recommended_semester' => mt_rand(1, 8),
                ]);
            }
        }
    }
}
