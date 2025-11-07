<?php

namespace App\Http\Requests;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class InvoicePaidRequest extends APIRequest
{
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:invoices,id',
            'payment_type' => 'required|string|in:cash,bank,split', // example types
            'paid_amount' => [
                'numeric',
                'min:0',
                Rule::requiredIf(in_array($this->payment_type, ['cash', 'bank'])),
            ],
            'cash_paid_amount' => 'required_if:payment_type,split|numeric|min:0',
            'bank_paid_amount' => 'required_if:payment_type,split|numeric|min:0',
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
