<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [];

        // 50 Users for Dosen
        for ($i = 1; $i <= 50; $i++) {
            $users[] = [
                'name' => 'DOSEN USER ' . $i,
                'email' => 'dosen.' . $i . '@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'photo' => 'dosen_' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // 50 Users for Mahasiswa
        for ($i = 1; $i <= 50; $i++) {
            $users[] = [
                'name' => 'MAHASISWA USER ' . $i,
                'email' => 'mhs.' . $i . '@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '0857' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'photo' => 'mhs_' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('users')->insert($users);
    }
}
