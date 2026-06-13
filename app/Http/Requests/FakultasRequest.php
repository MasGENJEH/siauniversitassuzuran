<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FakultasRequest extends FormRequest
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
        $facultiesId = $this->route('faculty');

        return [
            'code' => [
                'required',
                'string',
                'max:10',
                // Jika create: harus unik di tabel faculties. Jika update: abaikan unik untuk ID diri sendiri.
                'unique:faculties,code,'.$facultiesId,
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'code.required' => 'KODE FAKULTAS wajib diisi.',
            'code.string' => 'KODE FAKULTAS harus berupa teks.',
            'code.max' => 'KODE FAKULTAS maksimal berjumlah 10 karakter.',
            'code.unique' => 'KODE FAKULTAS sudah terdaftar di sistem.',

            'name.required' => 'NAMA FAKULTAS wajib diisi.',
            'name.string' => 'NAMA FAKULTAS harus berupa teks.',
            'name.max' => 'NAMA FAKULTAS maksimal berjumlah 255 karakter.',
        ];
    }
}
