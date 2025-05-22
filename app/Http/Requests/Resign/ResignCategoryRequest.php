<?php

namespace App\Http\Requests\Resign;

use App\Http\Requests\APIRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ResignCategoryRequest extends APIRequest
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
            "name" => "required|string|max:255|unique:resign_categories,name,except,id",
        ];
    }
    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
