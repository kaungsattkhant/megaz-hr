<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdvanceStoreRequest extends FormRequest
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
            'staff_id' => 'required|exists:staff,id',
            'advance_amount' => 'required|numeric|min:1',
            'total_months' => 'required|integer|min:1',
            'remaining_amount' => 'required|numeric|min:0',
            'remaining_months' => 'required|integer|min:0',
            'current_deduction_amount' => 'required|numeric|min:0',
        ];
    }
    // public function messages(): array
    // {
    //     return [
    //         'staff_id.required' => 'Staff is required',
    //         'staff_id.exists' => 'Selected staff does not exist',

    //         'advance_amount.required' => 'Advance amount is required',
    //         'advance_amount.numeric' => 'Advance amount must be numeric',

    //         'total_months.required' => 'Total months is required',
    //         'total_months.integer' => 'Total months must be an integer',

    //         'remaining_amount.required' => 'Remaining amount is required',

    //         'remaining_months.required' => 'Remaining months is required',

    //         'current_deduction_amount.required' => 'Current deduction amount is required',
    //     ];
    // }
}
