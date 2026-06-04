<?php

namespace App\Http\Controllers;

use App\Http\Requests\TahunAkademikRequest;
use App\Http\Resources\TahunAkademikResource;
use App\Services\TahunAkademikService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TahunAkademikController extends Controller
{
    private TahunAkademikService $tahunAkademikService;

    public function __construct(TahunAkademikService $tahunAkademikService)
    {
        $this->tahunAkademikService = $tahunAkademikService;
    }

    public function index()
    {
        $fields = ['*'];
        $tahunAkademik = $this->tahunAkademikService->getAll($fields);

        return response()->json(TahunAkademikResource::collection($tahunAkademik));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $tahunAkademik = $this->tahunAkademikService->getById($id, $fields);

            return response()->json(new TahunAkademikResource($tahunAkademik));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'tahun akademik tidak ditemukan',
            ], 404);
        }
    }

    public function store(TahunAkademikRequest $request)
    {
        $tahunAkademik = $this->tahunAkademikService->create($request->validated());

        return response()->json(new TahunAkademikResource($tahunAkademik), 201);
    }

    public function update(TahunAkademikRequest $request, int $id)
    {
        try {
            $tahunAkademik = $this->tahunAkademikService->update($id, $request->validated());

            return response()->json(new TahunAkademikResource($tahunAkademik));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'tahun akademik tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->tahunAkademikService->delete($id);

            return response()->json([
                'message' => 'tahun akademik berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'tahun akademik tidak ditemukan',
            ], 404);
        }
    }
}
