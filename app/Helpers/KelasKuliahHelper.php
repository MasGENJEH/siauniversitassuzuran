<?php

namespace App\Helpers;

use App\Models\KelasKuliah;
use App\Models\MataKuliah;

class KelasKuliahHelper
{
    public static function generateUniqueKodeKelas(string $kodeMk): string
    {
        $mataKuliah = MataKuliah::query()->where('code', $kodeMk)->first();
        $prefix = $mataKuliah->code ?? 'KLS';

        do {
            $randomString = $prefix . mt_rand(100, 999);
        } while (KelasKuliah::query()->where('class_code', $randomString)->exists());

        return $randomString;
    }
}
