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
        $day = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT'];
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
                    'course_id' => $mk->id,
                    'academic_year_id' => $tas->random()->id,
                    'class_code' => KelasKuliahHelper::generateUniqueKodeKelas($mk->code),
                    'class_name' => 'KELAS '.$letter,
                    'day' => $day[array_rand($day)],
                    'start_time' => $startTime->format('H:i:00'),
                    'end_time' => $endTime->format('H:i:00'),
                    'room' => 'RUANG '.mt_rand(101, 305),
                ]);
            }
        }
    }
}
