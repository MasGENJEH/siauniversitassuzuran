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
            'code' => [
                'required',
                'string',
                'max:50',
                'unique:tahun_akademiks,code,' . $tahunAkademikId,
            ],
            'name' => [
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
            'code.required' => 'KODE TAHUN AKADEMIK wajib diisi.',
            'code.string' => 'KODE TAHUN AKADEMIK harus berupa teks.',
            'code.max' => 'KODE TAHUN AKADEMIK maksimal berjumlah 50 karakter.',
            'code.unique' => 'KODE TAHUN AKADEMIK sudah terdaftar di dalam sistem.',

            'name.required' => 'NAMA TAHUN AKADEMIK wajib diisi.',
            'name.string' => 'NAMA TAHUN AKADEMIK harus berupa teks.',
            'name.max' => 'NAMA TAHUN AKADEMIK maksimal berjumlah 255 karakter.',

            'status.boolean' => 'STATUS harus bernilai true atau false (1 atau 0).',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => strtoupper($this->name),
            ]);
        }
        if ($this->has('code')) {
            $this->merge([
                'code' => strtoupper($this->code),
            ]);
        }
    }
}
