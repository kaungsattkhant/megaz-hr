<?php

namespace App\Http\Requests\Hr;


use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreLeaveAllowanceRequest extends APIRequest
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
            'role_id' => 'required|exists:roles,id',
            'leave_category_id' => 'required|exists:leave_categories,id',
            'day' => 'required|integer|min:1',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
