<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahunAkademik extends Model
{
    use SoftDeletes;

    protected $fillable = ['kode_ta', 'nama_ta', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function kelasKuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'id_ta', 'id');
    }
}
