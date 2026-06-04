<?php

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\MataKuliah;
use App\Models\TahunAkademik;
use App\Models\KelasKuliah;
use App\Models\User;
use App\Models\Dosen;
use App\Models\DosenPengampu;
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

    $this->user = User::create([
        'name' => 'dosen user',
        'email' => 'dosen@test.com',
        'password' => bcrypt('password'),
    ]);

    $this->dosen = Dosen::create([
        'id_user' => $this->user->id,
        'nidn' => '1234567890',
        'nama' => 'JOHN DOE',
    ]);
});

test('can get all dosen pengampu', function () {
    DosenPengampu::create([
        'id_kelas' => $this->kelasKuliah->id,
        'id_dosen' => $this->dosen->id,
    ]);

    $response = $this->getJson('/api/dosen-pengampus');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id_kelas' => $this->kelasKuliah->id,
            'id_dosen' => $this->dosen->id,
        ]);
});

test('can create dosen pengampu', function () {
    $response = $this->postJson('/api/dosen-pengampus', [
        'id_kelas' => $this->kelasKuliah->id,
        'id_dosen' => $this->dosen->id,
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'id_kelas' => $this->kelasKuliah->id,
            'id_dosen' => $this->dosen->id,
        ]);

    $this->assertDatabaseHas('dosen_pengampus', [
        'id_kelas' => $this->kelasKuliah->id,
        'id_dosen' => $this->dosen->id,
    ]);
});

test('validation errors when creating dosen pengampu', function () {
    $response = $this->postJson('/api/dosen-pengampus', [
        'id_kelas' => 9999,
        'id_dosen' => 9999,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id_kelas', 'id_dosen']);
});

test('can show a dosen pengampu', function () {
    $pengampu = DosenPengampu::create([
        'id_kelas' => $this->kelasKuliah->id,
        'id_dosen' => $this->dosen->id,
    ]);

    $response = $this->getJson("/api/dosen-pengampus/{$pengampu->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id_kelas' => $this->kelasKuliah->id,
            'id_dosen' => $this->dosen->id,
        ]);
});

test('returns 404 when showing non-existing dosen pengampu', function () {
    $response = $this->getJson('/api/dosen-pengampus/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'dosen pengampu tidak ditemukan',
        ]);
});

test('can update dosen pengampu', function () {
    $pengampu = DosenPengampu::create([
        'id_kelas' => $this->kelasKuliah->id,
        'id_dosen' => $this->dosen->id,
    ]);

    $user2 = User::create([
        'name' => 'dosen user 2',
        'email' => 'dosen2@test.com',
        'password' => bcrypt('password'),
    ]);

    $dosen2 = Dosen::create([
        'id_user' => $user2->id,
        'nidn' => '0987654321',
        'nama' => 'JANE DOE',
    ]);

    $response = $this->putJson("/api/dosen-pengampus/{$pengampu->id}", [
        'id_kelas' => $this->kelasKuliah->id,
        'id_dosen' => $dosen2->id,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'id_kelas' => $this->kelasKuliah->id,
            'id_dosen' => $dosen2->id,
        ]);

    $this->assertDatabaseHas('dosen_pengampus', [
        'id' => $pengampu->id,
        'id_dosen' => $dosen2->id,
    ]);
});

test('can delete dosen pengampu', function () {
    $pengampu = DosenPengampu::create([
        'id_kelas' => $this->kelasKuliah->id,
        'id_dosen' => $this->dosen->id,
    ]);

    $response = $this->deleteJson("/api/dosen-pengampus/{$pengampu->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'dosen pengampu berhasil dihapus',
        ]);

    $this->assertSoftDeleted('dosen_pengampus', [
        'id' => $pengampu->id,
    ]);
});
