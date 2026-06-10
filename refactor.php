<?php

$replacements = [
    // Tables
    "'fakultas'" => "'faculties'",
    "'prodis'" => "'study_programs'",
    "'tahun_akademiks'" => "'academic_years'",
    "'dosens'" => "'lecturers'",
    "'mahasiswas'" => "'students'",
    "'mata_kuliahs'" => "'courses'",
    "'kelas_kuliahs'" => "'course_classes'",
    "'dosen_pengampus'" => "'class_instructors'",
    "'kelas_mahasiswas'" => "'enrollments'",

    // URL Paths
    "fakultas" => "faculties",
    "prodis" => "study-programs",
    "tahun-akademiks" => "academic-years",
    "dosens" => "lecturers",
    "mahasiswas" => "students",
    "mata-kuliahs" => "courses",
    "kelas-kuliahs" => "course-classes",
    "dosen-pengampus" => "class-instructors",
    "kelas-mahasiswas" => "enrollments",

    // Route Parameters
    "{fakulta}" => "{faculty}",
    "{prodi}" => "{study_program}",
    "{tahun_akademik}" => "{academic_year}",
    "{dosen}" => "{lecturer}",
    "{mahasiswa}" => "{student}",
    "{mata_kuliah}" => "{course}",
    "{kelas_kuliah}" => "{course_class}",
    "{dosen_pengampu}" => "{class_instructor}",
    "{kelas_mahasiswa}" => "{enrollment}",

    // General Columns
    "kode_fakultas" => "code",
    "nama_fakultas" => "name",

    "id_fakultas" => "faculty_id",
    "kode_prodi" => "code",
    "nama_prodi" => "name",
    "prefix_nim" => "nim_prefix",

    "kode_ta" => "code",
    "nama_ta" => "name",

    "id_user" => "user_id",
    // nidn stays nidn
    "nama" => "name",
    "foto" => "photo",

    // nim stays nim
    "id_prodi" => "study_program_id",
    "id_dosen_pa" => "academic_advisor_id",
    "tahun_masuk" => "enrollment_year",
    "status_mahasiswa" => "status",

    "kode_mk" => "code",
    "nama_mk" => "name",
    "semester_plot" => "recommended_semester",

    "kode_kelas" => "class_code",
    "id_mk" => "course_id",
    "id_ta" => "academic_year_id",
    "nama_kelas" => "class_name",
    "hari" => "day",
    "jam_mulai" => "start_time",
    "jam_selesai" => "end_time",
    "ruangan" => "room",

    "id_kelas" => "course_class_id",
    "id_dosen" => "lecturer_id",

    "id_mahasiswa" => "student_id",
    "nilai_akhir" => "final_score",
    "nilai_huruf" => "letter_grade",

    // Some specific React variables
    "setFakultas" => "setFaculties",
    "setProdis" => "setStudyPrograms",
    "setTahunAkademiks" => "setAcademicYears",
    "setDosens" => "setLecturers",
    "setMahasiswas" => "setStudents",
    "setMataKuliahs" => "setCourses",
    "setKelasKuliahs" => "setCourseClasses",
    "setKelasMahasiswas" => "setEnrollments",
    "setDosenPengampus" => "setClassInstructors",

    "fetchFakultas" => "fetchFaculties",
    "fetchProdis" => "fetchStudyPrograms",
    "fetchTahunAkademiks" => "fetchAcademicYears",
    "fetchDosens" => "fetchLecturers",
    "fetchMahasiswas" => "fetchStudents",
    "fetchMataKuliahs" => "fetchCourses",
    "fetchKelasKuliahs" => "fetchCourseClasses",
    "fetchKelasMahasiswas" => "fetchEnrollments",

    // File references for photos
    "foto-dosen" => "photo-lecturer",
    "foto-mahasiswa" => "photo-student",
];

$directories = [
    __DIR__ . '/app/Http/Requests',
    __DIR__ . '/app/Http/Controllers',
    __DIR__ . '/app/Http/Resources',
    __DIR__ . '/app/Services',
    __DIR__ . '/routes',
    __DIR__ . '/resources/js/components',
    __DIR__ . '/database/seeders',
];

function processDirectory($dir, $replacements) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;
        $path = $dir . '/' . $file;
        if (is_dir($path)) {
            processDirectory($path, $replacements);
        } else {
            $ext = pathinfo($path, PATHINFO_EXTENSION);
            if (in_array($ext, ['php', 'jsx'])) {
                $content = file_get_contents($path);
                $newContent = strtr($content, $replacements);
                if ($content !== $newContent) {
                    file_put_contents($path, $newContent);
                    echo "Updated: $path\n";
                }
            }
        }
    }
}

foreach ($directories as $dir) {
    processDirectory($dir, $replacements);
}
echo "Done.\n";
