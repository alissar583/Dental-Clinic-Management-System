<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClinicRequest extends FormRequest
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
            'name' => 'required|string',
            'phone' => 'required|unique:users|regex:/^(09)+[0-9]{8}$/',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
            'city' => 'required|string',
            'country' => 'required|string',
            'area' => 'required|string',
            'street' => 'required|string',
            'building_number' => 'nullable|int',
            'floor_number' => 'nullable|int',
            'note' => 'nullable|string'
        ];
    }
}
