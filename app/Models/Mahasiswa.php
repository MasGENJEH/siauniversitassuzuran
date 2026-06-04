<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mahasiswa extends Model
{
    use SoftDeletes;

    protected $fillable = ['id_user', 'nim', 'nama', 'id_prodi', 'id_dosen_pa', 'tahun_masuk', 'status_mahasiswa'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function dosenPa()
    {
        return $this->belongsTo(Dosen::class);
    }

    public function kelas()
    {
        return $this->belongsToMany(KelasKuliah::class, 'kelas_mahasiswas', 'id_mahasiswa', 'id_kelas')
                    ->withPivot('nilai_akhir', 'nilai_huruf');
    }
}
