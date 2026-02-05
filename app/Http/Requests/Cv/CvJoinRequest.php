<?php

namespace App\Http\Requests\Cv;

use Illuminate\Foundation\Http\FormRequest;

class CvJoinRequest extends FormRequest
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
            "joined_date"=> ['required'],
            "staff_id" => ['required','exists:staff,id'],
            "probation_period"=>["required","integer","min:0"],
        ];
    }
}
