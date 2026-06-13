<?php

namespace App\Http\Requests;

use App\Models\Dosen;
use App\Models\KelasMahasiswa;
use App\Models\Mahasiswa;
use Illuminate\Foundation\Http\FormRequest;

class KelasMahasiswaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = $this->user();
        if (! $user) {
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
                if (! $routeParam) {
                    return false;
                }

                if ($routeParam instanceof KelasMahasiswa) {
                    $kelasMahasiswa = $routeParam;
                } else {
                    $kelasMahasiswa = KelasMahasiswa::find($routeParam);
                }

                if (! $kelasMahasiswa) {
                    return false;
                }

                $dosen = Dosen::where('user_id', $user->id)->first();
                if (! $dosen) {
                    return false;
                }

                // Check if the Dosen is assigned to the class section of the enrollment
                return \DB::table('class_instructors')
                    ->where('lecturer_id', $dosen->id)
                    ->where('course_class_id', $kelasMahasiswa->course_class_id)
                    ->whereNull('deleted_at')
                    ->exists();
            }
        }

        // Mahasiswa can only create/update their own KRS
        if ($user->hasRole('mahasiswa')) {
            $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
            if (! $mahasiswa) {
                return false;
            }

            // For POST (store) - Mahasiswa can only register classes for themselves
            if ($this->isMethod('post')) {
                return (int) $this->input('student_id') === $mahasiswa->id;
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
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
            ],
            'course_class_id' => [
                'required',
                'integer',
                'exists:course_classes,id',
            ],
            'final_score' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],
            'letter_grade' => [
                'nullable',
                'string',
                'max:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.required' => 'MAHASISWA wajib dipilih.',
            'student_id.integer' => 'MAHASISWA tidak valid.',
            'student_id.exists' => 'MAHASISWA tidak terdaftar di dalam sistem.',

            'course_class_id.required' => 'KELAS KULIAH wajib dipilih.',
            'course_class_id.integer' => 'KELAS KULIAH tidak valid.',
            'course_class_id.exists' => 'KELAS KULIAH tidak terdaftar di dalam sistem.',

            'final_score.numeric' => 'NILAI AKHIR harus berupa angka.',
            'final_score.min' => 'NILAI AKHIR minimal adalah 0.',
            'final_score.max' => 'NILAI AKHIR maksimal adalah 100.',

            'letter_grade.max' => 'NILAI HURUF maksimal berjumlah 1 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'letter_grade' => $this->letter_grade ? strtoupper($this->letter_grade) : null,
        ]);
    }
}
