<?php

namespace App\Http\Requests\JD;

use Illuminate\Contracts\Validation\Validator;
use App\Http\Requests\APIRequest;
class StoreJDrequest extends APIRequest
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
            'job_description' => 'required',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
