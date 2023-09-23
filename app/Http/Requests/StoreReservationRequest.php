<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'from' => ['required', 'date_format:H:i'],
            'to' => ['required', 'date_format:H:i','after:from'],
            'note' => ['string', 'nullable'],
            'payment' => ['required', 'numeric'],
            'date' => ['required', 'date_format:Y-m-d'],
            'cancelled_reason' => ['nullable', 'string'],
        ];
    }
}
