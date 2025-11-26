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

    protected function prepareForValidation()
    {
        // Convert JSON strings to arrays for validation
        if ($this->has('menu_steps') && is_string($this->menu_steps)) {
            $menuSteps = json_decode($this->menu_steps, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['menu_steps_array' => $menuSteps]);
            }
        }

        if ($this->has('cooking_place_id') && is_string($this->cooking_place_id)) {
            $cookingPlaces = json_decode($this->cooking_place_id, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['cooking_places_array' => $cookingPlaces]);
            }
        }

        if ($this->has('sub_menu_id') && is_string($this->sub_menu_id)) {
            $subMenus = json_decode($this->sub_menu_id, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['sub_menus_array' => $subMenus]);
            }
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => 'required',
            'menu_category_id' => 'required|exists:menu_categories,id',
            'code' => 'required',
            'image' => 'nullable|mimes:jpeg,png,jpg,webp|max:10240',
            'description' => 'nullable|string',
            'cooking_place_id' => 'required|json',
            'price' => 'nullable',
            'sub_menu_id' => 'required_if:menu_type,menu|nullable|json',
            'menu_steps' => 'nullable|json',
        ];
        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
