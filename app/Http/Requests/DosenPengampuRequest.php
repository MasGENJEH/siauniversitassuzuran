<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DosenPengampuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_class_id' => [
                'required',
                'integer',
                'exists:kelas_kuliahs,id',
            ],
            'lecturer_id' => [
                'required',
                'integer',
                'exists:lecturers,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'course_class_id.required' => 'KELAS KULIAH wajib dipilih.',
            'course_class_id.integer' => 'KELAS KULIAH tidak valid.',
            'course_class_id.exists' => 'KELAS KULIAH tidak terdaftar di dalam sistem.',

            'lecturer_id.required' => 'DOSEN wajib dipilih.',
            'lecturer_id.integer' => 'DOSEN tidak valid.',
            'lecturer_id.exists' => 'DOSEN tidak terdaftar di dalam sistem.',
        ];
    }
}
