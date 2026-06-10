<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Prodi extends Model
{
    use SoftDeletes;

    protected $table = 'study_programs';

    protected $fillable = ['faculty_id', 'code', 'name', 'jenjang', 'nim_prefix'];

    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class, 'faculty_id');
    }

    public function mataKuliah()
    {
        return $this->hasMany(MataKuliah::class, 'study_program_id');
    }

    public function mahasiswa()
    {
        return $this->hasMany(Mahasiswa::class, 'study_program_id');
    }
}
