<?php

namespace App\Helpers;

use App\Models\KelasKuliah;
use App\Models\MataKuliah;

class KelasKuliahHelper
{
    public static function generateUniqueKodeKelas(string $kodeMk): string
    {
        $mataKuliah = MataKuliah::query()->where('kode_mk', $kodeMk)->first();
        $prefix = $mataKuliah->kode_mk ?? 'KLS';

        do {
            $randomString = $prefix . mt_rand(100, 999);
        } while (KelasKuliah::query()->where('kode_kelas', $randomString)->exists());

        return $randomString;
    }
}
