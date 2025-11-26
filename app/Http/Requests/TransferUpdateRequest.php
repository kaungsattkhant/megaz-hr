<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferUpdateRequest extends FormRequest
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
            "transfer_id"=>"sometimes",
            "source_inventory_id"=>"sometimes",
            "destination_inventory_id" => "sometimes",
            "quantity" => "sometimes",
            "item_id" => "sometimes",
            "created_by" => "sometimes",

        ];
    }
}
