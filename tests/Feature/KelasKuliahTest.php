<?php

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\MataKuliah;
use App\Models\TahunAkademik;
use App\Models\KelasKuliah;
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
});

test('can get all kelas kuliah', function () {
    KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $this->mataKuliah->id,
        'id_ta' => $this->tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    $response = $this->getJson('/api/kelas-kuliahs');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_kelas' => 'IF-3A',
            'nama_kelas' => 'KELAS A',
        ]);
});

test('can create kelas kuliah', function () {
    $response = $this->postJson('/api/kelas-kuliahs', [
        'id_mk' => $this->mataKuliah->id,
        'id_ta' => $this->tahunAkademik->id,
        'nama_kelas' => 'kelas b',
        'hari' => 'selasa',
        'jam_mulai' => '10:00',
        'jam_selesai' => '11:40',
        'ruangan' => 'r.302',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'nama_kelas' => 'KELAS B',
            'hari' => 'SELASA',
            'ruangan' => 'R.302',
        ]);

    $generatedKode = $response->json('kode_kelas');
    expect($generatedKode)->toStartWith('IF101');

    $this->assertDatabaseHas('kelas_kuliahs', [
        'kode_kelas' => $generatedKode,
        'nama_kelas' => 'KELAS B',
    ]);
});

test('validation errors when creating kelas kuliah', function () {
    $response = $this->postJson('/api/kelas-kuliahs', [
        'id_mk' => 9999,
        'jam_mulai' => '10:00',
        'jam_selesai' => '08:00',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id_mk', 'id_ta', 'nama_kelas', 'hari', 'jam_selesai', 'ruangan']);
});

test('can show a kelas kuliah', function () {
    $kelas = KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $this->mataKuliah->id,
        'id_ta' => $this->tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    $response = $this->getJson("/api/kelas-kuliahs/{$kelas->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_kelas' => 'IF-3A',
            'nama_kelas' => 'KELAS A',
        ]);
});

test('returns 404 when showing non-existing kelas kuliah', function () {
    $response = $this->getJson('/api/kelas-kuliahs/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'kelas kuliah tidak ditemukan',
        ]);
});

test('can update kelas kuliah', function () {
    $kelas = KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $this->mataKuliah->id,
        'id_ta' => $this->tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    $response = $this->putJson("/api/kelas-kuliahs/{$kelas->id}", [
        'kode_kelas' => 'IF-3A',
        'id_mk' => $this->mataKuliah->id,
        'id_ta' => $this->tahunAkademik->id,
        'nama_kelas' => 'kelas a updated',
        'hari' => 'rabu',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '10:30:00',
        'ruangan' => 'lab 2',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nama_kelas' => 'KELAS A UPDATED',
            'hari' => 'RABU',
            'ruangan' => 'LAB 2',
        ]);

    $this->assertDatabaseHas('kelas_kuliahs', [
        'id' => $kelas->id,
        'nama_kelas' => 'KELAS A UPDATED',
        'hari' => 'RABU',
    ]);
});

test('can delete kelas kuliah', function () {
    $kelas = KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $this->mataKuliah->id,
        'id_ta' => $this->tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    $response = $this->deleteJson("/api/kelas-kuliahs/{$kelas->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'kelas kuliah berhasil dihapus',
        ]);

    $this->assertSoftDeleted('kelas_kuliahs', [
        'id' => $kelas->id,
    ]);
});
