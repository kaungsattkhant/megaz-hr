<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class AccessoryCreateRequest extends APIRequest
{
    public function rules()
    {
        $id = $this->get('id');
        return [
            'code' => [
                'required',
                Rule::unique('accessories')->ignore($id),
            ],
            // 'image' => $id === null ? ['required'] : [], // Make 'price' required only if $id is null
            'price' => $id === null ? ['required'] : [], // Make 'price' required only if $id is null
            'name' => 'required',
            'accessory_category_id' => 'required',
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
