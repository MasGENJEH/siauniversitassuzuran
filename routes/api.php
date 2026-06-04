<?php

use App\Http\Controllers\DosenController;
use App\Http\Controllers\DosenPengampuController;
use App\Http\Controllers\FakultasController;
use App\Http\Controllers\KelasKuliahController;
use App\Http\Controllers\MahasiswaController;
use App\Http\Controllers\MataKuliahController;
use App\Http\Controllers\ProdiController;
use App\Http\Controllers\TahunAkademikController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\KelasMahasiswaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('fakultas', FakultasController::class);
Route::apiResource('users', UserController::class);
Route::apiResource('dosens', DosenController::class);
Route::apiResource('mahasiswas', MahasiswaController::class);
Route::apiResource('prodis', ProdiController::class);
Route::apiResource('mata-kuliahs', MataKuliahController::class);
Route::apiResource('tahun-akademiks', TahunAkademikController::class);
Route::apiResource('kelas-kuliahs', KelasKuliahController::class);
Route::apiResource('dosen-pengampus', DosenPengampuController::class);
Route::apiResource('kelas-mahasiswas', KelasMahasiswaController::class);
Route::get('dosens/{dosen}/kelas-kuliah-aktif', [DosenController::class, 'kelasKuliahAktif']);
