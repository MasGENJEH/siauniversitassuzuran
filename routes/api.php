<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenPengampuController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KelasKuliahController;
use App\Http\Controllers\KelasMahasiswaController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Auth Routes
Route::post('/login', [AuthController::class, 'tokenLogin']);

// Authenticated Routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/profile/update', [AuthController::class, 'updateProfile']);
    Route::get('dosens/{dosen}/kelas-kuliah-aktif', [DosenController::class, 'kelasKuliahAktif']);
    Route::get('dosens/{dosen}/mahasiswa-bimbingan', [DosenController::class, 'mahasiswaBimbingan']);

    // --- Read operations (accessible to all authenticated users: admin, dosen, mahasiswa) ---
    Route::get('fakultas', [FakultasController::class, 'index']);
    Route::get('fakultas/{fakultas}', [FakultasController::class, 'show']);

    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);

    Route::get('dosens', [DosenController::class, 'index']);
    Route::get('dosens/{dosen}', [DosenController::class, 'show']);

    Route::get('mahasiswas', [MahasiswaController::class, 'index']);
    Route::get('mahasiswas/{mahasiswa}', [MahasiswaController::class, 'show']);

    Route::get('prodis', [ProdiController::class, 'index']);
    Route::get('prodis/{prodi}', [ProdiController::class, 'show']);

    Route::get('mata-kuliahs', [MataKuliahController::class, 'index']);
    Route::get('mata-kuliahs/{mata_kuliah}', [MataKuliahController::class, 'show']);

    Route::get('tahun-akademiks', [TahunAkademikController::class, 'index']);
    Route::get('tahun-akademiks/{tahun_akademik}', [TahunAkademikController::class, 'show']);

    Route::get('kelas-kuliahs', [KelasKuliahController::class, 'index']);
    Route::get('kelas-kuliahs/{kelas_kuliah}', [KelasKuliahController::class, 'show']);

    Route::get('dosen-pengampus', [DosenPengampuController::class, 'index']);
    Route::get('dosen-pengampus/{dosen_pengampu}', [DosenPengampuController::class, 'show']);

    Route::get('kelas-mahasiswas', [KelasMahasiswaController::class, 'index']);
    Route::get('kelas-mahasiswas/{kelas_mahasiswa}', [KelasMahasiswaController::class, 'show']);
    Route::post('kelas-mahasiswas', [KelasMahasiswaController::class, 'store']);

    // --- Admin-only CRUD Write operations ---
    Route::middleware(['role:admin'])->group(function () {
        Route::post('fakultas', [FakultasController::class, 'store']);
        Route::put('fakultas/{fakulta}', [FakultasController::class, 'update']);
        Route::delete('fakultas/{fakulta}', [FakultasController::class, 'destroy']);

        Route::post('users', [UserController::class, 'store']);
        Route::put('users/{user}', [UserController::class, 'update']);
        Route::delete('users/{user}', [UserController::class, 'destroy']);

        Route::post('dosens', [DosenController::class, 'store']);
        Route::put('dosens/{dosen}', [DosenController::class, 'update']);
        Route::delete('dosens/{dosen}', [DosenController::class, 'destroy']);

        Route::post('mahasiswas', [MahasiswaController::class, 'store']);
        Route::put('mahasiswas/{mahasiswa}', [MahasiswaController::class, 'update']);
        Route::delete('mahasiswas/{mahasiswa}', [MahasiswaController::class, 'destroy']);

        Route::post('prodis', [ProdiController::class, 'store']);
        Route::put('prodis/{prodi}', [ProdiController::class, 'update']);
        Route::delete('prodis/{prodi}', [ProdiController::class, 'destroy']);

        Route::post('mata-kuliahs', [MataKuliahController::class, 'store']);
        Route::put('mata-kuliahs/{mata_kuliah}', [MataKuliahController::class, 'update']);
        Route::delete('mata-kuliahs/{mata_kuliah}', [MataKuliahController::class, 'destroy']);

        Route::post('tahun-akademiks', [TahunAkademikController::class, 'store']);
        Route::put('tahun-akademiks/{tahun_akademik}', [TahunAkademikController::class, 'update']);
        Route::delete('tahun-akademiks/{tahun_akademik}', [TahunAkademikController::class, 'destroy']);

        Route::post('kelas-kuliahs', [KelasKuliahController::class, 'store']);
        Route::put('kelas-kuliahs/{kelas_kuliah}', [KelasKuliahController::class, 'update']);
        Route::delete('kelas-kuliahs/{kelas_kuliah}', [KelasKuliahController::class, 'destroy']);

        Route::post('dosen-pengampus', [DosenPengampuController::class, 'store']);
        Route::put('dosen-pengampus/{dosen_pengampu}', [DosenPengampuController::class, 'update']);
        Route::delete('dosen-pengampus/{dosen_pengampu}', [DosenPengampuController::class, 'destroy']);

        Route::delete('kelas-mahasiswas/{kelas_mahasiswa}', [KelasMahasiswaController::class, 'destroy']);
    });

    // --- Admin OR Dosen Write operations (specifically updating grades) ---
    Route::middleware(['role:admin|dosen'])->group(function () {
        Route::put('kelas-mahasiswas/{kelas_mahasiswa}', [KelasMahasiswaController::class, 'update']);
    });
});
