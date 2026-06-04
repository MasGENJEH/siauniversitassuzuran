<?php

namespace App\Helpers;

use App\Models\Mahasiswa;
use App\Models\Prodi;

class MahasiswaHelper
{
    public static function generateUniqueNim(string $kodeProdi): string
    {
        $tahun = substr(date('Y'), -2);

        $prodi = Prodi::query()->where('kode_prodi', $kodeProdi)->first();

        $prefix = $prodi->prefix_nim ?? '000';

        // 2. Lakukan perulangan sampai NIM benar-benar unik di database
        do {
            $randomNim = $prefix.$tahun.mt_rand(1000000, 9999999);
        } while (Mahasiswa::query()->where('nim', $randomNim)->exists());

        return $randomNim;
    }
}
