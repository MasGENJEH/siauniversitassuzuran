<?php

use App\Models\Fakultas;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get all fakultas', function () {
    Fakultas::create([
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $response = $this->getJson('/api/fakultas');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_fakultas' => 'FT',
            'nama_fakultas' => 'TEKNIK',
        ]);
});

test('can create fakultas', function () {
    $response = $this->postJson('/api/fakultas', [
        'kode_fakultas' => 'ft',
        'nama_fakultas' => 'teknik',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'kode_fakultas' => 'ft',
            'nama_fakultas' => 'teknik',
        ]);

    $this->assertDatabaseHas('fakultas', [
        'kode_fakultas' => 'ft',
    ]);
});

test('validation errors when creating fakultas', function () {
    $response = $this->postJson('/api/fakultas', [
        'kode_fakultas' => '',
        'nama_fakultas' => '',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['kode_fakultas', 'nama_fakultas']);
});

test('can show a fakultas', function () {
    $fakultas = Fakultas::create([
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $response = $this->getJson("/api/fakultas/{$fakultas->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_fakultas' => 'FT',
            'nama_fakultas' => 'TEKNIK',
        ]);
});

test('returns 404 when showing non-existing fakultas', function () {
    $response = $this->getJson('/api/fakultas/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'fakultas tidak ditemukan',
        ]);
});

test('can update fakultas', function () {
    $fakultas = Fakultas::create([
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $response = $this->putJson("/api/fakultas/{$fakultas->id}", [
        'kode_fakultas' => 'FT-UPD',
        'nama_fakultas' => 'TEKNIK UPDATED',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'kode_fakultas' => 'FT-UPD',
            'nama_fakultas' => 'TEKNIK UPDATED',
        ]);
});

test('can delete fakultas', function () {
    $fakultas = Fakultas::create([
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $response = $this->deleteJson("/api/fakultas/{$fakultas->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'fakultas berhasil dihapus',
        ]);

    $this->assertSoftDeleted('fakultas', [
        'id' => $fakultas->id,
    ]);
});
