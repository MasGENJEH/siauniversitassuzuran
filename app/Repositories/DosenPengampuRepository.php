<?php

namespace App\Repositories;

use App\Models\DosenPengampu;

class DosenPengampuRepository
{
    public function getAll(array $fields)
    {
        return DosenPengampu::select($fields)->latest()->get();
    }

    public function getById(int $id, array $fields)
    {
        return DosenPengampu::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return DosenPengampu::create($data);
    }

    public function update(int $id, array $data)
    {
        $dosenPengampu = DosenPengampu::findOrFail($id);
        $dosenPengampu->update($data);

        return $dosenPengampu;
    }

    public function delete(int $id)
    {
        $dosenPengampu = DosenPengampu::findOrFail($id);
        $dosenPengampu->delete();
    }
}
