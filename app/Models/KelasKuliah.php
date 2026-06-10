<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasKuliah extends Model
{
    use SoftDeletes;

    protected $table = 'course_classes';

    protected $fillable = [
        'class_code',
        'course_id',
        'academic_year_id',
        'class_name',
        'day',
        'start_time',
        'end_time',
        'room',
    ];

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'class_instructors', 'course_class_id', 'lecturer_id');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'course_id', 'id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'academic_year_id', 'id');
    }

    public function kelasMahasiswa()
    {
        return $this->hasMany(KelasMahasiswa::class, 'course_class_id', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'enrollments', 'course_class_id', 'student_id')
                    ->withPivot('final_score', 'letter_grade');
    }
}
