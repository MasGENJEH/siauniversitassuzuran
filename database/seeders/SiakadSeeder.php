<?php

namespace Database\Seeders;

use App\Helpers\MahasiswaHelper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SiakadSeeder extends Seeder
{
    public function run()
    {
        // 1. SEEDER TABEL USERS
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'test',
                'email' => 'admin@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567890',
                'photo' => 'admin.jpg',
            ],
            [
                'id' => 2,
                'name' => 'test1',
                'email' => 'budi.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567891',
                'photo' => 'budi.jpg',
            ],
            [
                'id' => 3,
                'name' => 'test2',
                'email' => 'rudi.mhs@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567892',
                'photo' => 'rudi.jpg',
            ],
            [
                'id' => 4,
                'name' => 'test4',
                'email' => 'anjay@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '051254244545',
                'photo' => 'anjay.jpg',
            ],
            [
                'id' => 5,
                'name' => 'PROF. DR. ENG. HENDRA WIJAYA, S.T., M.T.',
                'email' => 'hendra.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567805',
                'photo' => 'hendra.jpg',
            ],
            [
                'id' => 6,
                'name' => 'DR. RINA LESTARI, S.SI., M.IT.',
                'email' => 'rina.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567806',
                'photo' => 'rina.jpg',
            ],
            [
                'id' => 7,
                'name' => 'DIAN PRATIWI, S.KOM., M.KOM.',
                'email' => 'dian.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567807',
                'photo' => 'dian.jpg',
            ],
            [
                'id' => 8,
                'name' => 'IR. AGUS SUPRIATNA, M.SC., PH.D.',
                'email' => 'agus.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567808',
                'photo' => 'agus.jpg',
            ],
            [
                'id' => 9,
                'name' => 'MEGAWATI PUTRI, S.E., M.ACC.',
                'email' => 'megawati.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567809',
                'photo' => 'megawati.jpg',
            ],
            [
                'id' => 10,
                'name' => 'DR. BAMBANG UTOMO, S.H., M.H.',
                'email' => 'bambang.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567810',
                'photo' => 'bambang.jpg',
            ],
            [
                'id' => 11,
                'name' => 'EKO PRASETYO, S.T., M.ENG.',
                'email' => 'eko.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567811',
                'photo' => 'eko.jpg',
            ],
            [
                'id' => 12,
                'name' => 'FITRIANI NINGRUM, S.PSI., M.PSI.',
                'email' => 'fitriani.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567812',
                'photo' => 'fitriani.jpg',
            ],
            [
                'id' => 13,
                'name' => 'DR. HARI KUSUMA, M.ED.',
                'email' => 'hari.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567813',
                'photo' => 'hari.jpg',
            ],
            [
                'id' => 14,
                'name' => 'NURUL HIDAYAH, S.SI., M.STAT.',
                'email' => 'nurul.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567814',
                'photo' => 'nurul.jpg',
            ],
            [
                'id' => 15,
                'name' => 'PROF. DR. ANWAR JUNAEDI, S.E., M.SI.',
                'email' => 'anwar.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567815',
                'photo' => 'anwar.jpg',
            ],
            [
                'id' => 16,
                'name' => 'ANDIKA WIJAYA, S.SOS., M.A.',
                'email' => 'andika.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567816',
                'photo' => 'andika.jpg',
            ],
            [
                'id' => 17,
                'name' => 'DR. DRA. SRI REJEKI, M.HUM.',
                'email' => 'sri.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567817',
                'photo' => 'sri.jpg',
            ],
            [
                'id' => 18,
                'name' => 'REZA ALFIAN, S.FARM., M.SC., APT.',
                'email' => 'reza.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567818',
                'photo' => 'reza.jpg',
            ],
            [
                'id' => 19,
                'name' => 'DR. ENG. RYAN HIDAYAT, S.T., M.T.',
                'email' => 'ryan.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567819',
                'photo' => 'ryan.jpg',
            ],
            [
                'id' => 20,
                'name' => 'DEWI LESTARI, S.KG., M.D.SC.',
                'email' => 'dewi.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567820',
                'photo' => 'dewi.jpg',
            ],
            [
                'id' => 21,
                'name' => 'DR. ADITYA NUGRAHA, S.KED., M.KES.',
                'email' => 'aditya.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567821',
                'photo' => 'aditya.jpg',
            ],
            [
                'id' => 22,
                'name' => 'IR. ISKANDAR ZULKARNAEN, M.P.',
                'email' => 'iskandar.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567822',
                'photo' => 'iskandar.jpg',
            ],
            [
                'id' => 23,
                'name' => 'CHINTYA BELLA, S.I.KOM., M.I.KOM.',
                'email' => 'chintya.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567823',
                'photo' => 'chintya.jpg',
            ],
            [
                'id' => 24,
                'name' => 'TAUFIK HIDAYAT, S.PD., M.PD.',
                'email' => 'taufik.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567824',
                'photo' => 'taufik.jpg',
            ],
        ]);

        // 2. SEEDER TABEL FAKULTAS
        DB::table('fakultas')->insert([
            ['id' => 1, 'kode_fakultas' => 'FTI', 'nama_fakultas' => 'FAKULTAS TEKNOLOGI INFORMASI'],
            ['id' => 2, 'kode_fakultas' => 'FEB', 'nama_fakultas' => 'FAKULTAS EKONOMI DAN BISNIS'],
            ['id' => 3, 'kode_fakultas' => 'FTS', 'nama_fakultas' => 'FAKULTAS TEKNIK DAN SIPIL'],
            ['id' => 4, 'kode_fakultas' => 'FIKOM', 'nama_fakultas' => 'FAKULTAS ILMU KOMUNIKASI'],
            ['id' => 5, 'kode_fakultas' => 'FH', 'nama_fakultas' => 'FAKULTAS HUKUM'],
            ['id' => 6, 'kode_fakultas' => 'FPSI', 'nama_fakultas' => 'FAKULTAS PSIKOLOGI'],
            ['id' => 7, 'kode_fakultas' => 'FK', 'nama_fakultas' => 'FAKULTAS KEDOKTERAN'],
            ['id' => 8, 'kode_fakultas' => 'FKG', 'nama_fakultas' => 'FAKULTAS KEDOKTERAN GIGI'],
            ['id' => 9, 'kode_fakultas' => 'FKM', 'nama_fakultas' => 'FAKULTAS KESEHATAN MASYARAKAT'],
            ['id' => 10, 'kode_fakultas' => 'FF', 'nama_fakultas' => 'FAKULTAS FARMASI'],
            ['id' => 11, 'kode_fakultas' => 'FAD', 'nama_fakultas' => 'FAKULTAS ARSITEKTUR DAN DESAIN'],
            ['id' => 12, 'kode_fakultas' => 'FISIP', 'nama_fakultas' => 'FAKULTAS ILMU SOSIAL DAN ILMU POLITIK'],
            ['id' => 13, 'kode_fakultas' => 'FIB', 'nama_fakultas' => 'FAKULTAS ILMU BUDAYA'],
            ['id' => 14, 'kode_fakultas' => 'FMIPA', 'nama_fakultas' => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM'],
            ['id' => 15, 'kode_fakultas' => 'FAPERTA', 'nama_fakultas' => 'FAKULTAS PERTANIAN'],
            ['id' => 16, 'kode_fakultas' => 'FKIP', 'nama_fakultas' => 'FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN'],
        ]);

        // 3. SEEDER TABEL TAHUN AKADEMIK
        DB::table('tahun_akademiks')->insert([
            ['id' => 1, 'kode_ta' => '20241', 'nama_ta' => 'GANJIL 2024/2025', 'status' => false],
            ['id' => 2, 'kode_ta' => '20242', 'nama_ta' => 'GENAP 2024/2025', 'status' => false],
            ['id' => 3, 'kode_ta' => '20251', 'nama_ta' => 'GANJIL 2025/2026', 'status' => true], // Semester Aktif saat ini
        ]);

        // 4. SEEDER TABEL DOSEN
        DB::table('dosens')->insert([
            ['id' => 1, 'id_user' => 5, 'nidn' => '0402058101', 'nama' => 'PROF. DR. ENG. HENDRA WIJAYA, S.T., M.T.'],
            ['id' => 2, 'id_user' => 6, 'nidn' => '0415098302', 'nama' => 'DR. RINA LESTARI, S.SI., M.IT.'],
            ['id' => 3, 'id_user' => 7, 'nidn' => '0422118703', 'nama' => 'DIAN PRATIWI, S.KOM., M.KOM.'],
            ['id' => 4, 'id_user' => 8, 'nidn' => '0408047601', 'nama' => 'IR. AGUS SUPRIATNA, M.SC., PH.D.'],
            ['id' => 5, 'id_user' => 9, 'nidn' => '0419018804', 'nama' => 'MEGAWATI PUTRI, S.E., M.ACC.'],
            ['id' => 6, 'id_user' => 10, 'nidn' => '0430078202', 'nama' => 'DR. BAMBANG UTOMO, S.H., M.H.'],
            ['id' => 10, 'id_user' => 11, 'nidn' => '0414068901', 'nama' => 'EKO PRASETYO, S.T., M.ENG.'],
            ['id' => 11, 'id_user' => 12, 'nidn' => '0427128403', 'nama' => 'FITRIANI NINGRUM, S.PSI., M.PSI.'],
            ['id' => 12, 'id_user' => 13, 'nidn' => '0405037905', 'nama' => 'DR. HARI KUSUMA, M.ED.'],
            ['id' => 13, 'id_user' => 14, 'nidn' => '0418108602', 'nama' => 'NURUL HIDAYAH, S.SI., M.STAT.'],
            ['id' => 14, 'id_user' => 15, 'nidn' => '0423028001', 'nama' => 'PROF. DR. ANWAR JUNAEDI, S.E., M.SI.'],
            ['id' => 15, 'id_user' => 16, 'nidn' => '0409088503', 'nama' => 'ANDIKA WIJAYA, S.SOS., M.A.'],
            ['id' => 16, 'id_user' => 17, 'nidn' => '0412127702', 'nama' => 'DR. DRA. SRI REJEKI, M.HUM.'],
            ['id' => 17, 'id_user' => 18, 'nidn' => '0426059101', 'nama' => 'REZA ALFIAN, S.FARM., M.SC., APT.'],
            ['id' => 18, 'id_user' => 19, 'nidn' => '0401108304', 'nama' => 'DR. ENG. RYAN HIDAYAT, S.T., M.T.'],
            ['id' => 19, 'id_user' => 20, 'nidn' => '0416048802', 'nama' => 'DEWI LESTARI, S.KG., M.D.SC.'],
            ['id' => 20, 'id_user' => 21, 'nidn' => '0428098201', 'nama' => 'DR. ADITYA NUGRAHA, S.KED., M.KES.'],
            ['id' => 21, 'id_user' => 22, 'nidn' => '0407077503', 'nama' => 'IR. ISKANDAR ZULKARNAEN, M.P.'],
            ['id' => 22, 'id_user' => 23, 'nidn' => '0420038904', 'nama' => 'CHINTYA BELLA, S.I.KOM., M.I.KOM.'],
            ['id' => 23, 'id_user' => 24, 'nidn' => '0411068601', 'nama' => 'TAUFIK HIDAYAT, S.PD., M.PD.'],
        ]);

        // 5. SEEDER TABEL PRODI
        DB::table('prodis')->insert([
            ['id' => 1, 'id_fakultas' => 1, 'kode_prodi' => 'TI', 'prefix_nim' => '415',  'nama_prodi' => 'TEKNIK INFORMATIKA', 'jenjang' => 'S1'],
            ['id' => 2, 'id_fakultas' => 1, 'kode_prodi' => 'SI', 'prefix_nim' => '418', 'nama_prodi' => 'SISTEM INFORMASI', 'jenjang' => 'S1'],
            ['id' => 3, 'id_fakultas' => 2, 'kode_prodi' => 'AKT', 'prefix_nim' => '411', 'nama_prodi' => 'AKUNTANSI', 'jenjang' => 'S1'],
        ]);

        // 6. SEEDER TABEL MAHASISWA
        DB::table('mahasiswas')->insert([
            [
                'id' => 1,
                'id_user' => 3,
                'nim' => MahasiswaHelper::generateUniqueNim('SI'),
                'nama' => 'RUDI HERMAWAN',
                'id_prodi' => 2, // Sistem Informasi
                'id_dosen_pa' => 1,
                'tahun_masuk' => 2022,
                'status_mahasiswa' => 'AKTIF',
            ],
            [
                'id' => 2,
                'id_user' => 3,
                'nim' => MahasiswaHelper::generateUniqueNim('SI'),
                'nama' => 'ANISA RAHMAWATI',
                'id_prodi' => 2,
                'id_dosen_pa' => 1,
                'tahun_masuk' => 2022,
                'status_mahasiswa' => 'AKTIF',
            ],
            [
                'id' => 3,
                'id_user' => 3,
                'nim' => MahasiswaHelper::generateUniqueNim('TI'),
                'nama' => 'GUNTUR WIBOWO',
                'id_prodi' => 1, // Teknik Informatika
                'id_dosen_pa' => 2,
                'tahun_masuk' => 2023,
                'status_mahasiswa' => 'AKTIF',
            ],
        ]);

        // 7. SEEDER TABEL MATA KULIAH
        DB::table('mata_kuliahs')->insert([
            ['id' => 1, 'id_prodi' => 2, 'kode_mk' => 'SI301', 'nama_mk' => 'DESAIN BASIS DATA', 'sks' => 3, 'semester_plot' => 3],
            ['id' => 2, 'id_prodi' => 2, 'kode_mk' => 'SI402', 'nama_mk' => 'PEMROGRAMAN WEB LANJUT', 'sks' => 4, 'semester_plot' => 4],
            ['id' => 3, 'id_prodi' => 1, 'kode_mk' => 'IF202', 'nama_mk' => 'ALGORITMA & STRUKTUR DATA', 'sks' => 3, 'semester_plot' => 2],
        ]);

        // 8. SEEDER TABEL KELAS KULIAH
        DB::table('kelas_kuliahs')->insert([
            [
                'id' => 1,
                'id_mk' => 1, // Desain Basis Data
                'id_ta' => 3, // Ganjil 2025/2026
                'kode_kelas' => 'adsf3223r',
                'nama_kelas' => 'SI-45-A',
                'hari' => 'SENIN',
                'jam_mulai' => '08:00:00',
                'jam_selesai' => '10:30:00',
                'ruangan' => 'LAB KOMPUTER 3',
            ],
            [
                'id' => 2,
                'id_mk' => 2, // Pemrograman Web Lanjut
                'id_ta' => 3,
                'kode_kelas' => '34dfrg34efr',

                'nama_kelas' => 'SI-44-B',
                'hari' => 'RABU',
                'jam_mulai' => '13:00:00',
                'jam_selesai' => '16:20:00',
                'ruangan' => 'RUANG GEDUNG H.2',
            ],
            [
                'id' => 3,
                'id_mk' => 3, // Algoritma
                'id_ta' => 3,
                'kode_kelas' => '3456grtg4',

                'nama_kelas' => 'IF-46-A',
                'hari' => 'KAMIS',
                'jam_mulai' => '10:00:00',
                'jam_selesai' => '12:30:00',
                'ruangan' => 'RUANG GEDUNG E.4',
            ],
        ]);

        // 9. SEEDER TABEL PIVOT DOSEN PENGAMPU
        DB::table('dosen_pengampus')->insert([
            ['id' => 1, 'id_kelas' => 1, 'id_dosen' => 11], // Pak Budi mengajar kelas Desain Basis Data
            ['id' => 2, 'id_kelas' => 2, 'id_dosen' => 15], // Pak Zaki mengajar kelas Web Lanjut
            ['id' => 3, 'id_kelas' => 3, 'id_dosen' => 17], // Bu Siti mengajar kelas Algoritma
        ]);

        // 10. SEEDER TABEL PIVOT KELAS MAHASISWA (KRS / KHS)
        DB::table('kelas_mahasiswas')->insert([
            [
                'id' => 1,
                'id_mahasiswa' => 1, // Rudi
                'id_kelas' => 1, // Ambil kelas Desain Basis Data
                'nilai_akhir' => 85.5,
                'nilai_huruf' => 'A',
            ],
            [
                'id' => 2,
                'id_mahasiswa' => 2, // Anisa
                'id_kelas' => 1, // Ambil kelas Desain Basis Data
                'nilai_akhir' => 78.0,
                'nilai_huruf' => 'B',
            ],
            [
                'id' => 3,
                'id_mahasiswa' => 3, // Guntur
                'id_kelas' => 3, // Ambil kelas Algoritma
                'nilai_akhir' => 90.0,
                'nilai_huruf' => 'A',
            ],
        ]);
    }
}
