<?php

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\TahunAkademik;
use App\Models\KelasKuliah;
use App\Models\KelasMahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->fakultas = Fakultas::create([
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $this->prodi = Prodi::create([
        'id_fakultas' => $this->fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $this->userDosen = User::create([
        'name' => 'Dosen User',
        'email' => 'dosen@example.com',
        'password' => bcrypt('password123'),
    ]);

    $this->dosen = Dosen::create([
        'id_user' => $this->userDosen->id,
        'nidn' => '1234567890',
        'nama' => 'DOSEN TEST',
    ]);

    $this->userMhs = User::create([
        'name' => 'Mhs User',
        'email' => 'mhs@example.com',
        'password' => bcrypt('password123'),
    ]);

    $this->mahasiswa = Mahasiswa::create([
        'id_user' => $this->userMhs->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $this->prodi->id,
        'id_dosen_pa' => $this->dosen->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);

    $this->mataKuliah = MataKuliah::create([
        'id_prodi' => $this->prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);

    $this->tahunAkademik = TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);

    $this->kelasKuliah = KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $this->mataKuliah->id,
        'id_ta' => $this->tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);
});

test('can get all kelas mahasiswa', function () {
    KelasMahasiswa::create([
        'id_mahasiswa' => $this->mahasiswa->id,
        'id_kelas' => $this->kelasKuliah->id,
        'nilai_akhir' => 85.5,
        'nilai_huruf' => 'A',
    ]);

    $response = $this->getJson('/api/kelas-mahasiswas');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id_mahasiswa' => $this->mahasiswa->id,
            'id_kelas' => $this->kelasKuliah->id,
            'nilai_akhir' => 85.5,
            'nilai_huruf' => 'A',
        ]);
});

test('can create kelas mahasiswa', function () {
    $response = $this->postJson('/api/kelas-mahasiswas', [
        'id_mahasiswa' => $this->mahasiswa->id,
        'id_kelas' => $this->kelasKuliah->id,
        'nilai_akhir' => 90.0,
        'nilai_huruf' => 'a',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'id_mahasiswa' => $this->mahasiswa->id,
            'id_kelas' => $this->kelasKuliah->id,
            'nilai_akhir' => 90.0,
            'nilai_huruf' => 'A',
        ]);

    $this->assertDatabaseHas('kelas_mahasiswas', [
        'id_mahasiswa' => $this->mahasiswa->id,
        'id_kelas' => $this->kelasKuliah->id,
        'nilai_akhir' => 90.0,
        'nilai_huruf' => 'A',
    ]);
});

test('validation errors when creating kelas mahasiswa', function () {
    $response = $this->postJson('/api/kelas-mahasiswas', [
        'id_mahasiswa' => 9999,
        'id_kelas' => 9999,
        'nilai_akhir' => 150, // invalid max
        'nilai_huruf' => 'AB', // invalid length
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id_mahasiswa', 'id_kelas', 'nilai_akhir', 'nilai_huruf']);
});

test('can show a kelas mahasiswa', function () {
    $km = KelasMahasiswa::create([
        'id_mahasiswa' => $this->mahasiswa->id,
        'id_kelas' => $this->kelasKuliah->id,
        'nilai_akhir' => 85.5,
        'nilai_huruf' => 'A',
    ]);

    $response = $this->getJson("/api/kelas-mahasiswas/{$km->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id_mahasiswa' => $this->mahasiswa->id,
            'id_kelas' => $this->kelasKuliah->id,
            'nilai_akhir' => 85.5,
            'nilai_huruf' => 'A',
        ]);
});

test('returns 404 when showing non-existing kelas mahasiswa', function () {
    $response = $this->getJson('/api/kelas-mahasiswas/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'kelas mahasiswa tidak ditemukan',
        ]);
});

test('can update kelas mahasiswa', function () {
    $km = KelasMahasiswa::create([
        'id_mahasiswa' => $this->mahasiswa->id,
        'id_kelas' => $this->kelasKuliah->id,
        'nilai_akhir' => 85.5,
        'nilai_huruf' => 'A',
    ]);

    $response = $this->putJson("/api/kelas-mahasiswas/{$km->id}", [
        'id_mahasiswa' => $this->mahasiswa->id,
        'id_kelas' => $this->kelasKuliah->id,
        'nilai_akhir' => 78.0,
        'nilai_huruf' => 'b',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id_mahasiswa' => $this->mahasiswa->id,
            'id_kelas' => $this->kelasKuliah->id,
            'nilai_akhir' => 78.0,
            'nilai_huruf' => 'B',
        ]);
});

test('can delete kelas mahasiswa', function () {
    $km = KelasMahasiswa::create([
        'id_mahasiswa' => $this->mahasiswa->id,
        'id_kelas' => $this->kelasKuliah->id,
        'nilai_akhir' => 85.5,
        'nilai_huruf' => 'A',
    ]);

    $response = $this->deleteJson("/api/kelas-mahasiswas/{$km->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'kelas mahasiswa berhasil dihapus',
        ]);

    $this->assertSoftDeleted('kelas_mahasiswas', [
        'id' => $km->id,
    ]);
});
