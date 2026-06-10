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
                'email' => 'day.dosen@kampus.ac.id',
                'password' => Hash::make('password123'),
                'phone' => '081234567813',
                'photo' => 'day.jpg',
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
        DB::table('faculties')->insert([
            ['id' => 1, 'code' => 'FTI', 'name' => 'FAKULTAS TEKNOLOGI INFORMASI'],
            ['id' => 2, 'code' => 'FEB', 'name' => 'FAKULTAS EKONOMI DAN BISNIS'],
            ['id' => 3, 'code' => 'FTS', 'name' => 'FAKULTAS TEKNIK DAN SIPIL'],
            ['id' => 4, 'code' => 'FIKOM', 'name' => 'FAKULTAS ILMU KOMUNIKASI'],
            ['id' => 5, 'code' => 'FH', 'name' => 'FAKULTAS HUKUM'],
            ['id' => 6, 'code' => 'FPSI', 'name' => 'FAKULTAS PSIKOLOGI'],
            ['id' => 7, 'code' => 'FK', 'name' => 'FAKULTAS KEDOKTERAN'],
            ['id' => 8, 'code' => 'FKG', 'name' => 'FAKULTAS KEDOKTERAN GIGI'],
            ['id' => 9, 'code' => 'FKM', 'name' => 'FAKULTAS KESEHATAN MASYARAKAT'],
            ['id' => 10, 'code' => 'FF', 'name' => 'FAKULTAS FARMASI'],
            ['id' => 11, 'code' => 'FAD', 'name' => 'FAKULTAS ARSITEKTUR DAN DESAIN'],
            ['id' => 12, 'code' => 'FISIP', 'name' => 'FAKULTAS ILMU SOSIAL DAN ILMU POLITIK'],
            ['id' => 13, 'code' => 'FIB', 'name' => 'FAKULTAS ILMU BUDAYA'],
            ['id' => 14, 'code' => 'FMIPA', 'name' => 'FAKULTAS MATEMATIKA DAN ILMU PENGETAHUAN ALAM'],
            ['id' => 15, 'code' => 'FAPERTA', 'name' => 'FAKULTAS PERTANIAN'],
            ['id' => 16, 'code' => 'FKIP', 'name' => 'FAKULTAS KEGURUAN DAN ILMU PENDIDIKAN'],
        ]);

        // 3. SEEDER TABEL TAHUN AKADEMIK
        DB::table('academic_years')->insert([
            ['id' => 1, 'code' => '20241', 'name' => 'GANJIL 2024/2025', 'status' => false],
            ['id' => 2, 'code' => '20242', 'name' => 'GENAP 2024/2025', 'status' => false],
            ['id' => 3, 'code' => '20251', 'name' => 'GANJIL 2025/2026', 'status' => true], // Semester Aktif saat ini
        ]);

        // 4. SEEDER TABEL DOSEN
        DB::table('lecturers')->insert([
            ['id' => 1, 'user_id' => 5, 'nidn' => '0402058101', 'name' => 'PROF. DR. ENG. HENDRA WIJAYA, S.T., M.T.'],
            ['id' => 2, 'user_id' => 6, 'nidn' => '0415098302', 'name' => 'DR. RINA LESTARI, S.SI., M.IT.'],
            ['id' => 3, 'user_id' => 7, 'nidn' => '0422118703', 'name' => 'DIAN PRATIWI, S.KOM., M.KOM.'],
            ['id' => 4, 'user_id' => 8, 'nidn' => '0408047601', 'name' => 'IR. AGUS SUPRIATNA, M.SC., PH.D.'],
            ['id' => 5, 'user_id' => 9, 'nidn' => '0419018804', 'name' => 'MEGAWATI PUTRI, S.E., M.ACC.'],
            ['id' => 6, 'user_id' => 10, 'nidn' => '0430078202', 'name' => 'DR. BAMBANG UTOMO, S.H., M.H.'],
            ['id' => 10, 'user_id' => 11, 'nidn' => '0414068901', 'name' => 'EKO PRASETYO, S.T., M.ENG.'],
            ['id' => 11, 'user_id' => 12, 'nidn' => '0427128403', 'name' => 'FITRIANI NINGRUM, S.PSI., M.PSI.'],
            ['id' => 12, 'user_id' => 13, 'nidn' => '0405037905', 'name' => 'DR. HARI KUSUMA, M.ED.'],
            ['id' => 13, 'user_id' => 14, 'nidn' => '0418108602', 'name' => 'NURUL HIDAYAH, S.SI., M.STAT.'],
            ['id' => 14, 'user_id' => 15, 'nidn' => '0423028001', 'name' => 'PROF. DR. ANWAR JUNAEDI, S.E., M.SI.'],
            ['id' => 15, 'user_id' => 16, 'nidn' => '0409088503', 'name' => 'ANDIKA WIJAYA, S.SOS., M.A.'],
            ['id' => 16, 'user_id' => 17, 'nidn' => '0412127702', 'name' => 'DR. DRA. SRI REJEKI, M.HUM.'],
            ['id' => 17, 'user_id' => 18, 'nidn' => '0426059101', 'name' => 'REZA ALFIAN, S.FARM., M.SC., APT.'],
            ['id' => 18, 'user_id' => 19, 'nidn' => '0401108304', 'name' => 'DR. ENG. RYAN HIDAYAT, S.T., M.T.'],
            ['id' => 19, 'user_id' => 20, 'nidn' => '0416048802', 'name' => 'DEWI LESTARI, S.KG., M.D.SC.'],
            ['id' => 20, 'user_id' => 21, 'nidn' => '0428098201', 'name' => 'DR. ADITYA NUGRAHA, S.KED., M.KES.'],
            ['id' => 21, 'user_id' => 22, 'nidn' => '0407077503', 'name' => 'IR. ISKANDAR ZULKARNAEN, M.P.'],
            ['id' => 22, 'user_id' => 23, 'nidn' => '0420038904', 'name' => 'CHINTYA BELLA, S.I.KOM., M.I.KOM.'],
            ['id' => 23, 'user_id' => 24, 'nidn' => '0411068601', 'name' => 'TAUFIK HIDAYAT, S.PD., M.PD.'],
        ]);

        // 5. SEEDER TABEL PRODI
        DB::table('study_programs')->insert([
            ['id' => 1, 'faculty_id' => 1, 'code' => 'TI', 'nim_prefix' => '415',  'name' => 'TEKNIK INFORMATIKA', 'jenjang' => 'S1'],
            ['id' => 2, 'faculty_id' => 1, 'code' => 'SI', 'nim_prefix' => '418', 'name' => 'SISTEM INFORMASI', 'jenjang' => 'S1'],
            ['id' => 3, 'faculty_id' => 2, 'code' => 'AKT', 'nim_prefix' => '411', 'name' => 'AKUNTANSI', 'jenjang' => 'S1'],
        ]);

        // 6. SEEDER TABEL MAHASISWA
        DB::table('students')->insert([
            [
                'id' => 1,
                'user_id' => 3,
                'nim' => MahasiswaHelper::generateUniqueNim('SI'),
                'name' => 'RUDI HERMAWAN',
                'study_program_id' => 2, // Sistem Informasi
                'academic_advisor_id' => 1,
                'enrollment_year' => 2022,
                'status' => 'AKTIF',
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'nim' => MahasiswaHelper::generateUniqueNim('SI'),
                'name' => 'ANISA RAHMAWATI',
                'study_program_id' => 2,
                'academic_advisor_id' => 1,
                'enrollment_year' => 2022,
                'status' => 'AKTIF',
            ],
            [
                'id' => 3,
                'user_id' => 3,
                'nim' => MahasiswaHelper::generateUniqueNim('TI'),
                'name' => 'GUNTUR WIBOWO',
                'study_program_id' => 1, // Teknik Informatika
                'academic_advisor_id' => 2,
                'enrollment_year' => 2023,
                'status' => 'AKTIF',
            ],
        ]);

        // 7. SEEDER TABEL MATA KULIAH
        DB::table('courses')->insert([
            ['id' => 1, 'study_program_id' => 2, 'code' => 'SI301', 'name' => 'DESAIN BASIS DATA', 'sks' => 3, 'recommended_semester' => 3],
            ['id' => 2, 'study_program_id' => 2, 'code' => 'SI402', 'name' => 'PEMROGRAMAN WEB LANJUT', 'sks' => 4, 'recommended_semester' => 4],
            ['id' => 3, 'study_program_id' => 1, 'code' => 'IF202', 'name' => 'ALGORITMA & STRUKTUR DATA', 'sks' => 3, 'recommended_semester' => 2],
        ]);

        // 8. SEEDER TABEL KELAS KULIAH
        $startOptions = ['07:00', '08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00'];
        $getTime = function($sks) use ($startOptions) {
            $durationMinutes = $sks * 50;
            $startStr = $startOptions[array_rand($startOptions)];
            $startTime = \Carbon\Carbon::createFromFormat('H:i', $startStr);
            $endTime = (clone $startTime)->addMinutes($durationMinutes);
            return [
                'start_time' => $startTime->format('H:i:00'),
                'end_time' => $endTime->format('H:i:00')
            ];
        };

        $t1 = $getTime(3); // Desain Basis Data (3 SKS)
        $t2 = $getTime(4); // Pemrograman Web Lanjut (4 SKS)
        $t3 = $getTime(3); // Algoritma (3 SKS)

        DB::table('course_classes')->insert([
            [
                'id' => 1,
                'course_id' => 1, // Desain Basis Data
                'academic_year_id' => 3, // Ganjil 2025/2026
                'class_code' => 'adsf3223r',
                'class_name' => 'SI-45-A',
                'day' => 'SENIN',
                'start_time' => $t1['start_time'],
                'end_time' => $t1['end_time'],
                'room' => 'LAB KOMPUTER 3',
            ],
            [
                'id' => 2,
                'course_id' => 2, // Pemrograman Web Lanjut
                'academic_year_id' => 3,
                'class_code' => '34dfrg34efr',
                'class_name' => 'SI-44-B',
                'day' => 'RABU',
                'start_time' => $t2['start_time'],
                'end_time' => $t2['end_time'],
                'room' => 'RUANG GEDUNG H.2',
            ],
            [
                'id' => 3,
                'course_id' => 3, // Algoritma
                'academic_year_id' => 3,
                'class_code' => '3456grtg4',
                'class_name' => 'IF-46-A',
                'day' => 'KAMIS',
                'start_time' => $t3['start_time'],
                'end_time' => $t3['end_time'],
                'room' => 'RUANG GEDUNG E.4',
            ],
        ]);

        // 9. SEEDER TABEL PIVOT DOSEN PENGAMPU
        DB::table('class_instructors')->insert([
            ['id' => 1, 'course_class_id' => 1, 'lecturer_id' => 11], // Pak Budi mengajar kelas Desain Basis Data
            ['id' => 2, 'course_class_id' => 2, 'lecturer_id' => 15], // Pak Zaki mengajar kelas Web Lanjut
            ['id' => 3, 'course_class_id' => 3, 'lecturer_id' => 17], // Bu Siti mengajar kelas Algoritma
        ]);

        // 10. SEEDER TABEL PIVOT KELAS MAHASISWA (KRS / KHS)
        DB::table('enrollments')->insert([
            [
                'id' => 1,
                'student_id' => 1, // Rudi
                'course_class_id' => 1, // Ambil kelas Desain Basis Data
                'final_score' => 85.5,
                'letter_grade' => 'A',
            ],
            [
                'id' => 2,
                'student_id' => 2, // Anisa
                'course_class_id' => 1, // Ambil kelas Desain Basis Data
                'final_score' => 78.0,
                'letter_grade' => 'B',
            ],
            [
                'id' => 3,
                'student_id' => 3, // Guntur
                'course_class_id' => 3, // Ambil kelas Algoritma
                'final_score' => 90.0,
                'letter_grade' => 'A',
            ],
        ]);
    }
}
