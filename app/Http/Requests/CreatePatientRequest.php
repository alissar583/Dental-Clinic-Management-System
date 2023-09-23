<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreatePatientRequest extends FormRequest
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
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birth_date' => ['date_format:Y-m-d'],
            'active' => ['required'],
            'phone' => ['required', 'unique:users,phone'],
            'illnesses.*'=> ['exists:illnesses,id'],
            'medicine'=> ['string'],
            'else_illnesses'=> ['string'],
        ];
    }
}
