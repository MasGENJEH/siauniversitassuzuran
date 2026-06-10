<?php

namespace Database\Seeders;

use App\Models\KelasKuliah;
use App\Models\Dosen;
use App\Models\DosenPengampu;
use Illuminate\Database\Seeder;

class DosenPengampuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kelasKuliahs = KelasKuliah::all();
        $lecturers = Dosen::all();

        foreach ($kelasKuliahs as $kelas) {
            $count = 1;
            $chosenDosens = $lecturers->random($count);
 
            foreach ($chosenDosens as $dosen) {
                DosenPengampu::create([
                    'course_class_id' => $kelas->id,
                    'lecturer_id' => $dosen->id,
                ]);
            }
        }
    }
}
