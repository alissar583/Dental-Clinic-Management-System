<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreatePreviewRequest extends FormRequest
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
            'previews' => 'required|array',
            'previews.*.name_ar' => [
                'required', 'string', 'distinct','unique:previews,name_ar'
                // Rule::unique('previews', 'name_ar')->where('doctor_id', Auth::id())
            ],
            'previews.*.name_en' => [
                'required', 'string', 'distinct','unique:previews,name_en'
                // Rule::unique('previews', 'name_en')->where('doctor_id', Auth::id())
            ],
            'previews.*.cost' => 'nullable|integer'
        ];
    }
}
