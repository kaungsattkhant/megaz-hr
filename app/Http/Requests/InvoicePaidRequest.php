<?php

namespace App\Http\Requests;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class InvoicePaidRequest extends APIRequest
{
    public function rules()
    {
        return [
            'id' => 'required|integer|exists:invoices,id',
            'paid_amount' => 'required|numeric|min:0',
            'payment_type' => 'required|string|in:cash,bank', // example types
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
