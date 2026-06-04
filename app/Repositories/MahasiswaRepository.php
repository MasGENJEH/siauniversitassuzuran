<?php

namespace App\Repositories;

use App\Models\Mahasiswa;

class MahasiswaRepository
{
    public function getAll(array $fields)
    {
        return Mahasiswa::select($fields)->latest()->paginate(50);
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
