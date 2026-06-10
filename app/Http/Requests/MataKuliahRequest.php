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
            'study_program_id' => [
                'required',
                'integer',
                'exists:study-programs,id', // Memastikan prodi pengampu wajib terdaftar di tabel prodi
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'code' => [
                'nullable', // Di-set nullable jika Anda ingin memanfaatkan auto-generate kode dari sistem
                'string',
                'max:20',
                'unique:mata_kuliahs,code,'.$mataKuliahId,
            ],
            'sks' => [
                'required',
                'integer',
                'min:1',
                'max:6', // Batasan standar bobot SKS perkuliahan (biasanya 1 s.d 6 SKS)
            ],
            'recommended_semester' => [
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
            'study_program_id.required' => 'PROGRAM STUDI pengampu mata kuliah wajib dipilih.',
            'study_program_id.exists' => 'PROGRAM STUDI yang dipilih tidak valid.',

            'code.max' => 'KODE MATA KULIAH maksimal berjumlah 20 karakter.',
            'code.unique' => 'KODE MATA KULIAH sudah terdaftar di dalam sistem.',

            'name.required' => 'NAMA MATA KULIAH wajib diisi.',
            'name.max' => 'NAMA MATA KULIAH maksimal berjumlah 255 karakter.',

            'sks.required' => 'BOBOT SKS wajib diisi.',
            'sks.integer' => 'BOBOT SKS harus berupa angka.',
            'sks.min' => 'BOBOT SKS minimal adalah 1 SKS.',
            'sks.max' => 'BOBOT SKS maksimal adalah 6 SKS.',

            'recommended_semester.required' => 'PLOTTING SEMESTER wajib diisi.',
            'recommended_semester.integer' => 'PLOTTING SEMESTER harus berupa angka.',
            'recommended_semester.min' => 'PLOTTING SEMESTER paling rendah adalah semester 1.',
            'recommended_semester.max' => 'PLOTTING SEMESTER paling tinggi adalah semester 8.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'code' => $this->code ? strtoupper($this->code) : null,
            'name' => $this->name ? strtoupper($this->name) : null,
        ]);
    }
}
