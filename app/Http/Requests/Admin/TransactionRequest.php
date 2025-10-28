<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class TransactionRequest extends APIRequest
{
    public function rules()
    {
        return [
            'account_id'=>'required',
            'value'=>'required|integer',
            'action' => 'required|in:debit,credit',
            'cash_account_id'=>'required|exists:accounts,id'
        ];
    }
    public function authorize()
    {
        return parent::authorize();
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
