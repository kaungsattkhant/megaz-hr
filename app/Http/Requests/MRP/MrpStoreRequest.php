<?php

namespace App\Http\Requests\MRP;

use Illuminate\Validation\Rule;
use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;
use Symfony\Component\HttpFoundation\Response;

class MrpStoreRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return parent::authorize();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'code' => 'required',
            // 'image' => 'nullable|mimes:jpeg,png,jpg|max:10240',
            'is_active' => 'nullable',
            'is_feature' => 'nullable',
            'description' => 'required|string',
            'cooking_place_id' => "required|exists:cooking_places,id",
            'price' => 'required',
            'menu_steps' => 'required|array',
            // 'menu_steps.*.menu_id' => 'required|exists:menus,id',
            'menu_steps.*.staff_id' => 'required|exists:staff,id',
            'menu_steps.*.staff_quantity' => 'required|integer',
            'menu_steps.*.level' => 'required|string|in:level_1,level_2,level_3,level_4,level_5,level_6,level_7',
            'menu_steps.*.type' => 'required|string|in:portion,ready_to_sale,cooking,plating',
            'menu_steps.*.duration' => 'nullable|integer',
            'menu_steps.*.order_time' => 'nullable|integer',
            'menu_steps.*.expected_quantity' => 'required|integer',
            'menu_steps.*.item_menu' => 'required|array',
            // 'menu_steps.*.item_menu.*.menu_step_id' => 'required|exists:menu_steps,id',
            'menu_steps.*.item_menu.*.item_id' => 'required|exists:items,id',
            'menu_steps.*.item_menu.*.uom_id' => 'required|exists:uoms,id',
            'menu_steps.*.item_menu.*.weight' => 'required|integer'
        ];
        foreach ($this->input('menu_steps', []) as $index => $menuStep) {
            if (
                isset($menuStep['level'], $menuStep['type']) &&
                $menuStep['level'] === 'level_4' &&
                $menuStep['type'] === 'portion'
            ) {
                $rules["menu_steps.$index.duration"] = 'required|integer';
                $rules["menu_steps.$index.order_time"] = 'required|integer';
            }
        }
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}




// payload{
//     name:'Menu-1',
//     menu_category_id:'1',
//     code:'menu-001',
//     image_url:'tes.jpg',
//     image_path:'test.jpg',
//     description:'test',
//     menu_steps: '[
//     {"level":"level_4",
//     "type":"portion",
//     "staff_id":1,
//     "staff_quantity":2,
//     "duration":1,
//     "order_time":"1",
//     "expected_quantity":1,
//     "menu_id":1,
//     "item_menu":[
//             {"item_id":1,
//             "menu_step_id":1,
//             "uom_id":1
//             ,"weight":1}
//             ]}
//             ];
// }
