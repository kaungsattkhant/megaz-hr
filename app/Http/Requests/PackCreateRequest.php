<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackCreateRequest extends FormRequest
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
            'inventory_id'=>'required|integer|exists:inventories,id',
            'quantity'=>'required|integer',
            'menu_id'=>'required|integer|exists:menus,id',
            'expired_at'=>'required',
        ];
    }
}
