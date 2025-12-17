<?php

namespace App\Http\Requests\Loan;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;
class LoanRequest extends APIRequest
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
        'type' => 'required|in:addition,interest addition,settlement,interest settlement',
        'category' => 'nullable|in:loan,interest',
        'account_id' => 'required|exists:accounts,id',
        'account_code' => 'required',
        'cash_account_id' => 'nullable|exists:accounts,id',
        'amount' => 'required|numeric',
        'interest_rate' => 'nullable|numeric',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
