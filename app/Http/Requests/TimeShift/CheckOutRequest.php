<?php

namespace App\Http\Requests\TimeShift;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class CheckOutRequest extends APIRequest
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
            'check_out_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
