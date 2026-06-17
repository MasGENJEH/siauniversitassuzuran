<?php

namespace App\Repositories;

use App\Models\Mahasiswa;

class MahasiswaRepository
{
    public function getAll(array $fields, $perPage = null)
    {
        $query = Mahasiswa::with(['prodi:id,name,code', 'dosenPa:id,name,nidn'])
            ->select($fields)->latest();

        if (request()->has('search') && !empty(request()->query('search'))) {
            $search = request()->query('search');
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('nim', 'like', "%{$search}%");
        }

        return $perPage ? $query->paginate($perPage) : $query->get();
    }

    public function getById(int $id, array $fields)
    {
        return Mahasiswa::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return Mahasiswa::create($data);
    }

    public function update(int $id, array $data)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->update($data);

        return $mahasiswa;
    }

    public function delete(int $id)
    {
        $mahasiswa = Mahasiswa::findOrFail($id);
        $mahasiswa->delete();
    }
}
