<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TahunAkademikRequest extends FormRequest
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
        $tahunAkademikId = $this->route('tahun_akademik');

        return [
            'kode_ta' => [
                'required',
                'string',
                'max:50',
                'unique:tahun_akademiks,kode_ta,' . $tahunAkademikId,
            ],
            'nama_ta' => [
                'required',
                'string',
                'max:255',
            ],
            'status' => [
                'sometimes',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'kode_ta.required' => 'KODE TAHUN AKADEMIK wajib diisi.',
            'kode_ta.string' => 'KODE TAHUN AKADEMIK harus berupa teks.',
            'kode_ta.max' => 'KODE TAHUN AKADEMIK maksimal berjumlah 50 karakter.',
            'kode_ta.unique' => 'KODE TAHUN AKADEMIK sudah terdaftar di dalam sistem.',

            'nama_ta.required' => 'NAMA TAHUN AKADEMIK wajib diisi.',
            'nama_ta.string' => 'NAMA TAHUN AKADEMIK harus berupa teks.',
            'nama_ta.max' => 'NAMA TAHUN AKADEMIK maksimal berjumlah 255 karakter.',

            'status.boolean' => 'STATUS harus bernilai true atau false (1 atau 0).',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('nama_ta')) {
            $this->merge([
                'nama_ta' => strtoupper($this->nama_ta),
            ]);
        }
        if ($this->has('kode_ta')) {
            $this->merge([
                'kode_ta' => strtoupper($this->kode_ta),
            ]);
        }
    }
}
