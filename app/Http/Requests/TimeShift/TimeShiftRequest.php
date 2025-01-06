<?php

namespace App\Http\Requests\TimeShift;

use App\Http\Requests\APIRequest;

use Illuminate\Contracts\Validation\Validator;

class TimeShiftRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return parent::authorize();
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shift_id' => 'required|integer|exists:shifts,id',
            'from_time' => 'required|date_format:H:i',
            'to_time' => 'required|date_format:H:i',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
