<?php

namespace App\Services;

use App\Helpers\MataKuliahHelper;
use App\Repositories\MataKuliahRepository;
use App\Repositories\ProdiRepository;

class MataKuliahService
{
    private MataKuliahRepository $mataKuliahRepository;
    private ProdiRepository $prodiRepository;

    public function __construct(MataKuliahRepository $mataKuliahRepository, ProdiRepository $prodiRepository)
    {
        $this->mataKuliahRepository = $mataKuliahRepository;
        $this->prodiRepository = $prodiRepository;
    }

    public function getAll(array $fields)
    {
        return $this->mataKuliahRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->mataKuliahRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        $prodi = $this->prodiRepository->getById($data['study_program_id']);

        $data['code'] = MataKuliahHelper::generateUniqueKodeKelas($prodi->code);

        return $this->mataKuliahRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->mataKuliahRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->mataKuliahRepository->delete($id);
    }
}
