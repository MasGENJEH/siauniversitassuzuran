<?php

namespace App\Services;

use App\Helpers\MahasiswaHelper;
use App\Models\Prodi;
use App\Repositories\MahasiswaRepository;

class MahasiswaService
{
    private MahasiswaRepository $dosenRepository;

    public function __construct(MahasiswaRepository $dosenRepository)
    {
        $this->dosenRepository = $dosenRepository;
    }

    public function getAll(array $fields)
    {
        return $this->dosenRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->dosenRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        $prodi = Prodi::findOrFail($data['id_prodi']);

        $nimOtomatis = MahasiswaHelper::generateUniqueNim($prodi->kode_prodi);

        $data['nim'] = $nimOtomatis;

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
}
