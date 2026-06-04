<?php

namespace App\Services;

use App\Helpers\KelasKuliahHelper;
use App\Models\MataKuliah;
use App\Repositories\KelasKuliahRepository;

class KelasKuliahService
{
    private KelasKuliahRepository $kelasKuliahRepository;

    public function __construct(KelasKuliahRepository $kelasKuliahRepository)
    {
        $this->kelasKuliahRepository = $kelasKuliahRepository;
    }

    public function getAll(array $fields)
    {
        return $this->kelasKuliahRepository->getAll($fields);
    }

    public function getById(int $id, array $fields)
    {
        return $this->kelasKuliahRepository->getById($id, $fields);
    }

    public function create(array $data)
    {
        $mataKuliah = MataKuliah::findOrFail($data['id_mk']);
        $data['kode_kelas'] = KelasKuliahHelper::generateUniqueKodeKelas($mataKuliah->kode_mk);

        return $this->kelasKuliahRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->kelasKuliahRepository->update($id, $data);
    }

    public function delete(int $id)
    {
        return $this->kelasKuliahRepository->delete($id);
    }
}
