<?php

namespace Database\Seeders;

use App\Models\KelasKuliah;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Database\Seeder;

class KelasMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = Mahasiswa::all();
        $kelasKuliahs = KelasKuliah::all();
        $gradeLetters = ['A', 'B', 'C', 'D', 'E'];

        foreach ($students as $mahasiswa) {
            // Group all available classes by their course ID (course_id)
            $groupedByCourse = $kelasKuliahs->groupBy('course_id');

            // Randomly decide to enroll in 4 or 5 courses
            $count = mt_rand(15, 20);
            if ($groupedByCourse->count() < $count) {
                $count = $groupedByCourse->count();
            }

            // Pick unique courses
            $chosenCourseIds = $groupedByCourse->keys()->random($count);

            foreach ($chosenCourseIds as $courseId) {
                // Pick one random class of the selected course
                $kelas = $groupedByCourse->get($courseId)->random();

                $nilaiAkhir = mt_rand(500, 1000) / 10;

                if ($nilaiAkhir >= 80 && $nilaiAkhir <= 100) {
                    $nilaiHuruf = 'A';
                } elseif ($nilaiAkhir >= 70 && $nilaiAkhir < 80) {
                    $nilaiHuruf = 'B';
                } elseif ($nilaiAkhir >= 55 && $nilaiAkhir < 70) {
                    $nilaiHuruf = 'C';
                } elseif ($nilaiAkhir >= 40 && $nilaiAkhir < 55) {
                    $nilaiHuruf = 'D';
                } else {
                    $nilaiHuruf = 'E';
                }

                KelasMahasiswa::create([
                    'student_id' => $mahasiswa->id,
                    'course_class_id' => $kelas->id,
                    'final_score' => $nilaiAkhir,
                    'letter_grade' => $nilaiHuruf,
                ]);
            }
        }
    }
}
