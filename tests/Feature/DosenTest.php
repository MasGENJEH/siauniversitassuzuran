<?php

use App\Models\User;
use App\Models\Dosen;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->user = User::create([
        'name' => 'Dosen User',
        'email' => 'dosen@example.com',
        'password' => bcrypt('password123'),
    ]);
});

test('can get all dosens', function () {
    Dosen::create([
        'id_user' => $this->user->id,
        'nidn' => '1234567890',
        'nama' => 'JOHN DOE',
    ]);

    $response = $this->getJson('/api/dosens');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nidn' => '1234567890',
            'nama' => 'JOHN DOE',
        ]);
});

test('can create dosen', function () {
    $response = $this->postJson('/api/dosens', [
        'id_user' => $this->user->id,
        'nidn' => '0987654321',
        'nama' => 'jane doe',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'nidn' => '0987654321',
            'nama' => 'JANE DOE',
        ]);

    $this->assertDatabaseHas('dosens', [
        'nidn' => '0987654321',
        'nama' => 'JANE DOE',
    ]);
});

test('validation errors when creating dosen', function () {
    $response = $this->postJson('/api/dosens', [
        'id_user' => 9999,
        'nidn' => '',
        'nama' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id_user', 'nidn', 'nama']);
});

test('can show a dosen', function () {
    $dosen = Dosen::create([
        'id_user' => $this->user->id,
        'nidn' => '1234567890',
        'nama' => 'JOHN DOE',
    ]);

    $response = $this->getJson("/api/dosens/{$dosen->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nidn' => '1234567890',
            'nama' => 'JOHN DOE',
        ]);
});

test('returns 404 when showing non-existing dosen', function () {
    $response = $this->getJson('/api/dosens/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'dosen tidak ditemukan',
        ]);
});

test('can update dosen', function () {
    $dosen = Dosen::create([
        'id_user' => $this->user->id,
        'nidn' => '1234567890',
        'nama' => 'JOHN DOE',
    ]);

    $response = $this->putJson("/api/dosens/{$dosen->id}", [
        'id_user' => $this->user->id,
        'nidn' => '1234567890',
        'nama' => 'john doe updated',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nidn' => '1234567890',
            'nama' => 'JOHN DOE UPDATED',
        ]);
});

test('can delete dosen', function () {
    $dosen = Dosen::create([
        'id_user' => $this->user->id,
        'nidn' => '1234567890',
        'nama' => 'JOHN DOE',
    ]);

    $response = $this->deleteJson("/api/dosens/{$dosen->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'dosen berhasil dihapus',
        ]);

    $this->assertSoftDeleted('dosens', [
        'id' => $dosen->id,
    ]);
});

test('can get active classes for a dosen', function () {
    $dosen = Dosen::create([
        'id_user' => $this->user->id,
        'nidn' => '1234567890',
        'nama' => 'JOHN DOE',
    ]);

    $fakultas = \App\Models\Fakultas::create([
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $prodi = \App\Models\Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $mataKuliah = \App\Models\MataKuliah::create([
        'id_prodi' => $prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);

    $taActive = \App\Models\TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);

    $taInactive = \App\Models\TahunAkademik::create([
        'kode_ta' => '20221',
        'nama_ta' => 'GANJIL 2022/2023',
        'status' => false,
    ]);

    $kelasActive = \App\Models\KelasKuliah::create([
        'kode_kelas' => 'IF-3A-ACT',
        'id_mk' => $mataKuliah->id,
        'id_ta' => $taActive->id,
        'nama_kelas' => 'KELAS A ACTIVE',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    $kelasInactive = \App\Models\KelasKuliah::create([
        'kode_kelas' => 'IF-3A-INACT',
        'id_mk' => $mataKuliah->id,
        'id_ta' => $taInactive->id,
        'nama_kelas' => 'KELAS A INACTIVE',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    // Assign dosen to both classes
    \App\Models\DosenPengampu::create([
        'id_kelas' => $kelasActive->id,
        'id_dosen' => $dosen->id,
    ]);

    \App\Models\DosenPengampu::create([
        'id_kelas' => $kelasInactive->id,
        'id_dosen' => $dosen->id,
    ]);

    $response = $this->getJson("/api/dosens/{$dosen->id}/kelas-kuliah-aktif");

    $response->assertStatus(200)
        ->assertJsonCount(1)
        ->assertJsonFragment([
            'kode_kelas' => 'IF-3A-ACT',
        ])
        ->assertJsonMissing([
            'kode_kelas' => 'IF-3A-INACT',
        ]);
});
