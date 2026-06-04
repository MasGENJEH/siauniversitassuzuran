<?php

namespace App\Services;

use App\Repositories\KelasMahasiswaRepository;

class KelasMahasiswaService
{
    private KelasMahasiswaRepository $kelasMahasiswaRepository;

    public function __construct(KelasMahasiswaRepository $kelasMahasiswaRepository)
    {
        $this->kelasMahasiswaRepository = $kelasMahasiswaRepository;
    }

    public function getAll(array $fields)
    {
        return $this->kelasMahasiswaRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->kelasMahasiswaRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->kelasMahasiswaRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->kelasMahasiswaRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->kelasMahasiswaRepository->delete($id);
    }
}
