<?php

namespace App\Repositories;

use App\Models\KelasMahasiswa;

class KelasMahasiswaRepository
{
    public function getAll(array $fields)
    {
        return KelasMahasiswa::select($fields)->latest()->get();
    }

    public function getById(int $id, array $fields)
    {
        return KelasMahasiswa::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return KelasMahasiswa::create($data);
    }

    public function update(int $id, array $data)
    {
        $kelasMahasiswa = KelasMahasiswa::findOrFail($id);
        $kelasMahasiswa->update($data);

        return $kelasMahasiswa;
    }

    public function delete(int $id)
    {
        $kelasMahasiswa = KelasMahasiswa::findOrFail($id);
        $kelasMahasiswa->delete();
    }
}
