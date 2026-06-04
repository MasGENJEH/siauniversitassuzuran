<?php

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
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

    $this->userPA = User::create([
        'name' => 'Dosen PA User',
        'email' => 'pa@example.com',
        'password' => bcrypt('password123'),
    ]);

    $this->dosenPA = Dosen::create([
        'id_user' => $this->userPA->id,
        'nidn' => '1234567890',
        'nama' => 'DOSEN PA',
    ]);

    $this->userMhs = User::create([
        'name' => 'Mhs User',
        'email' => 'mhs@example.com',
        'password' => bcrypt('password123'),
    ]);
});

test('can get all mahasiswas', function () {
    Mahasiswa::create([
        'id_user' => $this->userMhs->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $this->prodi->id,
        'id_dosen_pa' => $this->dosenPA->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);

    $response = $this->getJson('/api/mahasiswas');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nim' => '101260000001',
            'nama' => 'MAHASISWA TEST',
        ]);
});

test('can create mahasiswa', function () {
    $response = $this->postJson('/api/mahasiswas', [
        'id_user' => $this->userMhs->id,
        'nama' => 'new mahasiswa',
        'id_prodi' => $this->prodi->id,
        'id_dosen_pa' => $this->dosenPA->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'aktif',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'nama' => 'NEW MAHASISWA',
            'status_mahasiswa' => 'AKTIF',
        ]);

    $generatedNim = $response->json('nim');
    expect($generatedNim)->toStartWith('10126');

    $this->assertDatabaseHas('mahasiswas', [
        'nim' => $generatedNim,
        'nama' => 'NEW MAHASISWA',
    ]);
});

test('validation errors when creating mahasiswa', function () {
    $response = $this->postJson('/api/mahasiswas', [
        'id_user' => 9999,
        'nama' => '',
        'id_prodi' => 9999,
        'id_dosen_pa' => 9999,
        'tahun_masuk' => '20',
        'status_mahasiswa' => 'INVALID_STATUS',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id_user', 'nama', 'id_prodi', 'id_dosen_pa', 'tahun_masuk', 'status_mahasiswa']);
});

test('can show a mahasiswa', function () {
    $mhs = Mahasiswa::create([
        'id_user' => $this->userMhs->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $this->prodi->id,
        'id_dosen_pa' => $this->dosenPA->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);

    $response = $this->getJson("/api/mahasiswas/{$mhs->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nim' => '101260000001',
            'nama' => 'MAHASISWA TEST',
        ]);
});

test('returns 404 when showing non-existing mahasiswa', function () {
    $response = $this->getJson('/api/mahasiswas/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'mahasiswa tidak ditemukan',
        ]);
});

test('can update mahasiswa', function () {
    $mhs = Mahasiswa::create([
        'id_user' => $this->userMhs->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $this->prodi->id,
        'id_dosen_pa' => $this->dosenPA->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);

    $response = $this->putJson("/api/mahasiswas/{$mhs->id}", [
        'id_user' => $this->userMhs->id,
        'nama' => 'mahasiswa test updated',
        'id_prodi' => $this->prodi->id,
        'id_dosen_pa' => $this->dosenPA->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'cuti',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nama' => 'MAHASISWA TEST UPDATED',
            'status_mahasiswa' => 'CUTI',
        ]);
});

test('can delete mahasiswa', function () {
    $mhs = Mahasiswa::create([
        'id_user' => $this->userMhs->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $this->prodi->id,
        'id_dosen_pa' => $this->dosenPA->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);

    $response = $this->deleteJson("/api/mahasiswas/{$mhs->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'mahasiswa berhasil dihapus',
        ]);

    $this->assertSoftDeleted('mahasiswas', [
        'id' => $mhs->id,
    ]);
});
