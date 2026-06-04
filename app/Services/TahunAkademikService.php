<?php

namespace App\Services;

use App\Repositories\TahunAkademikRepository;

class TahunAkademikService
{
    private TahunAkademikRepository $tahunAkademikRepository;

    public function __construct(TahunAkademikRepository $tahunAkademikRepository)
    {
        $this->tahunAkademikRepository = $tahunAkademikRepository;
    }

    public function getAll(array $fields)
    {
        return $this->tahunAkademikRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->tahunAkademikRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->tahunAkademikRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->tahunAkademikRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->tahunAkademikRepository->delete($id);
    }
}
