<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetMyTreatementsRequest extends FormRequest
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
            'doctor_id' => 'nullable|exists:doctors,id',
            'preview_id' => 'nullable|exists:previews,id',
            'order_by' => 'nullable|in:asc',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
            'date' => 'nullable|date',
        ];
    }
}
