<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliah extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_prodi', 'semester_plot', 'kode_mk', 'nama_mk', 'sks'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'id_prodi', 'id');
    }

    public function kelasKuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'id_mk', 'id');
    }
}
