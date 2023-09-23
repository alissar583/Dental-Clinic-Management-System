<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItemExportRequest extends FormRequest
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
            'condition' => 'nullable|in:daily,weekly,monthly,yearly',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'category_id' => 'nullable|exists:categories,id'
        ];
    }
}
