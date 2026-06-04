<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DosenPengampu extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_kelas', 'id_dosen'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'id_dosen', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(KelasKuliah::class, 'id_kelas', 'id');
    }
}
