<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTreatemantRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'cost' => ['nullable', 'numeric'],
            'note' => ['nullable', 'string'],
            'doctor_id' => ['required', 'exists:doctors,id'],
            'preview_id' => ['required', 'exists:previews,id'],
            'patient_id' => ['required', 'exists:patients,id']
        ];
    }
}
