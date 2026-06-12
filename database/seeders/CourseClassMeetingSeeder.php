<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CourseClassMeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classes = DB::table('course_classes')->get();
        $academicYears = DB::table('academic_years')->get()->keyBy('id');
        $enrollments = DB::table('enrollments')->get()->groupBy('course_class_id');

        $dayMap = [
            'SENIN' => Carbon::MONDAY,
            'SELASA' => Carbon::TUESDAY,
            'RABU' => Carbon::WEDNESDAY,
            'KAMIS' => Carbon::THURSDAY,
            'JUMAT' => Carbon::FRIDAY,
            'SABTU' => Carbon::SATURDAY,
            'MINGGU' => Carbon::SUNDAY,
        ];

        foreach ($classes as $class) {
            $ay = $academicYears->get($class->academic_year_id);
            if (!$ay || !$ay->start_date || !$ay->end_date || !isset($dayMap[$class->day])) {
                continue;
            }

            $startDate = Carbon::parse($ay->start_date);
            $endDate = Carbon::parse($ay->end_date);
            $targetDay = $dayMap[$class->day];

            $currentDate = clone $startDate;
            // Majukan ke hari yang sesuai dengan jadwal kelas
            while ($currentDate->dayOfWeek !== $targetDay) {
                $currentDate->addDay();
            }

            $studentsInClass = $enrollments->get($class->id) ?? collect();

            while ($currentDate <= $endDate) {
                // Buat Meeting
                $meetingId = DB::table('course_class_meetings')->insertGetId([
                    'course_class_id' => $class->id,
                    'tanggal' => $currentDate->format('Y-m-d'),
                    'is_active' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Buat list absen kosong untuk tiap mahasiswa
                $absensiData = [];
                foreach ($studentsInClass as $enrollment) {
                    $absensiData[] = [
                        'course_class_meeting_id' => $meetingId,
                        'student_id' => $enrollment->student_id,
                        'status' => null, // Sesuai kesepakatan: dikosongkan (null)
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                if (!empty($absensiData)) {
                    DB::table('absensis')->insert($absensiData);
                }

                // Lanjut ke minggu depan
                $currentDate->addWeek();
            }
        }
    }
}
