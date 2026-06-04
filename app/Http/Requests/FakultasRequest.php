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
        $fakultasId = $this->route('fakultas');

        return [
            'kode_fakultas' => [
                'required',
                'string',
                'max:10',
                // Jika create: harus unik di tabel fakultas. Jika update: abaikan unik untuk ID diri sendiri.
                'unique:fakultas,kode_fakultas,'.$fakultasId,
            ],
            'nama_fakultas' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_fakultas.required' => 'KODE FAKULTAS wajib diisi.',
            'kode_fakultas.string' => 'KODE FAKULTAS harus berupa teks.',
            'kode_fakultas.max' => 'KODE FAKULTAS maksimal berjumlah 10 karakter.',
            'kode_fakultas.unique' => 'KODE FAKULTAS sudah terdaftar di sistem.',

            'nama_fakultas.required' => 'NAMA FAKULTAS wajib diisi.',
            'nama_fakultas.string' => 'NAMA FAKULTAS harus berupa teks.',
            'nama_fakultas.max' => 'NAMA FAKULTAS maksimal berjumlah 255 karakter.',
        ];
    }
}
