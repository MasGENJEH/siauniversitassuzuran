<?php

namespace App\Helpers;

use App\Models\MataKuliah;
use App\Models\Prodi;

class MataKuliahHelper
{
    public static function generateUniqueKodeKelas(string $kodeProdi): string
    {
        // $prefix = 'SZRN';
        $prodi = Prodi::query()->where('kode_prodi', $kodeProdi)->first();

        $prefix = $prodi->kode_prodi ?? 'MK';
        do {
            $randomString = $prefix.mt_rand(10000, 99999);
        } while (MataKuliah::query()->where('kode_mk', $randomString)->exists()); // kondisi membuat kode terus tergenerate ketika kode sudah ada

        return $randomString;
    }
}
