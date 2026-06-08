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

        // 50 Real Dosen Names
        $dosenNames = [
            'Prof. Dr. Ir. Budi Santoso, M.T.',
            'Dr. Eng. Eko Prasetyo, M.T.',
            'Rina Wijayanti, M.Kom.',
            'Dr. Adi Nugroho, M.Cs.',
            'Prof. Dr. Siti Aminah, M.Si.',
            'Bambang Hermawan, M.T.',
            'Dr. Dewi Lestari, M.Pd.',
            'Joko Susilo, M.Kom.',
            'Sri Wahyuni, M.Cs.',
            'Dr. Hendra Wijaya, Ph.D.',
            'Andi Pratama, M.T.',
            'Dr. Megawati, M.Si.',
            'Rian Hidayat, M.Kom.',
            'Prof. Dr. Yusuf Mansur, M.A.',
            'Kartika Putri, M.Cs.',
            'Dr. Slamet Riyadi, M.T.',
            'Tri Astuti, M.Pd.',
            'Dr. Agus Raharjo, M.H.',
            'Wulan Dari, M.Si.',
            'Dr. Budi Utomo, M.Kom.',
            'Fitriani, M.Cs.',
            'Dr. Ridwan Kamil, M.T.',
            'Prof. Dr. Anies Baswedan, M.P.P.',
            'Ganjar Pranowo, M.I.P.',
            'Dr. Prabowo Subianto, M.B.A.',
            'Sandiaga Uno, Ph.D.',
            'Dr. Sri Mulyani, Ph.D.',
            'Basuki Hadimuljono, M.Sc.',
            'Dr. Retno Marsudi, LL.M.',
            'Erick Thohir, M.B.A.',
            'Luhut Binsar Pandjaitan, M.P.A.',
            'Dr. Mahfud MD, S.H.',
            'Najwa Shihab, M.A.',
            'Dian Sastrowardoyo, M.Hum.',
            'Reza Rahadian, M.Sn.',
            'Dr. Chairul Tanjung, M.B.A.',
            'Prof. Dr. Habibie, M.Eng.',
            'Dr. Soekarno, M.A.',
            'Mohammad Hatta, Ph.D.',
            'Dr. Kartini, M.Pd.',
            'Cut Nyak Dien, M.Hum.',
            'Ki Hajar Dewantara, M.Pd.',
            'Dr. Tan Malaka, M.A.',
            'Gajah Mada, M.H.',
            'Hayam Wuruk, M.B.A.',
            'Dr. Sudirman, M.T.',
            'Diponegoro, M.Si.',
            'Dr. Pattimura, M.Sc.',
            'I Gusti Ngurah Rai, M.T.',
            'Dr. Raden Saleh, M.Sn.',
        ];

        // 50 Real Mahasiswa Names
        $mahasiswaNames = [
            'Ahmad Fauzi',
            'Bayu Saputra',
            'Citra Lestari',
            'Dian Pratama',
            'Eka Wahyuni',
            'Fajar Hidayat',
            'Gita Nurhaliza',
            'Hadi Wibowo',
            'Indah Permatasari',
            'Kevin Sanjaya',
            'Larasati Putri',
            'Muhammad Rizky',
            'Nadia Utami',
            'Octavianus Rio',
            'Putri Amelia',
            'Qori Asy-Syifa',
            'Rendy Pangalila',
            'Sari Indah',
            'Taufik Hidayat',
            'Ulfa Dwiyanti',
            'Vidi Aldiano',
            'Winda Lestari',
            'Yuda Pratama',
            'Zahra Aulia',
            'Aditya Wijaya',
            'Bella Safira',
            'Christian Sugiono',
            'Della Puspita',
            'Erwin Ramdani',
            'Febri Hariyadi',
            'Gunawan Dwi',
            'Hanif Sjahbandi',
            'Irfan Bachdim',
            'Jessica Mila',
            'Kim Kurniawan',
            'Luna Maya',
            'Marcus Gideon',
            'Nikita Willy',
            'Okto Maniani',
            'Pevita Pearce',
            'Rian Ardianto',
            'Syakir Sulaiman',
            'Tiara Andini',
            'Uston Nawawi',
            'Valeri Thomas',
            'Wawan Febrianto',
            'Yakob Sayuri',
            'Zulkifli Syukur',
            'Al Ghazali',
            'Bastian Steel',
        ];

        // Dosen Users
        for ($i = 1; $i <= 50; $i++) {
            $name = $dosenNames[$i - 1];
            $users[] = [
                'name' => $name,
                'email' => 'dosen.' . $i . '@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '0812' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'photo' => 'dosen_' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Mahasiswa Users
        for ($i = 1; $i <= 50; $i++) {
            $name = $mahasiswaNames[$i - 1];
            $users[] = [
                'name' => $name,
                'email' => 'mhs.' . $i . '@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '0857' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'photo' => 'mhs_' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // Admin User
        $users[] = [
            'name' => 'Admin Kampus',
            'email' => 'admin@kampus.ac.id',
            'password' => Hash::make('password123'),
            'phone' => '081234567890',
            'photo' => 'admin.jpg',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('users')->insert($users);
    }
}
