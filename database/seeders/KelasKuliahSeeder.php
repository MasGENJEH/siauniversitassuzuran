<?php

namespace Database\Seeders;

use App\Helpers\KelasKuliahHelper;
use App\Models\KelasKuliah;
use App\Models\MataKuliah;
use App\Models\TahunAkademik;
use Carbon\Carbon;
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
        $startOptions = ['07:00', '08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];

        foreach ($mataKuliahs as $mk) {
            $letters = ['A', 'B', 'C'];
            foreach ($letters as $letter) {
                $sks = $mk->sks ?: 2;
                $durationMinutes = $mk->sks * 50; // contoh 1 SKS = 50 menit

                $earliestStart = Carbon::createFromTime(7, 0);
                $latestEnd = Carbon::createFromTime(21, 0);

                // Cari batas maksimal jam mulai agar jam selesai tidak lewat 21:00
                $latestStart = (clone $latestEnd)->subMinutes($durationMinutes);

                $randomMinute = rand(
                    0,
                    $earliestStart->diffInMinutes($latestStart)
                );

                $startTime = (clone $earliestStart)->addMinutes($randomMinute);
                $endTime = (clone $startTime)->addMinutes($durationMinutes);

                KelasKuliah::create([
                    'id_mk' => $mk->id,
                    'id_ta' => $tas->random()->id,
                    'kode_kelas' => KelasKuliahHelper::generateUniqueKodeKelas($mk->kode_mk),
                    'nama_kelas' => 'KELAS '.$letter,
                    'hari' => $hari[array_rand($hari)],
                    'jam_mulai' => $startTime->format('H:i:00'),
                    'jam_selesai' => $endTime->format('H:i:00'),
                    'ruangan' => 'RUANG '.mt_rand(101, 305),
                ]);
            }
        }
    }
}
