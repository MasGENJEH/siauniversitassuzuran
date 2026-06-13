<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenPengampuController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KelasKuliahController;
use App\Http\Controllers\KelasMahasiswaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\CourseClassExamController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AbsensiController;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::post('/login', [AuthController::class, 'tokenLogin']);

// Authenticated Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('lecturers/{lecturer}/kelas-kuliah-aktif', [DosenController::class, 'kelasKuliahAktif']);
    Route::get('lecturers/{lecturer}/mahasiswa-bimbingan', [DosenController::class, 'mahasiswaBimbingan']);

    // --- Read operations (accessible to all authenticated users: admin, dosen, mahasiswa) ---
    Route::get('faculties', [FakultasController::class, 'index']);
    Route::get('faculties/{faculties}', [FakultasController::class, 'show']);

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);

    Route::get('lecturers', [DosenController::class, 'index']);
    Route::get('lecturers/{lecturer}', [DosenController::class, 'show']);

    Route::get('students', [MahasiswaController::class, 'index']);
    Route::get('students/{student}', [MahasiswaController::class, 'show']);

    Route::get('study-programs', [ProdiController::class, 'index']);
    Route::get('study-programs/{study_program}', [ProdiController::class, 'show']);

    Route::get('courses', [MataKuliahController::class, 'index']);
    Route::get('courses/{course}', [MataKuliahController::class, 'show']);

    Route::get('academic-years', [TahunAkademikController::class, 'index']);
    Route::get('academic-years/{academic_year}', [TahunAkademikController::class, 'show']);

    Route::get('course-classes', [KelasKuliahController::class, 'index']);
    Route::get('course-classes/{course_class}', [KelasKuliahController::class, 'show']);

    Route::get('class-instructors', [DosenPengampuController::class, 'index']);
    Route::get('class-instructors/{class_instructor}', [DosenPengampuController::class, 'show']);

    Route::get('enrollments', [KelasMahasiswaController::class, 'index']);
    Route::get('enrollments/{enrollment}', [KelasMahasiswaController::class, 'show']);
    Route::post('enrollments', [KelasMahasiswaController::class, 'store']);

    Route::get('absensis', [AbsensiController::class, 'index']);

    // --- Admin-only CRUD Write operations ---
    Route::middleware(['role:admin'])->group(function () {
        Route::post('faculties', [FakultasController::class, 'store']);
        Route::put('faculties/{faculty}', [FakultasController::class, 'update']);
        Route::delete('faculties/{faculty}', [FakultasController::class, 'destroy']);

        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);

        Route::post('lecturers', [DosenController::class, 'store']);
        Route::put('lecturers/{lecturer}', [DosenController::class, 'update']);
        Route::delete('lecturers/{lecturer}', [DosenController::class, 'destroy']);

        Route::post('students', [MahasiswaController::class, 'store']);
        Route::put('students/{student}', [MahasiswaController::class, 'update']);
        Route::delete('students/{student}', [MahasiswaController::class, 'destroy']);

        Route::post('study-programs', [ProdiController::class, 'store']);
        Route::put('study-programs/{study_program}', [ProdiController::class, 'update']);
        Route::delete('study-programs/{study_program}', [ProdiController::class, 'destroy']);

        Route::post('courses', [MataKuliahController::class, 'store']);
        Route::put('courses/{course}', [MataKuliahController::class, 'update']);
        Route::delete('courses/{course}', [MataKuliahController::class, 'destroy']);

        Route::post('academic-years', [TahunAkademikController::class, 'store']);
        Route::put('academic-years/{academic_year}', [TahunAkademikController::class, 'update']);
        Route::delete('academic-years/{academic_year}', [TahunAkademikController::class, 'destroy']);

        Route::post('course-classes', [KelasKuliahController::class, 'store']);
        Route::put('course-classes/{course_class}', [KelasKuliahController::class, 'update']);
        Route::delete('course-classes/{course_class}', [KelasKuliahController::class, 'destroy']);

        Route::post('class-instructors', [DosenPengampuController::class, 'store']);
        Route::put('class-instructors/{class_instructor}', [DosenPengampuController::class, 'update']);
        Route::delete('class-instructors/{class_instructor}', [DosenPengampuController::class, 'destroy']);

        Route::delete('enrollments/{enrollment}', [KelasMahasiswaController::class, 'destroy']);
    });

    // --- Admin OR Dosen Write operations (specifically updating grades and attendance) ---
    Route::middleware(['role:admin|dosen'])->group(function () {
        Route::put('enrollments/{enrollment}', [KelasMahasiswaController::class, 'update']);
        Route::post('/absensis', [AbsensiController::class, 'store']);
        Route::post('/absensis/activate', [AbsensiController::class, 'activateMeeting']);
        
        // Feature: Exams (Portal Dosen)
        Route::get('/exams', [CourseClassExamController::class, 'index']);
        Route::post('/exams', [CourseClassExamController::class, 'store']);
        Route::delete('/exams/{id}', [CourseClassExamController::class, 'destroy']);
    });
});
