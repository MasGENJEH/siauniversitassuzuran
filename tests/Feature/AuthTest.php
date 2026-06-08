<?php

use App\Models\User;
use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\TahunAkademik;
use App\Models\KelasKuliah;
use App\Models\KelasMahasiswa;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

test('user can login and receive plain text token', function () {
    Role::firstOrCreate(['name' => 'admin']);

    $user = User::factory()->create([
        'email' => 'admin@mail.com',
        'password' => Hash::make('password123'),
    ]);
    $user->assignRole('admin');

    $response = $this->postJson('/api/login', [
        'email' => 'admin@mail.com',
        'password' => 'password123',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'message',
            'token',
            'user' => [
                'id',
                'name',
                'email',
                'roles',
            ],
        ]);
});

test('guest cannot access protected routes', function () {
    // We override beforeEach by logging out to simulate a guest
    $this->withHeaders([])->getJson('/api/user')->assertStatus(401);
});

test('non-admin user cannot create a fakultas', function () {
    // actingAsDosen will override actingAsAdmin from beforeEach
    actingAsDosen();

    $response = $this->postJson('/api/fakultas', [
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $response->assertStatus(403);
});

test('admin can create a fakultas', function () {
    actingAsAdmin();

    $response = $this->postJson('/api/fakultas', [
        'kode_fakultas' => 'FT',
        'nama_fakultas' => 'TEKNIK',
    ]);

    $response->assertStatus(201);
});

test('dosen can update grades (kelas-mahasiswa)', function () {
    // Create necessary seed models
    $fakultas = Fakultas::create(['kode_fakultas' => 'FT', 'nama_fakultas' => 'TEKNIK']);
    $prodi = Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);
    
    // Create Dosen user
    Role::firstOrCreate(['name' => 'dosen']);
    $dosenUser = User::create(['name' => 'PA Dosen', 'email' => 'pa@example.com', 'password' => Hash::make('pass123')]);
    $dosenUser->assignRole('dosen');
    
    $dosen = Dosen::create(['id_user' => $dosenUser->id, 'nidn' => '1234567890', 'nama' => 'DOSEN TEST']);
    
    // Log in as the specific Dosen user we created
    Laravel\Sanctum\Sanctum::actingAs($dosenUser, ['*']);

    $mhsUser = User::create(['name' => 'Student User', 'email' => 'student@example.com', 'password' => Hash::make('pass123')]);
    $mahasiswa = Mahasiswa::create([
        'id_user' => $mhsUser->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $prodi->id,
        'id_dosen_pa' => $dosen->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);
    $mataKuliah = MataKuliah::create([
        'id_prodi' => $prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);
    $tahunAkademik = TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);
    $kelasKuliah = KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $mataKuliah->id,
        'id_ta' => $tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    // Set Dosen as Pengampu for the class
    \DB::table('dosen_pengampus')->insert([
        'id_kelas' => $kelasKuliah->id,
        'id_dosen' => $dosen->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $km = KelasMahasiswa::create([
        'id_mahasiswa' => $mahasiswa->id,
        'id_kelas' => $kelasKuliah->id,
        'nilai_akhir' => 85.5,
        'nilai_huruf' => 'A',
    ]);

    $response = $this->putJson("/api/kelas-mahasiswas/{$km->id}", [
        'id_mahasiswa' => $mahasiswa->id,
        'id_kelas' => $kelasKuliah->id,
        'nilai_akhir' => 90.0,
        'nilai_huruf' => 'A',
    ]);

    $response->assertStatus(200);
});

test('dosen cannot update grades of a class they do not teach', function () {
    // Create necessary seed models
    $fakultas = Fakultas::create(['kode_fakultas' => 'FT', 'nama_fakultas' => 'TEKNIK']);
    $prodi = Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);
    
    // Create Dosen user (logged in, not teaching the class)
    Role::firstOrCreate(['name' => 'dosen']);
    $otherDosenUser = User::create(['name' => 'Other Dosen', 'email' => 'other@example.com', 'password' => Hash::make('pass123')]);
    $otherDosenUser->assignRole('dosen');
    $otherDosen = Dosen::create(['id_user' => $otherDosenUser->id, 'nidn' => '9999999999', 'nama' => 'OTHER DOSEN']);
    
    Laravel\Sanctum\Sanctum::actingAs($otherDosenUser, ['*']);

    // Create a different Dosen (who will be assigned to the class, but is not logged in)
    $dosenUser = User::create(['name' => 'Class Dosen', 'email' => 'class@example.com', 'password' => Hash::make('pass123')]);
    $dosen = Dosen::create(['id_user' => $dosenUser->id, 'nidn' => '1234567890', 'nama' => 'CLASS DOSEN']);

    $mhsUser = User::create(['name' => 'Student User', 'email' => 'student@example.com', 'password' => Hash::make('pass123')]);
    $mahasiswa = Mahasiswa::create([
        'id_user' => $mhsUser->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $prodi->id,
        'id_dosen_pa' => $dosen->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);
    $mataKuliah = MataKuliah::create([
        'id_prodi' => $prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);
    $tahunAkademik = TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);
    $kelasKuliah = KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $mataKuliah->id,
        'id_ta' => $tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    // Assign the class dosen (not the logged in otherDosen)
    \DB::table('dosen_pengampus')->insert([
        'id_kelas' => $kelasKuliah->id,
        'id_dosen' => $dosen->id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $km = KelasMahasiswa::create([
        'id_mahasiswa' => $mahasiswa->id,
        'id_kelas' => $kelasKuliah->id,
        'nilai_akhir' => 85.5,
        'nilai_huruf' => 'A',
    ]);

    $response = $this->putJson("/api/kelas-mahasiswas/{$km->id}", [
        'id_mahasiswa' => $mahasiswa->id,
        'id_kelas' => $kelasKuliah->id,
        'nilai_akhir' => 90.0,
        'nilai_huruf' => 'A',
    ]);

    // Assert that the logged in otherDosen receives 403 forbidden
    $response->assertStatus(403);
});

test('mahasiswa cannot update grades', function () {
    actingAsMahasiswa();

    $fakultas = Fakultas::create(['kode_fakultas' => 'FT', 'nama_fakultas' => 'TEKNIK']);
    $prodi = Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);
    $dosenUser = User::create(['name' => 'PA Dosen', 'email' => 'pa@example.com', 'password' => 'pass123']);
    $dosen = Dosen::create(['id_user' => $dosenUser->id, 'nidn' => '1234567890', 'nama' => 'DOSEN TEST']);
    $mhsUser = User::create(['name' => 'Student User', 'email' => 'student@example.com', 'password' => 'pass123']);
    $mahasiswa = Mahasiswa::create([
        'id_user' => $mhsUser->id,
        'nim' => '101260000001',
        'nama' => 'MAHASISWA TEST',
        'id_prodi' => $prodi->id,
        'id_dosen_pa' => $dosen->id,
        'tahun_masuk' => 2026,
        'status_mahasiswa' => 'AKTIF',
    ]);
    $mataKuliah = MataKuliah::create([
        'id_prodi' => $prodi->id,
        'kode_mk' => 'IF101',
        'nama_mk' => 'PEMROGRAMAN WEB',
        'sks' => 3,
        'semester_plot' => 3,
    ]);
    $tahunAkademik = TahunAkademik::create([
        'kode_ta' => '20231',
        'nama_ta' => 'GANJIL 2023/2024',
        'status' => true,
    ]);
    $kelasKuliah = KelasKuliah::create([
        'kode_kelas' => 'IF-3A',
        'id_mk' => $mataKuliah->id,
        'id_ta' => $tahunAkademik->id,
        'nama_kelas' => 'KELAS A',
        'hari' => 'SENIN',
        'jam_mulai' => '08:00:00',
        'jam_selesai' => '09:40:00',
        'ruangan' => 'LAB 1',
    ]);

    $km = KelasMahasiswa::create([
        'id_mahasiswa' => $mahasiswa->id,
        'id_kelas' => $kelasKuliah->id,
        'nilai_akhir' => 85.5,
        'nilai_huruf' => 'A',
    ]);

    $response = $this->putJson("/api/kelas-mahasiswas/{$km->id}", [
        'id_mahasiswa' => $mahasiswa->id,
        'id_kelas' => $kelasKuliah->id,
        'nilai_akhir' => 90.0,
        'nilai_huruf' => 'A',
    ]);

    // Assert that the logged in otherDosen receives 403 forbidden
    $response->assertStatus(403);
});

test('dosen can fetch their own active classes schedule', function () {
    $fakultas = Fakultas::create(['kode_fakultas' => 'FT', 'nama_fakultas' => 'TEKNIK']);
    $prodi = Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);
    
    Role::firstOrCreate(['name' => 'dosen']);
    $dosenUser = User::create(['name' => 'PA Dosen', 'email' => 'pa@example.com', 'password' => Hash::make('pass123')]);
    $dosenUser->assignRole('dosen');
    $dosen = Dosen::create(['id_user' => $dosenUser->id, 'nidn' => '1234567890', 'nama' => 'DOSEN TEST']);
    
    Laravel\Sanctum\Sanctum::actingAs($dosenUser, ['*']);

    $response = $this->getJson("/api/dosens/{$dosen->id}/kelas-kuliah-aktif");
    $response->assertStatus(200);
});

test('dosen cannot fetch other lecturers active classes schedule', function () {
    $fakultas = Fakultas::create(['kode_fakultas' => 'FT', 'nama_fakultas' => 'TEKNIK']);
    $prodi = Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);
    
    Role::firstOrCreate(['name' => 'dosen']);
    $dosenUser1 = User::create(['name' => 'PA Dosen 1', 'email' => 'pa1@example.com', 'password' => Hash::make('pass123')]);
    $dosenUser1->assignRole('dosen');
    $dosen1 = Dosen::create(['id_user' => $dosenUser1->id, 'nidn' => '1234567891', 'nama' => 'DOSEN TEST 1']);
    
    $dosenUser2 = User::create(['name' => 'PA Dosen 2', 'email' => 'pa2@example.com', 'password' => Hash::make('pass123')]);
    $dosenUser2->assignRole('dosen');
    $dosen2 = Dosen::create(['id_user' => $dosenUser2->id, 'nidn' => '1234567892', 'nama' => 'DOSEN TEST 2']);

    Laravel\Sanctum\Sanctum::actingAs($dosenUser1, ['*']);

    $response = $this->getJson("/api/dosens/{$dosen2->id}/kelas-kuliah-aktif");
    $response->assertStatus(403);
});

test('dosen can fetch their own academic advisee students', function () {
    $fakultas = Fakultas::create(['kode_fakultas' => 'FT', 'nama_fakultas' => 'TEKNIK']);
    $prodi = Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);
    
    Role::firstOrCreate(['name' => 'dosen']);
    $dosenUser = User::create(['name' => 'PA Dosen', 'email' => 'pa@example.com', 'password' => Hash::make('pass123')]);
    $dosenUser->assignRole('dosen');
    $dosen = Dosen::create(['id_user' => $dosenUser->id, 'nidn' => '1234567890', 'nama' => 'DOSEN TEST']);
    
    Laravel\Sanctum\Sanctum::actingAs($dosenUser, ['*']);

    $response = $this->getJson("/api/dosens/{$dosen->id}/mahasiswa-bimbingan");
    $response->assertStatus(200);
});

test('dosen cannot fetch other lecturers academic advisee students', function () {
    $fakultas = Fakultas::create(['kode_fakultas' => 'FT', 'nama_fakultas' => 'TEKNIK']);
    $prodi = Prodi::create([
        'id_fakultas' => $fakultas->id,
        'kode_prodi' => 'IF',
        'nama_prodi' => 'INFORMATIKA',
        'jenjang' => 'S1',
        'prefix_nim' => '101',
    ]);
    
    Role::firstOrCreate(['name' => 'dosen']);
    $dosenUser1 = User::create(['name' => 'PA Dosen 1', 'email' => 'pa1@example.com', 'password' => Hash::make('pass123')]);
    $dosenUser1->assignRole('dosen');
    $dosen1 = Dosen::create(['id_user' => $dosenUser1->id, 'nidn' => '1234567891', 'nama' => 'DOSEN TEST 1']);
    
    $dosenUser2 = User::create(['name' => 'PA Dosen 2', 'email' => 'pa2@example.com', 'password' => Hash::make('pass123')]);
    $dosenUser2->assignRole('dosen');
    $dosen2 = Dosen::create(['id_user' => $dosenUser2->id, 'nidn' => '1234567892', 'nama' => 'DOSEN TEST 2']);

    Laravel\Sanctum\Sanctum::actingAs($dosenUser1, ['*']);

    $response = $this->getJson("/api/dosens/{$dosen2->id}/mahasiswa-bimbingan");
    $response->assertStatus(403);
});
