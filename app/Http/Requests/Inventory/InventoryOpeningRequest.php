<?php

namespace App\Http\Requests\Inventory;

use Illuminate\Foundation\Http\FormRequest;

class InventoryOpeningRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // if items comes as a JSON string, decode it so array rules work
        if ($this->has('items') && is_string($this->input('items'))) {
            $decoded = json_decode($this->input('items'), true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $this->merge(['items' => $decoded]);
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
        return [
            'date' => 'required|date',
            'remarks' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.base_uom_id' => 'required|exists:uoms,id',
            'items.*.uom_id' => 'required|exists:uoms,id',
            'items.*.base_uom_quantity' => 'required|numeric|min:0',
            'items.*.uom_quantity' => 'required|numeric|min:0',
        ];
    }
}
