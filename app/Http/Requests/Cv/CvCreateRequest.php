<?php

namespace App\Http\Requests\Cv;

use Illuminate\Foundation\Http\FormRequest;

class CvCreateRequest extends FormRequest
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
            "name" => "required",
            "phone_number" => "required|unique:staff,phone_number",
            "alt_phone_number" => "required|unique:staff,alt_phone_number",
            "email" => "required|unique:staff,email",
            "birthdate" => "required",
            "father_name" => "required",
            "mother_name" => "required",
            "state" => "required",
            "city" => "required",
            "nrc_number" => "required",
            "nrc_code" => "required",
            "nrc_township_code"=> "required",
            "nrc_type" => "required",
            "address" => "required",
            "gender_id" => "required|exists:genders,id",
            "department_id" => "required|exists:departments,id",
            "experience"=>"required",
            "primary_name" => "required",
            // "primary_phone" => "required|regex:/^09/",
            "primary_phone" => "required",
            "primary_relationship" => "required",
            "secondary_name" => "required",
            // "secondary_phone" => "required|regex:/^09/",
            "secondary_phone" => "required",
            "secondary_relationship" => "required",
            "role_id" => "required|array",
            "role_id.*" => "required|exists:roles,id"
        ];
    }
}
