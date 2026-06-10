<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Helpers\MahasiswaHelper;
use Illuminate\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::query()->where('email', 'like', 'mhs.%')->orderBy('id')->get();
        $study_programs = Prodi::all();
        $lecturers = Dosen::all();

        // 250 students, 50 lecturers.
        // Assign exactly 5 students to each lecturer.
        $studentIndex = 0;
        $totalStudents = $users->count();

        foreach ($lecturers as $dosen) {
            for ($k = 0; $k < 5; $k++) {
                if ($studentIndex >= $totalStudents) break;

                $user = $users[$studentIndex++];
                $prodi = $study_programs->random();

                Mahasiswa::create([
                    'user_id' => $user->id,
                    'nim' => MahasiswaHelper::generateUniqueNim($prodi->code),
                    'name' => strtoupper($user->name),
                    'study_program_id' => $prodi->id,
                    'academic_advisor_id' => $dosen->id,
                    'enrollment_year' => mt_rand(2021, 2024),
                    'status' => 'AKTIF',
                ]);
            }
        }
    }
}
