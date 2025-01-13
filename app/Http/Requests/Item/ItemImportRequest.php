<?php

namespace App\Http\Requests\Item;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class ItemImportRequest extends APIRequest
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
            'item_import' => 'required|file|mimes:xlsx,xls,csv'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
