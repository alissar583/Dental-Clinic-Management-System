<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatProfileRequest extends FormRequest
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
            'first_name' => ['string'],
            'last_name' => ['string'],
            // 'phone' => ['numeric', 'unique:users,phone'],
            'photo' => ['file', 'mimes:png,jpg,jpeg'],
            'birth_date' => ['nullable', 'date']
        ];
    }
}
