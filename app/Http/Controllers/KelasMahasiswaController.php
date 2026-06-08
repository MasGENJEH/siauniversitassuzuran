<?php

namespace App\Http\Controllers;

use App\Http\Requests\KelasMahasiswaRequest;
use App\Http\Resources\KelasMahasiswaResource;
use App\Services\KelasMahasiswaService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class KelasMahasiswaController extends Controller
{
    private KelasMahasiswaService $kelasMahasiswaService;

    public function __construct(KelasMahasiswaService $kelasMahasiswaService)
    {
        $this->kelasMahasiswaService = $kelasMahasiswaService;
    }

    public function index()
    {
        $user = auth()->user();
        if ($user && $user->hasRole('mahasiswa')) {
            $mahasiswa = \App\Models\Mahasiswa::where('id_user', $user->id)->first();
            if ($mahasiswa) {
                $kelasMahasiswa = \App\Models\KelasMahasiswa::where('id_mahasiswa', $mahasiswa->id)->get();
                return response()->json(KelasMahasiswaResource::collection($kelasMahasiswa));
            }
            return response()->json([]);
        }

        $fields = ['*'];
        $kelasMahasiswa = $this->kelasMahasiswaService->getAll($fields);

        return response()->json(KelasMahasiswaResource::collection($kelasMahasiswa));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $kelasMahasiswa = $this->kelasMahasiswaService->getById($id, $fields);

            return response()->json(new KelasMahasiswaResource($kelasMahasiswa));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'kelas mahasiswa tidak ditemukan',
            ], 404);
        }
    }

    public function store(KelasMahasiswaRequest $request)
    {
        $kelasMahasiswa = $this->kelasMahasiswaService->create($request->validated());

        return response()->json(new KelasMahasiswaResource($kelasMahasiswa), 201);
    }

    public function update(KelasMahasiswaRequest $request, int $id)
    {
        try {
            $kelasMahasiswa = $this->kelasMahasiswaService->update($id, $request->validated());

            return response()->json(new KelasMahasiswaResource($kelasMahasiswa));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'kelas mahasiswa tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        $user = auth()->user();
        if ($user && ($user->hasRole('mahasiswa') || $user->hasRole('dosen'))) {
            return response()->json([
                'message' => 'Mahasiswa dan Dosen tidak diizinkan menghapus pendaftaran kelas (KRS).'
            ], 403);
        }

        try {
            $this->kelasMahasiswaService->delete($id);

            return response()->json([
                'message' => 'kelas mahasiswa berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'kelas mahasiswa tidak ditemukan',
            ], 404);
        }
    }
}
