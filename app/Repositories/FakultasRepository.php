<?php

namespace App\Repositories;

use App\Models\Fakultas;

class FakultasRepository
{
    public function getAll(array $fields)
    {
        return Fakultas::select($fields)->latest()->paginate(20);
    }

    public function getById(int $id, array $fields)
    {
        return Fakultas::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return Fakultas::create($data);
    }

    public function update(int $id, array $data)
    {
        $fakultas = Fakultas::findOrFail($id);
        $fakultas->update($data);

        return $fakultas;
    }

    public function delete(int $id)
    {
        $fakultas = Fakultas::findOrFail($id);
        $fakultas->delete();
    }
}
