<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class EntityStartValidationRequest extends APIRequest
{
    public function rules()
    {
        $type=$this->get('type');
        return [
            'customer_id'=>'required',
            'entity_type'=>'required',
            'entity_id'=>'required|exists:entities,id',
            'session_duration'=>$type=='session' ? 'required' : 'nullable',
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
