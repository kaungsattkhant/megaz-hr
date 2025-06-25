<?php

namespace App\Http\Requests\PoOrder;

use App\Http\Requests\APIRequest;
use Illuminate\Contracts\Validation\Validator;

class ArrivalItemRequest extends APIRequest
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
            'quality' => 'required|integer',
            'base_uom_id' => 'required|integer|exists:uoms,id',
            'base_uom_quantity' => 'required|numeric',
            'uom_id' => 'required|integer|exists:uoms,id',
            'uom_quantity' => 'required|numeric',
            'uom_conversion_unit_id' => 'required|integer|exists:uom_conversions,id',
            'quantity' => 'required|numeric',
            'amount' => 'required|numeric',
            'unit_price'  => 'required|numeric',
            'po_invoice_id' => 'nullable|integer|exists:po_invoices,id',
            'item_id' => 'required',
            'supplier_id' => 'required',
            'purchase_order_id' => 'required',
            'later_buy' => 'nullable',
            'item_leftable_type' => 'nullable',
            'is_new_invoice' => 'nullable',
            'invoice_no' => 'nullable',
            'item_left_id' => 'nullable',
            'brand_id' => 'nullable',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
    }
}
