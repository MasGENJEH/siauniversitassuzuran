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
        return true;
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
