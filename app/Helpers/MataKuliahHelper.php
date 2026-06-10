<?php

namespace App\Helpers;

use App\Models\MataKuliah;
use App\Models\Prodi;

class MataKuliahHelper
{
    public static function generateUniqueKodeKelas(string $kodeProdi): string
    {
        // $prefix = 'SZRN';
        $prodi = Prodi::query()->where('code', $kodeProdi)->first();

        $prefix = $prodi->code ?? 'MK';
        do {
            $randomString = $prefix.mt_rand(10000, 99999);
        } while (MataKuliah::query()->where('code', $randomString)->exists()); // kondisi membuat kode terus tergenerate ketika kode sudah ada

        return $randomString;
    }
}
