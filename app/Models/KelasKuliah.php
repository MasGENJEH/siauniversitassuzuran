<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class KelasKuliah extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'kode_kelas',
        'id_mk',
        'id_ta',
        'nama_kelas',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
    ];

    public function dosen()
    {
        return $this->belongsToMany(Dosen::class, 'dosen_pengampus', 'id_kelas', 'id_dosen');
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class, 'id_mk', 'id');
    }

    public function tahunAkademik()
    {
        return $this->belongsTo(TahunAkademik::class, 'id_ta', 'id');
    }

    public function kelasMahasiswa()
    {
        return $this->hasMany(KelasMahasiswa::class, 'id_kelas', 'id');
    }

    public function mahasiswa()
    {
        return $this->belongsToMany(Mahasiswa::class, 'kelas_mahasiswas', 'id_kelas', 'id_mahasiswa')
                    ->withPivot('nilai_akhir', 'nilai_huruf');
    }
}
