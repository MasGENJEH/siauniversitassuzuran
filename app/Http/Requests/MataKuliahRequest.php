<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MataKuliahRequest extends FormRequest
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
        $mataKuliahId = $this->route('mata_kuliah');

        return [
            'id_prodi' => [
                'required',
                'integer',
                'exists:prodis,id', // Memastikan prodi pengampu wajib terdaftar di tabel prodi
            ],
            'nama_mk' => [
                'required',
                'string',
                'max:255',
            ],
            'kode_mk' => [
                'nullable', // Di-set nullable jika Anda ingin memanfaatkan auto-generate kode dari sistem
                'string',
                'max:20',
                'unique:mata_kuliahs,kode_mk,'.$mataKuliahId,
            ],
            'sks' => [
                'required',
                'integer',
                'min:1',
                'max:6', // Batasan standar bobot SKS perkuliahan (biasanya 1 s.d 6 SKS)
            ],
            'semester_plot' => [
                'required',
                'integer',
                'min:1',
                'max:8', // Standar plotting kurikulum S1 adalah semester 1 sampai 8
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'id_prodi.required' => 'PROGRAM STUDI pengampu mata kuliah wajib dipilih.',
            'id_prodi.exists' => 'PROGRAM STUDI yang dipilih tidak valid.',

            'kode_mk.max' => 'KODE MATA KULIAH maksimal berjumlah 20 karakter.',
            'kode_mk.unique' => 'KODE MATA KULIAH sudah terdaftar di dalam sistem.',

            'nama_mk.required' => 'NAMA MATA KULIAH wajib diisi.',
            'nama_mk.max' => 'NAMA MATA KULIAH maksimal berjumlah 255 karakter.',

            'sks.required' => 'BOBOT SKS wajib diisi.',
            'sks.integer' => 'BOBOT SKS harus berupa angka.',
            'sks.min' => 'BOBOT SKS minimal adalah 1 SKS.',
            'sks.max' => 'BOBOT SKS maksimal adalah 6 SKS.',

            'semester_plot.required' => 'PLOTTING SEMESTER wajib diisi.',
            'semester_plot.integer' => 'PLOTTING SEMESTER harus berupa angka.',
            'semester_plot.min' => 'PLOTTING SEMESTER paling rendah adalah semester 1.',
            'semester_plot.max' => 'PLOTTING SEMESTER paling tinggi adalah semester 8.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'kode_mk' => $this->kode_mk ? strtoupper($this->kode_mk) : null,
            'nama_mk' => $this->nama_mk ? strtoupper($this->nama_mk) : null,
        ]);
    }
}
