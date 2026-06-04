<?php

use App\Models\TahunAkademik;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get all tahun akademik', function () {
    TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);

    $response = $this->getJson('/api/tahun-akademiks');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_ta' => '20231',
            'nama_ta' => 'GANJIL 2023/2024',
            'status' => true,
        ]);
});

test('can create tahun akademik', function () {
    $response = $this->postJson('/api/tahun-akademiks', [
        'kode_ta' => '20232',
        'nama_ta' => 'genap 2023/2024',
        'status' => false,
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'kode_ta' => '20232',
            'nama_ta' => 'GENAP 2023/2024',
            'status' => false,
        ]);

    $this->assertDatabaseHas('tahun_akademiks', [
        'kode_ta' => '20232',
        'nama_ta' => 'GENAP 2023/2024',
    ]);
});

test('validation errors when creating tahun akademik', function () {
    $response = $this->postJson('/api/tahun-akademiks', [
        'kode_ta' => '',
        'nama_ta' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['kode_ta', 'nama_ta']);
});

test('can show a tahun akademik', function () {
    $ta = TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);

    $response = $this->getJson("/api/tahun-akademiks/{$ta->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_ta' => '20231',
            'nama_ta' => 'GANJIL 2023/2024',
        ]);
});

test('returns 404 when showing non-existing tahun akademik', function () {
    $response = $this->getJson('/api/tahun-akademiks/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'tahun akademik tidak ditemukan',
        ]);
});

test('can update tahun akademik', function () {
    $ta = TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);

    $response = $this->putJson("/api/tahun-akademiks/{$ta->id}", [
        'kode_ta' => '20231',
        'nama_ta' => 'ganjil update 2023/2024',
        'status' => false,
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'nama_ta' => 'GANJIL UPDATE 2023/2024',
            'status' => false,
        ]);

    $this->assertDatabaseHas('tahun_akademiks', [
        'id' => $ta->id,
        'nama_ta' => 'GANJIL UPDATE 2023/2024',
    ]);
});

test('can delete tahun akademik', function () {
    $ta = TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);

    $response = $this->deleteJson("/api/tahun-akademiks/{$ta->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'tahun akademik berhasil dihapus',
        ]);

    $this->assertSoftDeleted('tahun_akademiks', [
        'id' => $ta->id,
    ]);
});
