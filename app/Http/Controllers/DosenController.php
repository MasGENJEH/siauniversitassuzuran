<?php

namespace App\Http\Controllers;

use App\Http\Requests\DosenRequest;
use App\Http\Resources\DosenResource;
use App\Models\Dosen;
use App\Services\DosenService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;

class DosenController extends Controller
{
    private DosenService $dosenService;

    public function __construct(DosenService $dosenService)
    {
        $this->dosenService = $dosenService;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user && $user->hasRole('mahasiswa') && !$user->hasRole('admin')) {
            $mhs = \App\Models\Mahasiswa::where('user_id', $user->id)->first();
            if ($mhs) {
                $kelasIds = \App\Models\KelasMahasiswa::where('student_id', $mhs->id)->pluck('course_class_id');
                $lecturerIds = \App\Models\DosenPengampu::whereIn('course_class_id', $kelasIds)->pluck('lecturer_id')->toArray();
                
                if ($mhs->academic_advisor_id) {
                    $lecturerIds[] = $mhs->academic_advisor_id;
                }

                $dosen = Dosen::with(['user'])->whereIn('id', array_unique($lecturerIds))->get();
                return response()->json(DosenResource::collection($dosen));
            }
            return response()->json([]);
        }

        if ($user && $user->hasRole('dosen') && !$user->hasRole('admin')) {
            $dosenModel = Dosen::where('user_id', $user->id)->first();
            if ($dosenModel) {
                $kelasIds = \App\Models\DosenPengampu::where('lecturer_id', $dosenModel->id)->pluck('course_class_id');
                $lecturerIds = \App\Models\DosenPengampu::whereIn('course_class_id', $kelasIds)->pluck('lecturer_id')->toArray();
                
                $lecturerIds[] = $dosenModel->id;

                $dosenList = Dosen::with(['user'])->whereIn('id', array_unique($lecturerIds))->get();
                return response()->json(DosenResource::collection($dosenList));
            }
            return response()->json([]);
        }

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
        $data = $request->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photo-lecturer', 'public');
        }

        $dosen = $this->dosenService->create($data);

        return response()->json(new DosenResource($dosen), 201);
    }

    public function update(DosenRequest $request, int $id)
    {
        try {
            $data = $request->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                $oldDosen = $this->dosenService->getById($id, ['*']);
                if ($oldDosen->photo) {
                    Storage::disk('public')->delete($oldDosen->photo);
                }
                $data['photo'] = $request->file('photo')->store('photo-lecturer', 'public');
            }

            $dosen = $this->dosenService->update($id, $data);

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
            // Delete photo file if exists
            $dosen = $this->dosenService->getById($id, ['*']);
            if ($dosen->photo) {
                Storage::disk('public')->delete($dosen->photo);
            }

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
            $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
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
            $dosen = \App\Models\Dosen::where('user_id', $user->id)->first();
            if (!$dosen || $dosen->id !== $id) {
                return response()->json([
                    'message' => 'Anda tidak memiliki akses ke data bimbingan dosen lain.'
                ], 403);
            }
        }

        try {
            $dosen = \App\Models\Dosen::findOrFail($id);
            $students = \App\Models\Mahasiswa::where('academic_advisor_id', $dosen->id)->get();

            return response()->json(\App\Http\Resources\MahasiswaResource::collection($students));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'dosen tidak ditemukan',
            ], 404);
        }
    }
}
