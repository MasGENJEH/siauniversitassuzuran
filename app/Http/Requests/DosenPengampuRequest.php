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
            'id_kelas' => [
                'required',
                'integer',
                'exists:kelas_kuliahs,id',
            ],
            'id_dosen' => [
                'required',
                'integer',
                'exists:dosens,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_kelas.required' => 'KELAS KULIAH wajib dipilih.',
            'id_kelas.integer' => 'KELAS KULIAH tidak valid.',
            'id_kelas.exists' => 'KELAS KULIAH tidak terdaftar di dalam sistem.',

            'id_dosen.required' => 'DOSEN wajib dipilih.',
            'id_dosen.integer' => 'DOSEN tidak valid.',
            'id_dosen.exists' => 'DOSEN tidak terdaftar di dalam sistem.',
        ];
    }
}
