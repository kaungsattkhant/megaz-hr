<?php

namespace App\Http\Requests\NotiType;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class NotiTypeRequest extends APIRequest
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
            'name' => 'required|string',
            'typeable_type' => 'required|in:training,orgNew,warning',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
