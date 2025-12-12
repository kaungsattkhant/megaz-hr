<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BenefitCreateRequest extends FormRequest
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
            'type' => 'required|in:menu,cost',
            'menu_id' => 'required_if:type,menu|exists:menus,id',
            'cost' => 'required_if:type,cost|numeric',
            'name' => 'required_if:type,cost',
        ];
    }
}
