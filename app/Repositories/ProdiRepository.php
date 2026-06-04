<?php

namespace App\Repositories;

use App\Models\Prodi;

class ProdiRepository
{
    public function getAll(array $fields)
    {
        return Prodi::select($fields)->latest()->paginate(50);
    }

    public function getById(int $id, array $fields = ['*'])
    {
        return Prodi::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return Prodi::create($data);
    }

    public function update(int $id, array $data)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->update($data);

        return $prodi;
    }

    public function delete(int $id)
    {
        $prodi = Prodi::findOrFail($id);
        $prodi->delete();
    }
}
