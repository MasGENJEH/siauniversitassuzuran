<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MahasiswaRequest extends FormRequest
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
        $mahasiswaId = $this->route('mahasiswa');

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id', // Validasi akun user terdaftar
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'study_program_id' => [
                'required',
                'integer',
                'exists:study-programs,id', // Validasi prodi wajib terdaftar di tabel prodi
            ],
            'academic_advisor_id' => [
                'required',
                'integer',
                'exists:lecturers,id', // Validasi dosen PA wajib terdaftar di tabel dosen
            ],
            'enrollment_year' => [
                'required',
                'digits:4', // Memastikan input tahun berupa 4 digit angka (cth: 2026)
                'integer',
            ],
            'status' => [
                'required',
                Rule::in(['AKTIF', 'CUTI', 'LULUS', 'DO']), // Membatasi input sesuai opsi ENUM database
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
            'user_id.required' => 'AKUN USER pengikat mahasiswa wajib dipilih.',
            'user_id.exists' => 'AKUN USER yang dipilih tidak terdaftar di sistem.',

            'name.required' => 'NAMA MAHASISWA wajib diisi.',
            'name.max' => 'NAMA MAHASISWA maksimal berjumlah 255 karakter.',

            'study_program_id.required' => 'PROGRAM STUDI wajib dipilih.',
            'study_program_id.exists' => 'PROGRAM STUDI yang dipilih tidak valid.',

            'academic_advisor_id.required' => 'DOSEN PEMBIMBING AKADEMIK (PA) wajib dipilih.',
            'academic_advisor_id.exists' => 'DOSEN PEMBIMBING AKADEMIK tidak valid.',

            'enrollment_year.required' => 'TAHUN MASUK wajib diisi.',
            'enrollment_year.digits' => 'TAHUN MASUK harus berupa 4 digit angka tahun (Contoh: 2026).',

            'status.required' => 'STATUS MAHASISWA wajib dipilih.',
            'status.in' => 'STATUS MAHASISWA harus berupa pilihan: AKTIF, CUTI, LULUS, atau DO.',

            'photo.image' => 'FOTO harus berupa file gambar.',
            'photo.mimes' => 'FOTO harus berformat JPEG, PNG, JPG, atau WEBP.',
            'photo.max' => 'Ukuran FOTO maksimal 2MB.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => $this->name ? strtoupper($this->name) : null,
            'status' => $this->status ? strtoupper($this->status) : null,
        ]);
    }
}
