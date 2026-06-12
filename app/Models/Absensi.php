<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    protected $table = 'absensis';

    protected $fillable = [
        'course_class_meeting_id',
        'student_id',
        'status',
    ];

    public function courseClassMeeting()
    {
        return $this->belongsTo(CourseClassMeeting::class);
    }

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'student_id', 'id');
    }
}
