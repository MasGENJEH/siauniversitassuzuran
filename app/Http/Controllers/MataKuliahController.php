<?php

namespace App\Http\Controllers;

use App\Http\Requests\MataKuliahRequest;
use App\Http\Resources\MataKuliahResource;
use App\Services\MataKuliahService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MataKuliahController extends Controller
{
    private MataKuliahService $mataKuliahService;

    public function __construct(MataKuliahService $mataKuliahService)
    {
        $this->mataKuliahService = $mataKuliahService;
    }

    public function index()
    {
        $fields = ['*'];
        $mataKuliah = $this->mataKuliahService->getAll($fields);

        return response()->json(MataKuliahResource::collection($mataKuliah));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $mataKuliah = $this->mataKuliahService->getById($id, $fields);

            return response()->json(new MataKuliahResource($mataKuliah));
        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'mata kuliah tidak ada',
            ], 404);
        }
    }

    public function store(MataKuliahRequest $request)
    {
        $mataKuliah = $this->mataKuliahService->create($request->validated());

        return response()->json(new MataKuliahResource($mataKuliah));
    }

    public function update(MataKuliahRequest $request, int $id)
    {
        try {
            $mataKuliah = $this->mataKuliahService->update($id, $request->validated());

            return response()->json(new MataKuliahResource($mataKuliah));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'mata kuliah tidak ada',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->mataKuliahService->delete($id);

            return response()->json([
                'message' => 'mata kuliah berhasil dihapus',
            ], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'mata kuliah tidak ada',
            ], 404);
        }
    }
}
