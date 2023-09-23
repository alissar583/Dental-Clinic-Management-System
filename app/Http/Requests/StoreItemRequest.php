<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'name_en' => ['required', 'string'],
            'name_ar' => ['required', 'string'],
            'minimum_quantity' => ['required', 'numeric'],
            'price' => ['required', 'numeric'],
            'note' => ['nullable','string'],
            'exp_date' => ['required', 'date', 'after:now'],
            'quantity' =>['required', 'numeric'],
            'category_id' => ['required', 'exists:categories,id']
        ];
    }
}
