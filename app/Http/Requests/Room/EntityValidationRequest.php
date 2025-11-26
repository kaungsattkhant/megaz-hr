<?php

namespace App\Http\Requests\Room;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class EntityValidationRequest extends APIRequest
{
    public function rules()
    {
        return [
            'entity_type'=>'required',
            'entity_id'=>'required|exists:entities,id',
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
