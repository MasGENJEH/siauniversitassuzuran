<?php

namespace App\Http\Controllers;

use App\Http\Requests\KelasKuliahRequest;
use App\Http\Resources\KelasKuliahResource;
use App\Services\KelasKuliahService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KelasKuliahController extends Controller
{
    private KelasKuliahService $kelasKuliahService;

    public function __construct(KelasKuliahService $kelasKuliahService)
    {
        $this->kelasKuliahService = $kelasKuliahService;
    }

    public function index()
    {
        $fields = ['*'];
        $kelasKuliah = $this->kelasKuliahService->getAll($fields);

        return response()->json(KelasKuliahResource::collection($kelasKuliah));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $kelasKuliah = $this->kelasKuliahService->getById($id, $fields);

            return response()->json(new KelasKuliahResource($kelasKuliah));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'kelas kuliah tidak ditemukan',
            ], 404);
        }
    }

    public function store(KelasKuliahRequest $request)
    {
        $kelasKuliah = $this->kelasKuliahService->create($request->validated());

        return response()->json(new KelasKuliahResource($kelasKuliah), 201);
    }

    public function update(KelasKuliahRequest $request, int $id)
    {
        try {
            $kelasKuliah = $this->kelasKuliahService->update($id, $request->validated());

            return response()->json(new KelasKuliahResource($kelasKuliah));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'kelas kuliah tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->kelasKuliahService->delete($id);

            return response()->json([
                'message' => 'kelas kuliah berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'kelas kuliah tidak ditemukan',
            ], 404);
        }
    }
}
