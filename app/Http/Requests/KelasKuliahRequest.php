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
            'class_code' => [
                'nullable',
                'string',
                'max:50',
                'unique:kelas_kuliahs,class_code,' . $kelasKuliahId,
            ],
            'course_id' => [
                'required',
                'integer',
                'exists:mata_kuliahs,id',
            ],
            'academic_year_id' => [
                'required',
                'integer',
                'exists:tahun_akademiks,id',
            ],
            'class_name' => [
                'required',
                'string',
                'max:255',
            ],
            'day' => [
                'required',
                'string',
                'max:50',
            ],
            'start_time' => [
                'required',
                'date_format:H:i:s,H:i',
            ],
            'end_time' => [
                'required',
                'date_format:H:i:s,H:i',
                'after:start_time',
            ],
            'room' => [
                'required',
                'string',
                'max:255',
            ],
            'dosen_ids' => [
                'nullable',
                'array',
            ],
            'dosen_ids.*' => [
                'integer',
                'exists:lecturers,id',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'class_code.required' => 'KODE KELAS wajib diisi.',
            'class_code.string' => 'KODE KELAS harus berupa teks.',
            'class_code.max' => 'KODE KELAS maksimal berjumlah 50 karakter.',
            'class_code.unique' => 'KODE KELAS sudah terdaftar di dalam sistem.',

            'course_id.required' => 'MATA KULIAH wajib dipilih.',
            'course_id.integer' => 'MATA KULIAH tidak valid.',
            'course_id.exists' => 'MATA KULIAH tidak terdaftar di dalam sistem.',

            'academic_year_id.required' => 'TAHUN AKADEMIK wajib dipilih.',
            'academic_year_id.integer' => 'TAHUN AKADEMIK tidak valid.',
            'academic_year_id.exists' => 'TAHUN AKADEMIK tidak terdaftar di dalam sistem.',

            'class_name.required' => 'NAMA KELAS wajib diisi.',
            'class_name.string' => 'NAMA KELAS harus berupa teks.',
            'class_name.max' => 'NAMA KELAS maksimal berjumlah 255 karakter.',

            'day.required' => 'HARI wajib diisi.',
            'day.string' => 'HARI harus berupa teks.',
            'day.max' => 'HARI maksimal berjumlah 50 karakter.',

            'start_time.required' => 'JAM MULAI wajib diisi.',
            'start_time.date_format' => 'JAM MULAI harus berformat jam (contoh: 08:00 atau 08:00:00).',

            'end_time.required' => 'JAM SELESAI wajib diisi.',
            'end_time.date_format' => 'JAM SELESAI harus berformat jam (contoh: 09:40 atau 09:40:00).',
            'end_time.after' => 'JAM SELESAI harus setelah jam mulai.',

            'room.required' => 'RUANGAN wajib diisi.',
            'room.string' => 'RUANGAN harus berupa teks.',
            'room.max' => 'RUANGAN maksimal berjumlah 255 karakter.',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('day')) {
            $this->merge([
                'day' => strtoupper($this->day),
            ]);
        }
        if ($this->has('class_code')) {
            $this->merge([
                'class_code' => strtoupper($this->class_code),
            ]);
        }
        if ($this->has('class_name')) {
            $this->merge([
                'class_name' => strtoupper($this->class_name),
            ]);
        }
        if ($this->has('room')) {
            $this->merge([
                'room' => strtoupper($this->room),
            ]);
        }
    }
}
