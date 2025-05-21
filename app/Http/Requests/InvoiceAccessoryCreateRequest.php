<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class InvoiceAccessoryCreateRequest extends APIRequest
{
    public function rules()
    {
        return [
            'quantity' => 'required|numeric',
            'invoice_id' => 'required',
            'accessory_id' => 'required',
            'accessory_price'=>'required',

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
