<?php

namespace App\Http\Requests\Objective;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class ObjectiveRequest extends APIRequest
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
            'objective_name' => 'required',
            'is_active' => 'nullable|boolean',
            'objective_key' => 'nullable',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
