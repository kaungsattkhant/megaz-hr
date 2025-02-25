<?php

namespace App\Http\Requests\Inventory;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class InventoryItemRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inventory_id' => 'required',
            'item_id' => 'required',
            'base_uom_id' => 'required|integer|exists:uoms,id',
            'base_uom_min_quantity' => 'required|numeric',
            'uom_id' => 'required|integer|exists:uoms,id',
            'uom_min_quantity' => 'required|numeric',
            'conversion' => 'required',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
