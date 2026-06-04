<?php

namespace App\Http\Controllers;

use App\Http\Requests\DosenPengampuRequest;
use App\Http\Resources\DosenPengampuResource;
use App\Services\DosenPengampuService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DosenPengampuController extends Controller
{
    private DosenPengampuService $dosenPengampuService;

    public function __construct(DosenPengampuService $dosenPengampuService)
    {
        $this->dosenPengampuService = $dosenPengampuService;
    }

    public function index()
    {
        $fields = ['*'];
        $dosenPengampu = $this->dosenPengampuService->getAll($fields);

        return response()->json(DosenPengampuResource::collection($dosenPengampu));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $dosenPengampu = $this->dosenPengampuService->getById($id, $fields);

            return response()->json(new DosenPengampuResource($dosenPengampu));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen pengampu tidak ditemukan',
            ], 404);
        }
    }

    public function store(DosenPengampuRequest $request)
    {
        $dosenPengampu = $this->dosenPengampuService->create($request->validated());

        return response()->json(new DosenPengampuResource($dosenPengampu), 201);
    }

    public function update(DosenPengampuRequest $request, int $id)
    {
        try {
            $dosenPengampu = $this->dosenPengampuService->update($id, $request->validated());

            return response()->json(new DosenPengampuResource($dosenPengampu));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen pengampu tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->dosenPengampuService->delete($id);

            return response()->json([
                'message' => 'dosen pengampu berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen pengampu tidak ditemukan',
            ], 404);
        }
    }
}
