<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasMahasiswa extends Model
{
    use SoftDeletes;

    protected $table = 'enrollments';

    protected $fillable = [
        'student_id',
        'course_class_id',
        'final_score',
        'letter_grade',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'student_id', 'id');
    }

    public function kelasKuliah()
    {
        return $this->belongsTo(KelasKuliah::class, 'course_class_id', 'id');
    }
}
