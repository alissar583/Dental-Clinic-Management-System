<?php

namespace App\Http\Requests\Account;

use App\Enums\AccountType;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreAccountRequest extends FormRequest
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
        $rules =  [
            //////
            'roles' => ['array'],
            'roles.*' => 'exists:roles,id',
            'permissions' => ['array'],
            'permissions.*' => 'exists:permissions,id',
            ///////////
            'first_name' => ['required', 'string'],
            'last_name' => ['required', 'string'],
            'birth_date' => [],
            'active' => ['required'],
            'phone' => ['required', 'unique:users,phone'],
            'account_type' => ['required', 'in:1,2,3,4'],
        ];
        if($this->account_type == 4) {
            $rules['illnesses.*'] = ['exists:illnesses,id'];
            $rules['medicine'] = ['string'];
            $rules['else_illnesses'] = ['string'];
        }
        return $rules;
    }
}
