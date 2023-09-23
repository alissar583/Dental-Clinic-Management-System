<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->can('update',$this->route('reservation'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'from' => ['nullable', 'date_format:H:i'],
            'to' => ['nullable', 'date_format:H:i','after:from'],
            'note' => ['string', 'nullable'],
            'payment' => ['nullable', 'numeric'],
            'date' => ['nullable', 'date_format:Y-m-d'],
            'medicines' => 'nullable',
            'diagnostics' => 'nullable',
            'media' => 'nullable|array',
            'media.*' => 'file'
        ];
    }
}
