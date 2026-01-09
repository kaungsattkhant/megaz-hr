<?php

namespace App\Http\Requests\Staff;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StaffUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            "name" => "required",
            'phone_number' => [
                'required',
                Rule::unique('staff', 'phone_number')->ignore($this->route('id')),
            ],
            'alt_phone_number' => [
                'required',
                Rule::unique('staff', 'alt_phone_number')->ignore($this->route('id')),
            ],
             'email' => [
                'required',
                Rule::unique('staff', 'email')->ignore($this->route('id')),
            ],
            "nrc_number" => "sometimes",
            "address" => "sometimes",
            "gender_id" => "sometimes",
            "department_id" => "sometimes",
        ];
    }
}
