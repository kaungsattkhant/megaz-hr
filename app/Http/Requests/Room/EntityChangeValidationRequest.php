<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class EntityChangeValidationRequest extends APIRequest
{
    public function rules()
    {
        $isWaiter=$this->get('waiter')=="1" ? 1 :0 ;
        return [
            'entity_type'=>'required',
            'entity_id'=>'required|exists:entities,id',
            'previous_entity_id'=>'required|exists:entities,id',
            'invoice_id'=>'required',
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
