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
            'user_id' => [
                'required',
                'integer',
                'exists:users,id', // Memastikan ID User terdaftar di tabel users
            ],
            'nidn' => [
                'required',
                'string',
                'max:20',
                // Jika create: wajib unik di tabel dosen. Jika update: abaikan keunikan untuk ID dosen saat ini.
                'unique:lecturers,nidn,'.$dosenId,
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'photo' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048', // Max 2MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'AKUN USER pengikat dosen wajib dipilih.',
            'user_id.integer' => 'AKUN USER harus berupa angka.',
            'user_id.exists' => 'AKUN USER yang dipilih tidak valid atau tidak terdaftar.',

            'nidn.required' => 'NIDN wajib diisi.',
            'nidn.string' => 'NIDN harus berupa teks.',
            'nidn.max' => 'NIDN maksimal berjumlah 20 karakter.',
            'nidn.unique' => 'NIDN sudah terdaftar di dalam sistem.',

            'name.required' => 'NAMA DOSEN wajib diisi.',
            'name.string' => 'NAMA DOSEN harus berupa teks.',
            'name.max' => 'NAMA DOSEN maksimal berjumlah 255 karakter.',

            'photo.image' => 'FOTO harus berupa file gambar.',
            'photo.mimes' => 'FOTO harus berformat JPEG, PNG, JPG, atau WEBP.',
            'photo.max' => 'Ukuran FOTO maksimal 2MB.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strtoupper($this->name),
            ]);
        }
    }
}
