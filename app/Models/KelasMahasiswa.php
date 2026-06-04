<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasMahasiswa extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id_mahasiswa',
        'id_kelas',
        'nilai_akhir',
        'nilai_huruf',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'id_mahasiswa', 'id');
    }

    public function kelasKuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas', 'id');
    }
}
