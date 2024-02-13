<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryUpdateRequest extends FormRequest
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
            "area_id" => "sometimes",
            "department_id" => "sometimes",
            "name" => "sometimes",
            "inventoryable_type" => "sometimes",
            "inventoryable_id" => "sometimes",
            "is_active" => "sometimes"
        ];
    }
}
