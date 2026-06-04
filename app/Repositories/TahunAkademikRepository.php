<?php

namespace App\Repositories;

use App\Models\TahunAkademik;

class TahunAkademikRepository
{
    public function getAll(array $fields)
    {
        return TahunAkademik::select($fields)->latest()->paginate(20);
    }

    public function getById(int $id, array $fields)
    {
        return TahunAkademik::select($fields)->findOrFail($id);
    }

    public function create(array $data)
    {
        return TahunAkademik::create($data);
    }

    public function update(int $id, array $data)
    {
        $tahunAkademik = TahunAkademik::findOrFail($id);
        $tahunAkademik->update($data);

        return $tahunAkademik;
    }

    public function delete(int $id)
    {
        $tahunAkademik = TahunAkademik::findOrFail($id);
        $tahunAkademik->delete();
    }
}
