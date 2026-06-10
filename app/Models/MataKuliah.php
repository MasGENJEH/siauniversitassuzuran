<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MataKuliah extends Model
{
    use SoftDeletes;

    protected $table = 'courses';

    protected $fillable = ['study_program_id', 'recommended_semester', 'code', 'name', 'sks'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'study_program_id', 'id');
    }

    public function kelasKuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'course_id', 'id');
    }
}
