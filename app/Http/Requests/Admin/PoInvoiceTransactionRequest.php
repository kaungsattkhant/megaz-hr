<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class PoInvoiceTransactionRequest extends APIRequest
{
    public function rules()
    {
        return [
            'cash_account_id' => ['required','exists:accounts.id'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'ap_amount' => ['required', 'numeric', 'min:0'],
            'total_invoice_amount' => ['required', 'numeric', 'gt:0'],
            'po_invoice_id' => ['required', 'exists:po_invoices,id'], // Check existence in the 'po_invoices' table
            'supplier_id' => ['required', 'exists:suppliers,id'],
            'supplier_account_id' => ['required','exists:accounts.id'],

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
