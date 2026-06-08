<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KelasMahasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (!$user) {
            return false;
        }

        // Admin can perform any action
        if ($user->hasRole('admin')) {
            return true;
        }

        // Dosen can only update grades for their own classes
        if ($user->hasRole('dosen')) {
            // For POST (store) - Dosen is NOT allowed to register students to classes
            if ($this->isMethod('post')) {
                return false;
            }

            // For PUT/PATCH (update)
            if ($this->isMethod('put') || $this->isMethod('patch')) {
                $routeParam = $this->route('kelas_mahasiswa');
                if (!$routeParam) {
                    return false;
                }

                if ($routeParam instanceof \App\Models\KelasMahasiswa) {
                    $kelasMahasiswa = $routeParam;
                } else {
                    $kelasMahasiswa = \App\Models\KelasMahasiswa::find($routeParam);
                }

                if (!$kelasMahasiswa) {
                    return false;
                }

                $dosen = \App\Models\Dosen::where('id_user', $user->id)->first();
                if (!$dosen) {
                    return false;
                }

                // Check if the Dosen is assigned to the class section of the enrollment
                return \DB::table('dosen_pengampus')
                    ->where('id_dosen', $dosen->id)
                    ->where('id_kelas', $kelasMahasiswa->id_kelas)
                    ->whereNull('deleted_at')
                    ->exists();
            }
        }

        // Mahasiswa can only create/update their own KRS
        if ($user->hasRole('mahasiswa')) {
            $mahasiswa = \App\Models\Mahasiswa::where('id_user', $user->id)->first();
            if (!$mahasiswa) {
                return false;
            }

            // For POST (store) - Mahasiswa can only register classes for themselves
            if ($this->isMethod('post')) {
                return (int)$this->input('id_mahasiswa') === $mahasiswa->id;
            }

            // For PUT/PATCH (update) - Mahasiswa is NOT allowed to update KRS records
            if ($this->isMethod('put') || $this->isMethod('patch')) {
                return false;
            }
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id_mahasiswa' => [
                'required',
                'integer',
                'exists:mahasiswas,id',
            ],
            'id_kelas' => [
                'required',
                'integer',
                'exists:kelas_kuliahs,id',
            ],
            'nilai_akhir' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'nilai_huruf' => [
                'nullable',
                'string',
                'max:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_mahasiswa.required' => 'MAHASISWA wajib dipilih.',
            'id_mahasiswa.integer' => 'MAHASISWA tidak valid.',
            'id_mahasiswa.exists' => 'MAHASISWA tidak terdaftar di dalam sistem.',

            'id_kelas.required' => 'KELAS KULIAH wajib dipilih.',
            'id_kelas.integer' => 'KELAS KULIAH tidak valid.',
            'id_kelas.exists' => 'KELAS KULIAH tidak terdaftar di dalam sistem.',

            'nilai_akhir.numeric' => 'NILAI AKHIR harus berupa angka.',
            'nilai_akhir.min' => 'NILAI AKHIR minimal adalah 0.',
            'nilai_akhir.max' => 'NILAI AKHIR maksimal adalah 100.',

            'nilai_huruf.max' => 'NILAI HURUF maksimal berjumlah 1 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nilai_huruf' => $this->nilai_huruf ? strtoupper($this->nilai_huruf) : null,
        ]);
    }
}
