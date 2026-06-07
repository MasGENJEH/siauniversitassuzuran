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
        $prodis = Prodi::all();
        $dosens = Dosen::all();

        // Partition 50 students into groups of size between 2 and 5
        $sizes = [];
        $sum = 0;
        $totalStudents = count($users); // typically 50

        while ($sum < $totalStudents) {
            $needed = $totalStudents - $sum;
            if ($needed >= 2 && $needed <= 5) {
                $sizes[] = $needed;
                $sum += $needed;
            } else {
                $val = mt_rand(2, min(5, $needed - 2));
                $sizes[] = $val;
                $sum += $val;
            }
        }

        // Shuffle lecturers to randomly assign guardian lecturers
        $shuffledDosens = $dosens->shuffle();

        $studentIndex = 0;
        foreach ($sizes as $dosenIndex => $size) {
            // Assign a unique guardian lecturer to this group of students
            $dosen = $shuffledDosens[$dosenIndex];

            for ($k = 0; $k < $size; $k++) {
                if ($studentIndex >= $totalStudents) break;

                $user = $users[$studentIndex++];
                $prodi = $prodis->random();

                Mahasiswa::create([
                    'id_user' => $user->id,
                    'nim' => MahasiswaHelper::generateUniqueNim($prodi->kode_prodi),
                    'nama' => strtoupper($user->name),
                    'id_prodi' => $prodi->id,
                    'id_dosen_pa' => $dosen->id,
                    'tahun_masuk' => mt_rand(2021, 2024),
                    'status_mahasiswa' => 'AKTIF',
                ]);
            }
        }
    }
}
