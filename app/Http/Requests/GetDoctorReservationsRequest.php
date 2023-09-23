<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetDoctorReservationsRequest extends FormRequest
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
            'status' => 'nullable|in:Cancelled,Confirmed',
            'duration' => 'nullable|in:daily,weekly,monthly',
            'date'=>'nullable|date_format:Y-m-d',
            'preview_id'=>'nullable|exists:previews,id'
        ];
    }
}
