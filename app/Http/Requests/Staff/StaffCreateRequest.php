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
            "phone_number" => "required|unique:staff,phone_number",
            "alt_phone_number" => "required|unique:staff,alt_phone_number",
            "email" => "required|unique:staff,email",
            "birthdate" => "required",
            "fater_name" => "sometimes",
            "mother_name" => "sometimes",
            "state" => "required",
            "city" => "required",
            "zip_code" => "required",
            "joined_date" => "required",
            "nrc_number" => "sometimes",
            "address" => "sometimes",
            "gender_id" => "required",
            "department_id" => "required",
            "password" => "required",
            // "roles" => "required",
            'off_day_count' => "required|numeric",
            'gps_distance' => "required|numeric",
            'check_in_late_min' => "required|numeric",
            'check_out_early_min' => "required|numeric"
        ];
    }
}
