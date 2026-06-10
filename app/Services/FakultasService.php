<?php

namespace App\Services;

use App\Repositories\FakultasRepository;

class FakultasService
{
    private FakultasRepository $facultiesRepository;

    public function __construct(FakultasRepository $facultiesRepository)
    {
        $this->facultiesRepository = $facultiesRepository;
    }

    public function getAll(array $fields)
    {
        return $this->facultiesRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->facultiesRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->facultiesRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->facultiesRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->facultiesRepository->delete($id);
    }
}
