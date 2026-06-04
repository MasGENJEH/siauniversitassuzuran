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
        $users = User::where('email', 'like', 'mhs.%')->orderBy('id')->get();
        $prodis = Prodi::all();
        $dosens = Dosen::all();

        foreach ($users as $user) {
            $prodi = $prodis->random();
            $dosen = $dosens->random();

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
