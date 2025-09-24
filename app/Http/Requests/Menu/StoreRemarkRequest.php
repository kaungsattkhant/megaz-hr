<?php

namespace App\Http\Requests\Menu;

use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class StoreRemarkRequest extends APIRequest
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
            'name' => 'required|string|unique:remarks,name',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
