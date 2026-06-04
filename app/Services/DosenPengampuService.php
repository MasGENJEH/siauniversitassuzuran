<?php

namespace App\Services;

use App\Repositories\DosenPengampuRepository;

class DosenPengampuService
{
    private DosenPengampuRepository $dosenPengampuRepository;

    public function __construct(DosenPengampuRepository $dosenPengampuRepository)
    {
        $this->dosenPengampuRepository = $dosenPengampuRepository;
    }

    public function getAll(array $fields)
    {
        return $this->dosenPengampuRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->dosenPengampuRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->dosenPengampuRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->dosenPengampuRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->dosenPengampuRepository->delete($id);
    }
}
