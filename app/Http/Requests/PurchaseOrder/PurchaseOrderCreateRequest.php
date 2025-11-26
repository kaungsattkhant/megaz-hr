<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderCreateRequest extends FormRequest
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
            //
            'purchase_order.po_id' => 'required',
            'purchase_order.total_price' => 'required',
            'purchase_order.created_by' => 'required',
            'purchase_order_items' => 'required',
            'purchase_order_items.*.quantity' => 'required', // Validate quantity for each item
            'purchase_order_items.*.item_id' => 'required',

        ];
    }
}
