<?php

namespace App\Services;

use App\Repositories\ProdiRepository;

class ProdiService
{
    private ProdiRepository $prodiRepository;

    public function __construct(ProdiRepository $prodiRepository)
    {
        $this->prodiRepository = $prodiRepository;
    }

    public function getAll(array $fields)
    {
        return $this->prodiRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->prodiRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->prodiRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->prodiRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->prodiRepository->delete($id);
    }
}
