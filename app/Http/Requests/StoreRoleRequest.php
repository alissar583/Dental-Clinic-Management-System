<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
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
            'role_name_ar' => ['required', 'string', 'unique:roles,name_ar'],
            'role_name_en' => ['required', 'string', 'unique:roles,name'],
            'permissions.*' => ['required', 'exists:permissions,id']
        ];
    }
}
