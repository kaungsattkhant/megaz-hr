<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class PoInvoiceTransactionRequest extends APIRequest
{
    public function rules()
    {
        return [
            'cash_account_id' => 'required',
            'amount' => ['required', 'numeric','gt:0'],
            'ap_amount' => ['required', 'numeric', 'min:0'],
            'total_invoice_amount' => ['required', 'numeric','gt:0'],
            'po_invoice_id' => 'required',
            'supplier_id' => 'required',
            'supplier_account_id' => 'required',

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
