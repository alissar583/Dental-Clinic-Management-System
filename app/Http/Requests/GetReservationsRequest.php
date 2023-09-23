<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetReservationsRequest extends FormRequest
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
            'treatement_id' => 'required_without:doctor_id|exists:treatements,id',
            'status' => 'nullable|in:Cancelled,Confirmed',
            'start_date' => 'nullable|date_format:Y-m-d',
            'end_date' => 'nullable|date_format:Y-m-d',
            'doctor_id' => 'required_without:treatement_id|nullable|exists:doctors,id',
            'duration' => 'nullable|in:daily,weekly,monthly',
            'preview_id' => 'nullable|exists:previews,id'
        ];
    }
}
