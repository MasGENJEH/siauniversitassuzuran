<?php

namespace App\Services;

use App\Repositories\DosenRepository;

class DosenService
{
    private DosenRepository $dosenRepository;

    public function __construct(DosenRepository $dosenRepository)
    {
        $this->dosenRepository = $dosenRepository;
    }

    public function getAll(array $fields, $perPage = null)
    {
        return $this->dosenRepository->getAll($fields, $perPage);
    }

    public function getById(int $id, array $fields)
    {
        return $this->dosenRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        return $this->dosenRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->dosenRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->dosenRepository->delete($id);
    }

    public function getKelasKuliahAktif(int $id)
    {
        return $this->dosenRepository->getKelasKuliahAktif($id);
    }
}
