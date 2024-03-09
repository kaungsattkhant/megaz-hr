<?php

namespace App\Http\Requests\RoomSession;

use Illuminate\Foundation\Http\FormRequest;

class EndRoomSessionRequest extends FormRequest
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
            'invoice_id' => 'required',
            'change' => "required",
            'discount_value' => 'required',
            'paid_amount' => 'required',
            'payment_type' => "required",
            'total_session_price' => 'required',
            'food_charge' => 'required',
            'service_charge' => 'required',
            'tax' => 'required',
            'total' => 'required'
        ];
    }
}
