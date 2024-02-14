<?php

namespace App\Http\Requests\PurchaseOrder;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseOrderUpdateRequest extends FormRequest
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
            "po_id" => "sometimes",
            "total_price" => "sometimes",
            "created_by" => "sometimes",
            "kitchen_check_id" => "sometimes",
            "financial_check_id" => "sometimes",
            "kitchen_check_time" => "sometimes",
            "kitchen_check_time" => "sometimes",
            "condition"=>"sometimes",
        ];
    }
}
