<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\Rule;

class ItemRequest extends APIRequest
{
    public function rules()
    {
        $id = $this->get('id');
        return [
            'code' => [
                'required',
                Rule::unique('items', 'code')->ignore($id),
            ],
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
