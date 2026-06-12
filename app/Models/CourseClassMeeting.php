<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseClassMeeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_class_id',
        'tanggal',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tanggal' => 'date',
    ];

    public function courseClass()
    {
        return $this->belongsTo(CourseClass::class);
    }

    public function absensis()
    {
        return $this->hasMany(Absensi::class);
    }
}
