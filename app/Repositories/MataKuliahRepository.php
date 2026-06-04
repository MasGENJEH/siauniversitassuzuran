<?php

namespace App\Repositories;

use App\Models\MataKuliah;

class MataKuliahRepository
{
    public function getAll(array $fields)
    {
        return MataKuliah::select($fields)->latest()->paginate(50);
    }

    public function getById(int $id, array $fields)
    {
        return MataKuliah::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return MataKuliah::create($data);
    }

    public function update(int $id, array $data)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->update($data);

        return $mataKuliah;
    }

    public function delete(int $id)
    {
        $mataKuliah = MataKuliah::findOrFail($id);
        $mataKuliah->delete();
    }
}
