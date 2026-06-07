<?php

namespace App\Services;

use App\Helpers\MahasiswaHelper;
use App\Models\Prodi;
use App\Repositories\MahasiswaRepository;

class MahasiswaService
{
    private MahasiswaRepository $mahasiswaRepository;

    public function __construct(MahasiswaRepository $mahasiswaRepository)
    {
        $this->mahasiswaRepository = $mahasiswaRepository;
    }

    public function getAll(array $fields)
    {
        return $this->mahasiswaRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->mahasiswaRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        $prodi = Prodi::findOrFail($data['id_prodi']);

        $nimOtomatis = MahasiswaHelper::generateUniqueNim($prodi->kode_prodi);

        $data['nim'] = $nimOtomatis;

        return $this->mahasiswaRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->mahasiswaRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->mahasiswaRepository->delete($id);
    }
}
