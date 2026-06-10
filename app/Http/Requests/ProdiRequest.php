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
            'faculty_id' => 'required|integer|exists:faculties,id',
            'code' => 'required|string|max:4',
            'name' => 'required|string|max:255',
            'jenjang' => 'required|string|max:2',
            'nim_prefix' => 'required|string|max:3',
        ];
    }

    public function messages()
    {
        return [
            'faculty_id.required' => 'FAKULTAS induk wajib dipilih.',
            'faculty_id.integer' => 'FAKULTAS harus berupa format angka.',
            'faculty_id.exists' => 'FAKULTAS yang dipilih tidak valid atau tidak terdaftar di sistem.',

            'code.required' => 'KODE PRODI wajib diisi.',
            'code.string' => 'KODE PRODI harus berupa teks.',
            'code.max' => 'KODE PRODI maksimal berjumlah 4 karakter.',

            'name.required' => 'NAMA PRODI wajib diisi.',
            'name.string' => 'NAMA PRODI harus berupa teks.',
            'name.max' => 'NAMA PRODI maksimal berjumlah 255 karakter.',

            'jenjang.required' => 'JENJANG pendidikan (seperti S1/D3) wajib diisi.',
            'jenjang.string' => 'JENJANG harus berupa teks.',
            'jenjang.max' => 'JENJANG maksimal berjumlah 2 karakter.',

            'nim_prefix.required' => 'PREFIX NIM wajib diisi.',
            'nim_prefix.string' => 'PREFIX NIM harus berupa teks.',
            'nim_prefix.max' => 'PREFIX NIM harus tepat berjumlah 3 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strtoupper($this->name),
            ]);
        }
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper($this->code),
            ]);
        }
    }
}
