<?php

namespace App\Http\Requests\MRP;

use Illuminate\Foundation\Http\FormRequest;
use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class MenuToggleRequest extends APIRequest
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
            'is_active' => 'nullable|in:0,1',
            'is_feature' => 'nullable|in:0,1',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
