<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TahunAkademik extends Model
{
    use SoftDeletes;

    protected $table = 'academic_years';

    protected $fillable = ['code', 'name', 'status'];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function kelasKuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'academic_year_id');
    }
}
