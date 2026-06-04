<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class DosenRequest extends FormRequest
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
        $dosenId = $this->route('dosen');

        return [
            'id_user' => [
                'required',
                'integer',
                'exists:users,id', // Memastikan ID User terdaftar di tabel users
            ],
            'nidn' => [
                'required',
                'string',
                'max:20',
                // Jika create: wajib unik di tabel dosen. Jika update: abaikan keunikan untuk ID dosen saat ini.
                'unique:dosens,nidn,'.$dosenId,
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_user.required' => 'AKUN USER pengikat dosen wajib dipilih.',
            'id_user.integer' => 'AKUN USER harus berupa angka.',
            'id_user.exists' => 'AKUN USER yang dipilih tidak valid atau tidak terdaftar.',

            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.string' => 'NIDN harus berupa teks.',
            'nidn.max' => 'NIDN maksimal berjumlah 20 karakter.',
            'nidn.unique' => 'NIDN sudah terdaftar di dalam sistem.',

            'nama.required' => 'NAMA DOSEN wajib diisi.',
            'nama.string' => 'NAMA DOSEN harus berupa teks.',
            'nama.max' => 'NAMA DOSEN maksimal berjumlah 255 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('nama')) {
            $this->merge([
                'nama' => strtoupper($this->nama),
            ]);
        }
    }
}
