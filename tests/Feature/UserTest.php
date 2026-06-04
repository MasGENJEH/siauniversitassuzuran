<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('can get all users', function () {
    User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->getJson('/api/users');

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
});

test('can create user', function () {
    $response = $this->postJson('/api/users', [
        'name' => 'New User',
        'email' => 'new@example.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(201)
        ->assertJsonFragment([
            'name' => 'New User',
            'email' => 'new@example.com',
        ]);

    $this->assertDatabaseHas('users', [
        'email' => 'new@example.com',
    ]);
});

test('validation errors when creating user', function () {
    $response = $this->postJson('/api/users', [
        'name' => '',
        'email' => 'invalid-email',
        'password' => 'short',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

test('can show a user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->getJson("/api/users/{$user->id}");

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
});

test('returns 404 when showing non-existing user', function () {
    $response = $this->getJson('/api/users/9999');

    $response->assertStatus(404)
        ->assertJson([
            'message' => 'User tidak ditemukan',
        ]);
});

test('can update user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->putJson("/api/users/{$user->id}", [
        'name' => 'Updated User',
        'email' => 'updated@example.com',
    ]);

    $response->assertStatus(200)
        ->assertJsonFragment([
            'name' => 'Updated User',
            'email' => 'updated@example.com',
        ]);
});

test('can delete user', function () {
    $user = User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
    ]);

    $response = $this->deleteJson("/api/users/{$user->id}");

    $response->assertStatus(200)
        ->assertJson([
            'message' => 'user berhasil dihapus',
        ]);

    $this->assertSoftDeleted('users', [
        'id' => $user->id,
    ]);
});
