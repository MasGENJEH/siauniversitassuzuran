<?php

namespace Database\Seeders;

use App\Models\Mahasiswa;
use App\Models\KelasKuliah;
use App\Models\KelasMahasiswa;
use Illuminate\Database\Seeder;

class KelasMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswas = Mahasiswa::all();
        $kelasKuliahs = KelasKuliah::all();
        $gradeLetters = ['A', 'B', 'C', 'D', 'E'];

        foreach ($mahasiswas as $mahasiswa) {
            $count = mt_rand(4, 6);
            $chosenClasses = $kelasKuliahs->random($count);

            foreach ($chosenClasses as $kelas) {
                $nilaiAkhir = mt_rand(500, 1000) / 10;
                $nilaiHuruf = $gradeLetters[array_rand($gradeLetters)];

                KelasMahasiswa::create([
                    'id_mahasiswa' => $mahasiswa->id,
                    'id_kelas' => $kelas->id,
                    'nilai_akhir' => $nilaiAkhir,
                    'nilai_huruf' => $nilaiHuruf,
                ]);
            }
        }
    }
}
