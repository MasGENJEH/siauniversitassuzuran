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
            'Prof. Dr. Takiya Genji, M.T.',
            'Dr. Eng. Tamao Serizawa, M.T.',
            'Shun Izaki, M.Kom.',
            'Dr. Takashi Makise, M.Cs.',
            'Prof. Dr. Ken Katagiri, M.Si.',
            'Chuta Tamura, M.T.',
            'Dr. Hideto Bando, M.Pd.',
            'Tokio Tatsukawa, M.Kom.',
            'Shoji Tsutsumoto, M.Cs.',
            'Dr. Megumi Hayashida, Ph.D.',
            'Prof. Dr. Naruto Uzumaki, M.A.',
            'Dr. Sasuke Uchiha, M.T.',
            'Sakura Haruno, M.Si.',
            'Prof. Dr. Kakashi Hatake, M.Eng.',
            'Jiraiya, M.Cs.',
            'Dr. Tsunade Senju, M.T.',
            'Orochimaru, M.Pd.',
            'Dr. Itachi Uchiha, M.H.',
            'Minato Namikaze, M.Si.',
            'Dr. Shikamaru Nara, M.Kom.',
            'Monkey D. Luffy, M.Cs.',
            'Dr. Roronoa Zoro, M.T.',
            'Prof. Dr. Nami, M.P.P.',
            'Usopp, M.I.P.',
            'Dr. Vinsmoke Sanji, M.B.A.',
            'Tony Tony Chopper, Ph.D.',
            'Dr. Nico Robin, Ph.D.',
            'Franky, M.Sc.',
            'Dr. Brook, LL.M.',
            'Jinbe, M.B.A.',
            'Eren Yeager, M.P.A.',
            'Dr. Mikasa Ackerman, S.H.',
            'Armin Arlert, M.A.',
            'Levi Ackerman, M.Hum.',
            'Erwin Smith, M.Sn.',
            'Dr. Hange Zoë, M.B.A.',
            'Prof. Dr. Jean Kirstein, M.Eng.',
            'Dr. Sasha Blouse, M.A.',
            'Connie Springer, Ph.D.',
            'Dr. Reiner Braun, M.Pd.',
            'SpongeBob SquarePants, M.Hum.',
            'Patrick Star, M.Pd.',
            'Dr. Squidward Tentacles, M.A.',
            'Mr. Krabs, M.H.',
            'Plankton, M.B.A.',
            'Dr. Sandy Cheeks, M.T.',
            'Gary the Snail, M.Si.',
            'Dr. Mrs. Puff, M.Sc.',
            'Pearl Krabs, M.T.',
            'Dr. Larry the Lobster, M.Sn.',
        ];

        // 50 Real Mahasiswa Names
        $mahasiswaNames = [
            // DB, Bleach, JJK, DS, MHA (50)
            'Goku', 'Vegeta', 'Gohan', 'Piccolo', 'Krillin', 'Trunks', 'Bulma', 'Frieza', 'Cell', 'Majin Buu',
            'Ichigo Kurosaki', 'Rukia Kuchiki', 'Orihime Inoue', 'Uryu Ishida', 'Renji Abarai', 'Sosuke Aizen', 'Byakuya Kuchiki', 'Toshiro Hitsugaya', 'Kenpachi Zaraki', 'Kisuke Urahara',
            'Yuji Itadori', 'Megumi Fushiguro', 'Nobara Kugisaki', 'Satoru Gojo', 'Kento Nanami', 'Maki Zenin', 'Toge Inumaki', 'Panda', 'Suguru Geto', 'Ryomen Sukuna',
            'Tanjiro Kamado', 'Nezuko Kamado', 'Zenitsu Agatsuma', 'Inosuke Hashibira', 'Giyu Tomioka', 'Shinobu Kocho', 'Kyojuro Rengoku', 'Tengen Uzui', 'Muichiro Tokito', 'Mitsuri Kanroji',
            'Izuku Midoriya', 'Katsuki Bakugo', 'Shoto Todoroki', 'Ochaco Uraraka', 'Tenya Iida', 'Tsuyu Asui', 'Eijiro Kirishima', 'Momo Yaoyorozu', 'Fumikage Tokoyami', 'Denki Kaminari',
            // Naruto, OP, AoT, Tokyo Ghoul, HxH (50)
            'Hinata Hyuga', 'Neji Hyuga', 'Rock Lee', 'Tenten', 'Gaara', 'Temari', 'Kankuro', 'Kiba Inuzuka', 'Shino Aburame', 'Choji Akimichi',
            'Trafalgar Law', 'Portgas D. Ace', 'Sabo', 'Boa Hancock', 'Dracule Mihawk', 'Shanks', 'Buggy', 'Crocodile', 'Donquixote Doflamingo', 'Charlotte Katakuri',
            'Historia Reiss', 'Annie Leonhart', 'Zeke Yeager', 'Pieck Finger', 'Porco Galliard', 'Bertholdt Hoover', 'Ymir', 'Petra Ral', 'Oluo Bozado', 'Floch Forster',
            'Ken Kaneki', 'Touka Kirishima', 'Hideyoshi Nagachika', 'Rize Kamishiro', 'Shu Tsukiyama', 'Koutarou Amon', 'Akira Mado', 'Juuzou Suzuya', 'Eto Yoshimura', 'Kisho Arima',
            'Gon Freecss', 'Killua Zoldyck', 'Kurapika', 'Leorio Paradinight', 'Hisoka Morow', 'Illumi Zoldyck', 'Chrollo Lucilfer', 'Meruem', 'Neferpitou', 'Isaac Netero',
            // FMA, Death Note, Fairy Tail, Black Clover, SAO (50)
            'Edward Elric', 'Alphonse Elric', 'Winry Rockbell', 'Roy Mustang', 'Riza Hawkeye', 'Maes Hughes', 'Alex Louis Armstrong', 'Ling Yao', 'Lan Fan', 'Envy',
            'Light Yagami', 'L Lawliet', 'Misa Amane', 'Ryuk', 'Rem', 'Near', 'Mello', 'Teru Mikami', 'Kiyomi Takada', 'Soichiro Yagami',
            'Natsu Dragneel', 'Lucy Heartfilia', 'Happy', 'Gray Fullbuster', 'Erza Scarlet', 'Wendy Marvell', 'Gajeel Redfox', 'Juvia Lockser', 'Mirajane Strauss', 'Laxus Dreyar',
            'Asta', 'Yuno', 'Noelle Silva', 'Yami Sukehiro', 'Magna Swing', 'Luck Voltia', 'Finral Roulacase', 'Vanessa Enoteca', 'Charmy Pappitson', 'Gordon Agrippa',
            'Kirito', 'Asuna', 'Leafa', 'Sinon', 'Lisbeth', 'Silica', 'Klein', 'Agil', 'Yui', 'Alice Zuberg',
            // Gintama, Jojo, Haikyuu, Kuroko, OPM (50)
            'Gintoki Sakata', 'Shinpachi Shimura', 'Kagura', 'Tae Shimura', 'Kotaro Katsura', 'Toushirou Hijikata', 'Sougo Okita', 'Isao Kondo', 'Sagaru Yamazaki', 'Tatsuma Sakamoto',
            'Jonathan Joestar', 'Joseph Joestar', 'Jotaro Kujo', 'Josuke Higashikata', 'Giorno Giovanna', 'Jolyne Cujoh', 'Johnny Joestar', 'Josuke Gappy', 'Dio Brando', 'Yoshikage Kira',
            'Shoyo Hinata', 'Tobio Kageyama', 'Daichi Sawamura', 'Koushi Sugawara', 'Asahi Azumane', 'Yu Nishinoya', 'Ryunosuke Tanaka', 'Kei Tsukishima', 'Tadashi Yamaguchi', 'Toru Oikawa',
            'Tetsuya Kuroko', 'Taiga Kagami', 'Ryota Kise', 'Shintaro Midorima', 'Daiki Aomine', 'Atsushi Murasakibara', 'Seijuro Akashi', 'Satsuki Momoi', 'Teppei Kiyoshi', 'Junpei Hyuga',
            'Saitama', 'Genos', 'Tatsumaki', 'Bang', 'King', 'Zombieman', 'Garou', 'Mumen Rider', 'Sonic', 'Fubuki',
            // DC, Marvel, Star Wars, HP, LotR (50)
            'Bruce Wayne', 'Clark Kent', 'Diana Prince', 'Barry Allen', 'Hal Jordan', 'Arthur Curry', 'Victor Stone', 'Dick Grayson', 'Barbara Gordon', 'Wally West',
            'Peter Parker', 'Tony Stark', 'Steve Rogers', 'Thor Odinson', 'Bruce Banner', 'Natasha Romanoff', 'Clint Barton', 'Wanda Maximoff', 'Vision', 'Stephen Strange',
            'Luke Skywalker', 'Leia Organa', 'Han Solo', 'Chewbacca', 'Darth Vader', 'Obi-Wan Kenobi', 'Yoda', 'Boba Fett', 'Lando Calrissian', 'Emperor Palpatine',
            'Harry Potter', 'Hermione Granger', 'Ron Weasley', 'Albus Dumbledore', 'Severus Snape', 'Sirius Black', 'Remus Lupin', 'Rubeus Hagrid', 'Minerva McGonagall', 'Draco Malfoy',
            'Frodo Baggins', 'Samwise Gamgee', 'Gandalf', 'Aragorn', 'Legolas', 'Gimli', 'Boromir', 'Gollum', 'Sauron', 'Elrond'
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
        for ($i = 1; $i <= 250; $i++) {
            $name = $mahasiswaNames[$i - 1];
            
            $users[] = [
                'name' => $name,
                'email' => 'mhs.' . $i . '@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '0857' . str_pad($i, 8, '0', STR_PAD_LEFT),
                'photo' => 'mhs_' . (($i - 1) % 50 + 1) . '.jpg',
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
