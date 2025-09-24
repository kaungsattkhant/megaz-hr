<?php

namespace App\Http\Requests\Financial;

use Illuminate\Foundation\Http\FormRequest;

class CashbookCloseRequest extends FormRequest
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
            'is_pos' => 'required|in:0,1', // boolean means it accepts 1/0, true/false
            'cash_account_id' =>'required',
            'to_cash_account_id' => 'required_if:is_pos,1|nullable',
        ];
    }
}
