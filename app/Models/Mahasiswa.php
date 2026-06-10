<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'students';

    protected $fillable = ['user_id', 'nim', 'name', 'study_program_id', 'academic_advisor_id', 'enrollment_year', 'status', 'photo'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class, 'study_program_id');
    }

    public function dosenPa()
    {
        return $this->belongsTo(Dosen::class, 'academic_advisor_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(KelasKuliah::class, 'enrollments', 'student_id', 'course_class_id')
                    ->withPivot('final_score', 'letter_grade');
    }
}
