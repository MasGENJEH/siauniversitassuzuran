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
            'id_user' => [
                'required',
                'integer',
                'exists:users,id', // Validasi akun user terdaftar
            ],
            'nama' => [
                'required',
                'string',
                'max:255',
            ],
            'id_prodi' => [
                'required',
                'integer',
                'exists:prodis,id', // Validasi prodi wajib terdaftar di tabel prodi
            ],
            'id_dosen_pa' => [
                'required',
                'integer',
                'exists:dosens,id', // Validasi dosen PA wajib terdaftar di tabel dosen
            ],
            'tahun_masuk' => [
                'required',
                'digits:4', // Memastikan input tahun berupa 4 digit angka (cth: 2026)
                'integer',
            ],
            'status_mahasiswa' => [
                'required',
                Rule::in(['AKTIF', 'CUTI', 'LULUS', 'DO']), // Membatasi input sesuai opsi ENUM database
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_user.required' => 'AKUN USER pengikat mahasiswa wajib dipilih.',
            'id_user.exists' => 'AKUN USER yang dipilih tidak terdaftar di sistem.',

            'nama.required' => 'NAMA MAHASISWA wajib diisi.',
            'nama.max' => 'NAMA MAHASISWA maksimal berjumlah 255 karakter.',

            'id_prodi.required' => 'PROGRAM STUDI wajib dipilih.',
            'id_prodi.exists' => 'PROGRAM STUDI yang dipilih tidak valid.',

            'id_dosen_pa.required' => 'DOSEN PEMBIMBING AKADEMIK (PA) wajib dipilih.',
            'id_dosen_pa.exists' => 'DOSEN PEMBIMBING AKADEMIK tidak valid.',

            'tahun_masuk.required' => 'TAHUN MASUK wajib diisi.',
            'tahun_masuk.digits' => 'TAHUN MASUK harus berupa 4 digit angka tahun (Contoh: 2026).',

            'status_mahasiswa.required' => 'STATUS MAHASISWA wajib dipilih.',
            'status_mahasiswa.in' => 'STATUS MAHASISWA harus berupa pilihan: AKTIF, CUTI, LULUS, atau DO.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'nama' => $this->nama ? strtoupper($this->nama) : null,
            'status_mahasiswa' => $this->status_mahasiswa ? strtoupper($this->status_mahasiswa) : null,
        ]);
    }
}
