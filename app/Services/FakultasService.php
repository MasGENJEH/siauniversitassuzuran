<?php

namespace App\Services;

use App\Repositories\FakultasRepository;

class FakultasService
{
    private FakultasRepository $fakultasRepository;

    public function __construct(FakultasRepository $fakultasRepository)
    {
        $this->fakultasRepository = $fakultasRepository;
    }

    public function getAll(array $fields)
    {
        return $this->fakultasRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->fakultasRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->fakultasRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->fakultasRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->fakultasRepository->delete($id);
    }
}
