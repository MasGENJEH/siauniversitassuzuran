<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dosen extends Model
{
    use SoftDeletes;

    protected $table = 'lecturers';

    protected $fillable = ['user_id', 'nidn', 'name', 'photo'];

    public function pengampu()
    {
        return $this->hasMany(DosenPengampu::class, 'lecturer_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(KelasKuliah::class, 'class_instructors', 'lecturer_id', 'course_class_id');
    }
}
