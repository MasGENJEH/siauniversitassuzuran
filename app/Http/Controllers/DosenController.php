<?php

namespace App\Http\Controllers;

use App\Http\Requests\DosenRequest;
use App\Http\Resources\DosenResource;
use App\Services\DosenService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DosenController extends Controller
{
    private DosenService $dosenService;

    public function __construct(DosenService $dosenService)
    {
        $this->dosenService = $dosenService;
    }

    public function index()
    {
        $fields = ['*'];
        $dosen = $this->dosenService->getAll($fields ?: ['*']);

        return response()->json(DosenResource::collection($dosen));
    }

    public function show(int $id)
    {
        try {
            $fields = ['*'];
            $dosen = $this->dosenService->getById($id, $fields);

            return response()->json(new DosenResource($dosen));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen tidak ditemukan',
            ], 404);
        }
    }

    public function store(DosenRequest $request)
    {
        $dosen = $this->dosenService->create($request->validated());

        return response()->json(new DosenResource($dosen), 201);
    }

    public function update(DosenRequest $request, int $id)
    {
        try {
            $dosen = $this->dosenService->update($id, $request->validated());

            return response()->json(new DosenResource($dosen));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen tidak ditemukan',
            ], 404);
        }
    }

    public function destroy(int $id)
    {
        try {
            $this->dosenService->delete($id);

            return response()->json([
                'message' => 'dosen berhasil dihapus',
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen tidak ditemukan',
            ], 404);
        }
    }

    public function kelasKuliahAktif(int $id)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('dosen')) {
            $dosen = \App\Models\Dosen::where('id_user', $user->id)->first();
            if (!$dosen || $dosen->id !== $id) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke kelas dosen lain.'
                ], 403);
            }
        }

        try {
            $kelas = $this->dosenService->getKelasKuliahAktif($id);

            return response()->json(\App\Http\Resources\KelasKuliahResource::collection($kelas));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen tidak ditemukan',
            ], 404);
        }
    }

    public function mahasiswaBimbingan(int $id)
    {
        $user = auth()->user();
        if ($user && $user->hasRole('dosen')) {
            $dosen = \App\Models\Dosen::where('id_user', $user->id)->first();
            if (!$dosen || $dosen->id !== $id) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke data bimbingan dosen lain.'
                ], 403);
            }
        }

        try {
            $dosen = \App\Models\Dosen::findOrFail($id);
            $mahasiswas = \App\Models\Mahasiswa::where('id_dosen_pa', $dosen->id)->get();

            return response()->json(\App\Http\Resources\MahasiswaResource::collection($mahasiswas));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen tidak ditemukan',
            ], 404);
        }
    }
}
