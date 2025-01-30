<?php

namespace App\Http\Requests\PoOrder;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class PoOrderItemRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
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
            'base_uom_id' => 'required|integer|exists:uoms,id',
            'base_uom_quantity' => 'required|numeric',
            'uom_id' => 'required|integer|exists:uoms,id',
            'uom_quantity' => 'required|numeric',
            'uom_conversion_unit_id' => 'required|integer|exists:uom_conversions,id',
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
            'unit_price'  => 'required|numeric',
            'item_id' => 'required|integer|exists:items,id',
            'brand_id' => 'nullable|integer|exists:brands,id',
            'supplier_id' => 'required|integer|exists:suppliers,id',
            'purchase_order_id' => 'required|integer|exists:purchase_orders,id',
            'item_price_id' => 'nullable|integer|exists:item_prices,id',
            'later_buy' => 'nullable',
            'item_leftable_type' => 'nullable',
            'item_left_id' => 'nullable|exists:item_lefts,id',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
