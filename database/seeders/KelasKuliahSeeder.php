<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\TahunAkademik;
use App\Models\KelasKuliah;
use App\Helpers\KelasKuliahHelper;
use Illuminate\Database\Seeder;

class KelasKuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mataKuliahs = MataKuliah::all();
        $tas = TahunAkademik::all();
        $hari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT'];

        foreach ($mataKuliahs as $mk) {
            $letters = ['A', 'B', 'C'];
            foreach ($letters as $letter) {
                KelasKuliah::create([
                    'id_mk' => $mk->id,
                    'id_ta' => $tas->random()->id,
                    'kode_kelas' => KelasKuliahHelper::generateUniqueKodeKelas($mk->kode_mk),
                    'nama_kelas' => 'KELAS ' . $letter,
                    'hari' => $hari[array_rand($hari)],
                    'jam_mulai' => '08:00:00',
                    'jam_selesai' => '09:40:00',
                    'ruangan' => 'RUANG ' . mt_rand(101, 305),
                ]);
            }
        }
    }
}
