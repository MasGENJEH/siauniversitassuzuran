<?php

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\MataKuliah;
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
});

test('can get all mata kuliahs', function () {
    MataKuliah::create([
        'id_prodi' => $this->prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);

    $response = $this->getJson('/api/mata-kuliahs');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_mk' => 'IF101',
            'nama_mk' => 'PEMROGRAMAN WEB',
        ]);
});

test('can create mata kuliah', function () {
    $response = $this->postJson('/api/mata-kuliahs', [
        'id_prodi' => $this->prodi->id,
        'nama_mk' => 'pemrograman web',
        'sks' => 3,
        'semester_plot' => 3,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nama_mk' => 'PEMROGRAMAN WEB',
            'sks' => 3,
            'semester_plot' => 3,
        ]);

    $generatedKode = $response->json('kode_mk');
    expect($generatedKode)->toStartWith('IF');

    $this->assertDatabaseHas('mata_kuliahs', [
        'kode_mk' => $generatedKode,
        'nama_mk' => 'PEMROGRAMAN WEB',
    ]);
});

test('validation errors when creating mata kuliah', function () {
    $response = $this->postJson('/api/mata-kuliahs', [
        'id_prodi' => 9999,
        'nama_mk' => '',
        'sks' => 10,
        'semester_plot' => 0,
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id_prodi', 'nama_mk', 'sks', 'semester_plot']);
});

test('can show a mata kuliah', function () {
    $mk = MataKuliah::create([
        'id_prodi' => $this->prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);

    $response = $this->getJson("/api/mata-kuliahs/{$mk->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_mk' => 'IF101',
            'nama_mk' => 'PEMROGRAMAN WEB',
        ]);
});

test('returns 404 when showing non-existing mata kuliah', function () {
    $response = $this->getJson('/api/mata-kuliahs/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'mata kuliah tidak ada',
        ]);
});

test('can update mata kuliah', function () {
    $mk = MataKuliah::create([
        'id_prodi' => $this->prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);

    $response = $this->putJson("/api/mata-kuliahs/{$mk->id}", [
        'id_prodi' => $this->prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'pemrograman web updated',
        'sks' => 4,
        'semester_plot' => 4,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_mk' => 'IF101',
            'nama_mk' => 'PEMROGRAMAN WEB UPDATED',
            'sks' => 4,
            'semester_plot' => 4,
        ]);
});

test('can delete mata kuliah', function () {
    $mk = MataKuliah::create([
        'id_prodi' => $this->prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);

    $response = $this->deleteJson("/api/mata-kuliahs/{$mk->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'mata kuliah berhasil dihapus',
        ]);

    $this->assertSoftDeleted('mata_kuliahs', [
        'id' => $mk->id,
    ]);
});
