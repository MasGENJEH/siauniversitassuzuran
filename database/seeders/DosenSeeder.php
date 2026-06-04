<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::where('email', 'like', 'dosen.%')->orderBy('id')->get();
        $dosens = [];

        foreach ($users as $index => $user) {
            $i = $index + 1;
            $dosens[] = [
                'id_user' => $user->id,
                'nidn' => '04' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'nama' => strtoupper($user->name),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('dosens')->insert($dosens);
    }
}
