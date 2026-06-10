<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fakultas extends Model
{
    //
    use SoftDeletes;

    protected $table = 'faculties';

    protected $fillable = ['code', 'name'];

    public function prodi()
    {
        return $this->hasMany(Prodi::class, 'faculty_id');
    }
}
