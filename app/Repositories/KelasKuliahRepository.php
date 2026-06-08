<?php

namespace App\Repositories;

use App\Models\KelasKuliah;

class KelasKuliahRepository
{
    public function getAll(array $fields)
    {
        return KelasKuliah::select($fields)->latest()->get();
    }

    public function getById(int $id, array $fields)
    {
        return KelasKuliah::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return KelasKuliah::create($data);
    }

    public function update(int $id, array $data)
    {
        $kelasKuliah = KelasKuliah::findOrFail($id);
        $kelasKuliah->update($data);

        return $kelasKuliah;
    }

    public function delete(int $id)
    {
        $kelasKuliah = KelasKuliah::findOrFail($id);
        $kelasKuliah->delete();
    }
}
