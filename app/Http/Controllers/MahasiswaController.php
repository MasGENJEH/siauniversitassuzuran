<?php

namespace App\Http\Controllers;

use App\Http\Requests\MahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Services\MahasiswaService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MahasiswaController extends Controller
{
    private MahasiswaService $mahasiswaService;

    public function __construct(MahasiswaService $mahasiswaService)
    {
        $this->mahasiswaService = $mahasiswaService;
    }

    public function index()
    {
        $fields = ['*'];
        $mahasiswa = $this->mahasiswaService->getAll($fields);

        return response()->json(MahasiswaResource::collection($mahasiswa));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $mahasiswa = $this->mahasiswaService->getById($id, $fields);

            return response()->json(new MahasiswaResource($mahasiswa));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'mahasiswa tidak ditemukan',
            ], 404);
        }
    }

    public function store(MahasiswaRequest $request)
    {
        $mahasiswa = $this->mahasiswaService->create($request->validated());

        return response()->json(new MahasiswaResource($mahasiswa), 201);
    }

    public function update(MahasiswaRequest $request, int $id)
    {
        try {
            $mahasiswa = $this->mahasiswaService->update($id, $request->validated());

            return response()->json(new MahasiswaResource($mahasiswa));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'mahasiswa tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->mahasiswaService->delete($id);

            return response()->json([
                'message' => 'mahasiswa berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'mahasiswa tidak ditemukan',
            ], 404);
        }
    }
}
