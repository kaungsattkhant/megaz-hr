<?php

namespace App\Http\Requests\Objective;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class KtvProductTreeRequest extends APIRequest
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
            'entity_id' => 'required',
            'objective_keys' => 'required',
            'items' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
