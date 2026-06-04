<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fakultas extends Model
{
    //
    use SoftDeletes;

    protected $fillable = ['kode_fakultas', 'nama_fakultas'];

    public function prodi()
    {
        return $this->hasMany(Prodi::class);
    }
}
