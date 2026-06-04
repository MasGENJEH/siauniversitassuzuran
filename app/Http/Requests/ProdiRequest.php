<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProdiRequest extends FormRequest
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
            'id_fakultas' => 'required|integer|exists:fakultas,id',
            'kode_prodi' => 'required|string|max:4',
            'nama_prodi' => 'required|string|max:255',
            'jenjang' => 'required|string|max:2',
            'prefix_nim' => 'required|string|max:3',
        ];
    }

    public function messages()
    {
        return [
            'id_fakultas.required' => 'FAKULTAS induk wajib dipilih.',
            'id_fakultas.integer' => 'FAKULTAS harus berupa format angka.',
            'id_fakultas.exists' => 'FAKULTAS yang dipilih tidak valid atau tidak terdaftar di sistem.',

            'kode_prodi.required' => 'KODE PRODI wajib diisi.',
            'kode_prodi.string' => 'KODE PRODI harus berupa teks.',
            'kode_prodi.max' => 'KODE PRODI maksimal berjumlah 4 karakter.',

            'nama_prodi.required' => 'NAMA PRODI wajib diisi.',
            'nama_prodi.string' => 'NAMA PRODI harus berupa teks.',
            'nama_prodi.max' => 'NAMA PRODI maksimal berjumlah 255 karakter.',

            'jenjang.required' => 'JENJANG pendidikan (seperti S1/D3) wajib diisi.',
            'jenjang.string' => 'JENJANG harus berupa teks.',
            'jenjang.max' => 'JENJANG maksimal berjumlah 2 karakter.',

            'prefix_nim.required' => 'PREFIX NIM wajib diisi.',
            'prefix_nim.string' => 'PREFIX NIM harus berupa teks.',
            'prefix_nim.max' => 'PREFIX NIM harus tepat berjumlah 3 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('nama_prodi')) {
            $this->merge([
                'nama_prodi' => strtoupper($this->nama_prodi),
            ]);
        }
        if ($this->has('kode_prodi')) {
            $this->merge([
                'kode_prodi' => strtoupper($this->kode_prodi),
            ]);
        }
    }
}
