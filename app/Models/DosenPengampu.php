<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DosenPengampu extends Model
{
    use SoftDeletes;

    protected $table = 'class_instructors';

    protected $fillable = ['course_class_id', 'lecturer_id'];

    public function dosen()
    {
        return $this->belongsTo(Dosen::class, 'lecturer_id', 'id');
    }

    public function kelas()
    {
        return $this->belongsTo(KelasKuliah::class, 'course_class_id', 'id');
    }
}
