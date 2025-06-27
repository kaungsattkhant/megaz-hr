<?php

namespace App\Http\Requests\Area;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class AreaCreateRequest extends APIRequest
{
    public function rules()
    {
        $isSellingArea = $this->get('area_category_id') == 2 ? true : false;
        if ($isSellingArea) {
            return [
                //
                'name' => 'required',
                'area_type_id' => 'required|exists:area_types,id',
                'area_category_id' => 'required|exists:area_categories,id',
            ];
        } else {
            return [
                'name' => 'required',
                'area_category_id' => 'required|exists:area_categories,id',
            ];
        }
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