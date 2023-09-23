<?php

namespace App\Http\Requests;

use App\Models\Day;
use App\Rules\CheckWorcableIntersection;
use Illuminate\Foundation\Http\FormRequest;

class WorkingDaysRequest extends FormRequest
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
            // 'days' => ['required', 'array'],
            // 'days.*.from' => ['required', 'date_format:H:i'],
            // 'days.*.to' => ['required', 'date_format:H:i'],
            // 'days.*.day_id' => ['required', 'exists:days,id'],
        ];   
    }
}
