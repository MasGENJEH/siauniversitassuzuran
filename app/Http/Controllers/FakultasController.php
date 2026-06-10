<?php

namespace App\Http\Controllers;

use App\Http\Requests\FakultasRequest;
use App\Http\Resources\FakultasResource;
use App\Services\FakultasService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FakultasController extends Controller
{
    private FakultasService $facultiesService;

    public function __construct(FakultasService $facultiesService)
    {
        $this->facultiesService = $facultiesService;
    }

    public function index()
    {
        $fields = ['*'];
        $faculties = $this->facultiesService->getAll($fields ?: ['*']);

        return response()->json(FakultasResource::collection($faculties));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $faculties = $this->facultiesService->getById($id, $fields);

            return response()->json(new FakultasResource($faculties));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'faculties tidak ditemukan',
            ], 404);
        }
    }

    public function store(FakultasRequest $request)
    {
        $faculties = $this->facultiesService->create($request->validated());

        return response()->json(new FakultasResource($faculties), 201);
    }

    public function update(FakultasRequest $request, int $id)
    {
        try {
            $faculties = $this->facultiesService->update($id, $request->validated());

            return response()->json(new FakultasResource($faculties));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'faculties tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->facultiesService->delete($id);

            return response()->json([
                'message' => 'faculties berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'faculties tidak ditemukan',
            ], 404);
        }
    }
}
