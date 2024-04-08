<?php

namespace App\Http\Requests\Staff;

use Illuminate\Foundation\Http\FormRequest;

class StaffCreateRequest extends FormRequest
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
            "phone_number" => "required",
            "alt_phone_number" => "sometimes",
            "email" => "sometimes",
            "birthdate" => "required",
            "fater_name" => "sometimes",
            "mother_name" => "sometimes",
            "state" => "required",
            "city" => "required",
            "zip_code" => "required",
            "nrc_number" => "sometimes",
            "address" => "sometimes",
            "gender_id" => "required",
            "department_id" => "required",
            "password" => "required",
            "roles" => "required"

        ];
    }
}
