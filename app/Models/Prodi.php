<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_fakultas', 'kode_prodi', 'nama_prodi', 'jenjang', 'prefix_nim'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class);
    }
}
