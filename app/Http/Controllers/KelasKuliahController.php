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
        $user = auth()->user();
        if ($user && $user->hasRole('mahasiswa') && !$user->hasRole('admin')) {
            $mhs = \App\Models\Mahasiswa::where('user_id', $user->id)->first();
            if ($mhs) {
                $kelasIds = \App\Models\KelasMahasiswa::where('student_id', $mhs->id)->pluck('course_class_id');
                $kelasKuliah = \App\Models\KelasKuliah::with(['mataKuliah:id,code,name,sks', 'tahunAkademik:id,code,name,status'])->whereIn('id', $kelasIds)->get();
                return response()->json(KelasKuliahResource::collection($kelasKuliah));
            }
            return response()->json([]);
        }

        if ($user && $user->hasRole('dosen') && !$user->hasRole('admin')) {
            $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                $kelasIds = \App\Models\DosenPengampu::where('lecturer_id', $dosen->id)->pluck('course_class_id');
                $kelasKuliah = \App\Models\KelasKuliah::with(['mataKuliah:id,code,name,sks', 'tahunAkademik:id,code,name,status'])->whereIn('id', $kelasIds)->get();
                return response()->json(KelasKuliahResource::collection($kelasKuliah));
            }
            return response()->json([]);
        }

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
