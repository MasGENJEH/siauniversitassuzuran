<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_user', 'nidn', 'nama'];

    public function pengampu()
    {
        return $this->hasMany(DosenPengampu::class, 'id_dosen', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(KelasKuliah::class, 'dosen_pengampus', 'id_dosen', 'id_kelas');
    }
}
