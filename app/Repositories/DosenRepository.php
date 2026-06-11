<?php

namespace App\Repositories;

use App\Models\Dosen;

class DosenRepository
{
    public function getAll(array $fields)
    {
        return Dosen::select($fields)->latest()->get();
    }

    public function getById(int $id, array $fields)
    {
        return Dosen::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return Dosen::create($data);
    }

    public function update(int $id, array $data)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->update($data);

        return $dosen;
    }

    public function delete(int $id)
    {
        $dosen = Dosen::findOrFail($id);
        $dosen->delete();
    }

    public function getKelasKuliahAktif(int $id)
    {
        $dosen = Dosen::findOrFail($id);

        return $dosen->kelas()
            ->whereHas('tahunAkademik', function ($query) {
                $query->where('status', true);
            })
            ->with(['mataKuliah:id,code,name,sks', 'tahunAkademik:id,code,name'])
            ->get();
    }
}
