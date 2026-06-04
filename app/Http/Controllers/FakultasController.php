<?php

namespace App\Http\Controllers;

use App\Http\Requests\FakultasRequest;
use App\Http\Resources\FakultasResource;
use App\Services\FakultasService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FakultasController extends Controller
{
    private FakultasService $fakultasService;

    public function __construct(FakultasService $fakultasService)
    {
        $this->fakultasService = $fakultasService;
    }

    public function index()
    {
        $fields = ['*'];
        $fakultas = $this->fakultasService->getAll($fields ?: ['*']);

        return response()->json(FakultasResource::collection($fakultas));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $fakultas = $this->fakultasService->getById($id, $fields);

            return response()->json(new FakultasResource($fakultas));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'fakultas tidak ditemukan',
            ], 404);
        }
    }

    public function store(FakultasRequest $request)
    {
        $fakultas = $this->fakultasService->create($request->validated());

        return response()->json(new FakultasResource($fakultas), 201);
    }

    public function update(FakultasRequest $request, int $id)
    {
        try {
            $fakultas = $this->fakultasService->update($id, $request->validated());

            return response()->json(new FakultasResource($fakultas));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'fakultas tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->fakultasService->delete($id);

            return response()->json([
                'message' => 'fakultas berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'fakultas tidak ditemukan',
            ], 404);
        }
    }
}
