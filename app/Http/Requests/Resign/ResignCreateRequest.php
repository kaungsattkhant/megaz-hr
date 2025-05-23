<?php

namespace App\Http\Requests\Resign;

use App\Http\Requests\APIRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ResignCreateRequest extends FormRequest
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
            'resign_category_id' => 'required|exists:resign_categories,id',
            'resignation_date' => 'required',
            'status' => 'required|in:received,confirmed,cancelled',
            'detail' => 'nullable|string',
            'staff_id' => 'required|exists:staff,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'confirmed_by' => 'required_if:status,confirmed|nullable',
            'cancelled_by' => 'required_if:status,cancelled|nullable',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
