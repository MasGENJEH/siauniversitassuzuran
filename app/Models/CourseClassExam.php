<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClassExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'exam_type',
        'tanggal',
        'start_time',
        'end_time',
        'room',
        'lecturer_id',
        'method',
        'notes',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(Dosen::class, 'lecturer_id');
    }
}
