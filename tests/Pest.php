<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in('Feature')
    ->beforeEach(function () {
        if (!str_contains(get_class($this), 'AuthTest') && !str_contains(get_class($this), 'ExampleTest')) {
            actingAsAdmin();
        }
    });

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

use App\Models\User;
use Spatie\Permission\Models\Role;
use Laravel\Sanctum\Sanctum;

function actingAsAdmin()
{
    Role::firstOrCreate(['name' => 'admin']);
    $user = User::factory()->create();
    $user->assignRole('admin');
    Sanctum::actingAs($user, ['*']);
    return $user;
}

function actingAsDosen()
{
    Role::firstOrCreate(['name' => 'dosen']);
    $user = User::factory()->create();
    $user->assignRole('dosen');
    Sanctum::actingAs($user, ['*']);
    return $user;
}

function actingAsMahasiswa()
{
    Role::firstOrCreate(['name' => 'mahasiswa']);
    $user = User::factory()->create();
    $user->assignRole('mahasiswa');
    Sanctum::actingAs($user, ['*']);
    return $user;
}
