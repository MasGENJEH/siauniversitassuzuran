<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'mahasiswa', 'dosen'];

        $permissions = ['create role', 'edit role', 'delete role', 'view role'];

        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
        }
        foreach ($permissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }
        $adminRole = Role::where('name', 'admin')->first();
        $adminRole->givePermissionTo($permissions);

        foreach ($roles as $roleName) {
            $user = User::firstOrCreate(
                ['email' => $roleName.'@mail.com'],
                [
                    'name' => ucfirst($roleName).' User',
                    'phone' => fake()->phoneNumber(),
                    'photo' => fake()->imageUrl(200, 200, 'people', true, 'profile'),
                    'password' => Hash::make('password123'),
                ]
            );

            $user->assignRole($roleName);
        }

        // Assign 'dosen' role to all users whose email matches/contains 'dosen'
        $dosenRole = Role::where('name', 'dosen')->first();
        $dosenUsers = User::where('email', 'like', '%dosen%')->get();
        foreach ($dosenUsers as $user) {
            $user->assignRole($dosenRole);
        }

        // Assign 'mahasiswa' role to all users whose email matches/contains 'mhs'
        $mahasiswaRole = Role::where('name', 'mahasiswa')->first();
        $mahasiswaUsers = User::where('email', 'like', '%mhs%')->get();
        foreach ($mahasiswaUsers as $user) {
            $user->assignRole($mahasiswaRole);
        }
    }
}
