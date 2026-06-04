<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        $userId = $this->route('user');

        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                // Jika create: wajib unik. Jika update: abaikan keunikan untuk ID user saat ini.
                'unique:users,email,'.$userId,
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
            ],
            'photo' => [
                'nullable',
                'string', // Mengakomodasi nama file gambar, ganti ke 'image|mimes:jpeg,png,jpg|max:2048' jika berupa file upload langsung
            ],
        ];

        // Aturan khusus untuk password: Wajib saat CREATE, opsional saat UPDATE (boleh dikosongkan jika tidak ganti password)
        if ($this->isMethod('post')) {
            $rules['password'] = ['required', 'string', 'min:8'];
        } else {
            $rules['password'] = ['nullable', 'string', 'min:8'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'NAMA LENGKAP wajib diisi.',
            'name.string' => 'NAMA LENGKAP harus berupa teks.',
            'name.max' => 'NAMA LENGKAP maksimal berjumlah 255 karakter.',

            'email.required' => 'EMAIL wajib diisi.',
            'email.string' => 'EMAIL harus berupa teks.',
            'email.email' => 'Format EMAIL tidak valid (gunakan contoh: nama@kampus.ac.id).',
            'email.max' => 'EMAIL maksimal berjumlah 255 karakter.',
            'email.unique' => 'EMAIL sudah digunakan oleh akun lain.',

            'password.required' => 'PASSWORD wajib diisi.',
            'password.string' => 'PASSWORD harus berupa teks.',
            'password.min' => 'PASSWORD minimal berjumlah 8 karakter.',

            'phone.max' => 'NOMOR TELEPON maksimal berjumlah 20 karakter.',
        ];
    }
}
