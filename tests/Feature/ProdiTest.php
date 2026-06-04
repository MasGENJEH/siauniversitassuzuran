<?php

use App\Models\Fakultas;
use App\Models\Prodi;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->fakultas = Fakultas::create([
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);
});

test('can get all prodis', function () {
    Prodi::create([
        'id_fakultas' => $this->fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $response = $this->getJson('/api/prodis');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_prodi' => 'IF',
            'nama_prodi' => 'INFORMATIKA',
        ]);
});

test('can create prodi', function () {
    $response = $this->postJson('/api/prodis', [
        'id_fakultas' => $this->fakultas->id,
        'kode_prodi' => 'if',
        'nama_prodi' => 'informatika',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'kode_prodi' => 'IF',
            'nama_prodi' => 'INFORMATIKA',
            'jenjang' => 'S1',
        ]);

    $this->assertDatabaseHas('prodis', [
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
    ]);
});

test('validation errors when creating prodi', function () {
    $response = $this->postJson('/api/prodis', [
        'id_fakultas' => 9999,
        'kode_prodi' => '',
        'nama_prodi' => '',
        'jenjang' => '',
        'prefix_nim' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['id_fakultas', 'kode_prodi', 'nama_prodi', 'jenjang', 'prefix_nim']);
});

test('can show a prodi', function () {
    $prodi = Prodi::create([
        'id_fakultas' => $this->fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $response = $this->getJson("/api/prodis/{$prodi->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_prodi' => 'IF',
            'nama_prodi' => 'INFORMATIKA',
        ]);
});

test('returns 404 when showing non-existing prodi', function () {
    $response = $this->getJson('/api/prodis/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'prodi tidak ditemukan',
        ]);
});

test('can update prodi', function () {
    $prodi = Prodi::create([
        'id_fakultas' => $this->fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $response = $this->putJson("/api/prodis/{$prodi->id}", [
        'id_fakultas' => $this->fakultas->id,
        'kode_prodi' => 'if-u',
        'nama_prodi' => 'informatika updated',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'kode_prodi' => 'IF-U',
            'nama_prodi' => 'INFORMATIKA UPDATED',
        ]);
});

test('can delete prodi', function () {
    $prodi = Prodi::create([
        'id_fakultas' => $this->fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);

    $response = $this->deleteJson("/api/prodis/{$prodi->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'prodi berhasil dihapus',
        ]);

    $this->assertSoftDeleted('prodis', [
        'id' => $prodi->id,
    ]);
});
