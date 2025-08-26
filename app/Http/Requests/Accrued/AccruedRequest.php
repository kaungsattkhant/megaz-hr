<?php

namespace App\Http\Requests\Accrued;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;
class AccruedRequest extends APIRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category' => 'required',
            'type' => 'required',
            'expense_account_id' => 'required',
            'expense_account_code' => 'required',
            'amount' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
