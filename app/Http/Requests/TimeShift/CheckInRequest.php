<?php

namespace App\Http\Requests\TimeShift;

use App\Models\Gps;
use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class CheckInRequest extends APIRequest
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
            'latitude' => 'required',
            'longitude' => 'required',
            'check_in_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'staff_id' => 'required',
            'time_shift_id' => 'required'
        ];
    }


    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
