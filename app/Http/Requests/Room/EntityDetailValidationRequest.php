<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class EntityDetailValidationRequest extends APIRequest
{
    public function rules()
    {
        return [
            'entity_type'=>'required',
            'entity_id'=>'required|exists:entities,id',
            'invoice_id'=>'required|exists:invoices,id',
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
