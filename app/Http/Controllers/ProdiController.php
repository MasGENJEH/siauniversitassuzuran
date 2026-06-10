<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdiRequest;
use App\Http\Resources\ProdiResource;
use App\Services\ProdiService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProdiController extends Controller
{
    private ProdiService $prodiService;

    public function __construct(ProdiService $prodiService)
    {
        $this->prodiService = $prodiService;
    }

    public function index()
    {
        $fields = ['*'];
        $study_programs = $this->prodiService->getAll($fields);

        return response()->json(ProdiResource::collection($study_programs));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $study_programs = $this->prodiService->getById($id, $fields);

            return response()->json(new ProdiResource($study_programs));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'prodi tidak ditemukan',
            ], 404);
        }
    }

    public function store(ProdiRequest $request)
    {
        $prodi = $this->prodiService->create($request->validated());

        return response()->json(new ProdiResource($prodi), 201);
    }

    public function update(ProdiRequest $request, int $id)
    {
        try {
            $prodi = $this->prodiService->update($id, $request->validated());

            return response()->json(new ProdiResource($prodi), 201);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'prodi tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->prodiService->delete($id);

            return response()->json([
                'message' => 'prodi berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'prodi tidak ditemukan',
            ], 404);
        }
    }
}
