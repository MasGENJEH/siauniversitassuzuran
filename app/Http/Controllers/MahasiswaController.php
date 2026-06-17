<?php

namespace App\Http\Controllers;

use App\Http\Requests\MahasiswaRequest;
use App\Http\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use App\Services\MahasiswaService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class MahasiswaController extends Controller
{
    private MahasiswaService $mahasiswaService;

    public function __construct(MahasiswaService $mahasiswaService)
    {
        $this->mahasiswaService = $mahasiswaService;
    }

    public function index()
    {
        $user = auth()->user();
        $perPage = request()->query('size');

        if ($user && $user->hasRole('mahasiswa') && !$user->hasRole('admin')) {
            $query = Mahasiswa::with(['prodi:id,name,code', 'dosenPa:id,name,nidn'])
                ->where('user_id', $user->id);
            $mahasiswa = $perPage ? $query->paginate($perPage) : $query->get();

            return MahasiswaResource::collection($mahasiswa);
        }

        if ($user && $user->hasRole('dosen') && !$user->hasRole('admin')) {
            $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
            if ($dosen) {
                // Get classes taught by this dosen
                $kelasIds = \App\Models\DosenPengampu::where('lecturer_id', $dosen->id)->pluck('course_class_id');
                // Get students enrolled in those classes
                $studentIds = \App\Models\KelasMahasiswa::whereIn('course_class_id', $kelasIds)->pluck('student_id')->toArray();
                
                $query = Mahasiswa::with(['prodi:id,name,code', 'dosenPa:id,name,nidn'])
                    ->where('academic_advisor_id', $dosen->id)
                    ->orWhereIn('id', $studentIds);
                $mahasiswa = $perPage ? $query->paginate($perPage) : $query->get();
                return MahasiswaResource::collection($mahasiswa);
            }
            return response()->json([]);
        }

        $fields = ['*'];
        $mahasiswa = $this->mahasiswaService->getAll($fields, $perPage);

        return MahasiswaResource::collection($mahasiswa);
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
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photo-student', 'public');
        }

        $mahasiswa = $this->mahasiswaService->create($data);

        return response()->json(new MahasiswaResource($mahasiswa), 201);
    }

    public function update(MahasiswaRequest $request, int $id)
    {
        try {
            $data = $request->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                $oldMahasiswa = $this->mahasiswaService->getById($id, ['*']);
                if ($oldMahasiswa->photo) {
                    Storage::disk('public')->delete($oldMahasiswa->photo);
                }
                $data['photo'] = $request->file('photo')->store('photo-student', 'public');
            }

            $mahasiswa = $this->mahasiswaService->update($id, $data);

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
            // Delete photo file if exists
            $mahasiswa = $this->mahasiswaService->getById($id, ['*']);
            if ($mahasiswa->photo) {
                Storage::disk('public')->delete($mahasiswa->photo);
            }

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
