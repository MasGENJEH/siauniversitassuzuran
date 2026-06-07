<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255|unique:roles,name,'.$this->route('role'),
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'name wajib diisi.',
            'name.string' => 'name harus berupa string.',
            'name.max' => 'maksimal 255 karakter.',
            'name.unique' => 'name sudah ada.',
        ];
    }
}
