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
        $dosens = Dosen::all();

        foreach ($kelasKuliahs as $kelas) {
            $count = 1;
            $chosenDosens = $dosens->random($count);
 
            foreach ($chosenDosens as $dosen) {
                DosenPengampu::create([
                    'id_kelas' => $kelas->id,
                    'id_dosen' => $dosen->id,
                ]);
            }
        }
    }
}
