<?php

namespace App\Http\Requests\HR;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class SalaryBatchRequest extends APIRequest
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
            'name' => 'required|unique:salary_batches,name',
            'day_of_monthly' => 'required|integer|between:1,31',
            'staff_ids' => 'nullable|string|json',
            'staff_ids.*' => 'exists:staff,id',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
