<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class KelasKuliahRequest extends FormRequest
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
        $kelasKuliahId = $this->route('kelas_kuliah');

        return [
            'kode_kelas' => [
                'nullable',
                'string',
                'max:50',
                'unique:kelas_kuliahs,kode_kelas,' . $kelasKuliahId,
            ],
            'id_mk' => [
                'required',
                'integer',
                'exists:mata_kuliahs,id',
            ],
            'id_ta' => [
                'required',
                'integer',
                'exists:tahun_akademiks,id',
            ],
            'nama_kelas' => [
                'required',
                'string',
                'max:255',
            ],
            'hari' => [
                'required',
                'string',
                'max:50',
            ],
            'jam_mulai' => [
                'required',
                'date_format:H:i:s,H:i',
            ],
            'jam_selesai' => [
                'required',
                'date_format:H:i:s,H:i',
                'after:jam_mulai',
            ],
            'ruangan' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_kelas.required' => 'KODE KELAS wajib diisi.',
            'kode_kelas.string' => 'KODE KELAS harus berupa teks.',
            'kode_kelas.max' => 'KODE KELAS maksimal berjumlah 50 karakter.',
            'kode_kelas.unique' => 'KODE KELAS sudah terdaftar di dalam sistem.',

            'id_mk.required' => 'MATA KULIAH wajib dipilih.',
            'id_mk.integer' => 'MATA KULIAH tidak valid.',
            'id_mk.exists' => 'MATA KULIAH tidak terdaftar di dalam sistem.',

            'id_ta.required' => 'TAHUN AKADEMIK wajib dipilih.',
            'id_ta.integer' => 'TAHUN AKADEMIK tidak valid.',
            'id_ta.exists' => 'TAHUN AKADEMIK tidak terdaftar di dalam sistem.',

            'nama_kelas.required' => 'NAMA KELAS wajib diisi.',
            'nama_kelas.string' => 'NAMA KELAS harus berupa teks.',
            'nama_kelas.max' => 'NAMA KELAS maksimal berjumlah 255 karakter.',

            'hari.required' => 'HARI wajib diisi.',
            'hari.string' => 'HARI harus berupa teks.',
            'hari.max' => 'HARI maksimal berjumlah 50 karakter.',

            'jam_mulai.required' => 'JAM MULAI wajib diisi.',
            'jam_mulai.date_format' => 'JAM MULAI harus berformat jam (contoh: 08:00 atau 08:00:00).',

            'jam_selesai.required' => 'JAM SELESAI wajib diisi.',
            'jam_selesai.date_format' => 'JAM SELESAI harus berformat jam (contoh: 09:40 atau 09:40:00).',
            'jam_selesai.after' => 'JAM SELESAI harus setelah jam mulai.',

            'ruangan.required' => 'RUANGAN wajib diisi.',
            'ruangan.string' => 'RUANGAN harus berupa teks.',
            'ruangan.max' => 'RUANGAN maksimal berjumlah 255 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('hari')) {
            $this->merge([
                'hari' => strtoupper($this->hari),
            ]);
        }
        if ($this->has('kode_kelas')) {
            $this->merge([
                'kode_kelas' => strtoupper($this->kode_kelas),
            ]);
        }
        if ($this->has('nama_kelas')) {
            $this->merge([
                'nama_kelas' => strtoupper($this->nama_kelas),
            ]);
        }
        if ($this->has('ruangan')) {
            $this->merge([
                'ruangan' => strtoupper($this->ruangan),
            ]);
        }
    }
}
