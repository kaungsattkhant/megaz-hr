<?php

namespace App\Http\Requests\MRP;

use Illuminate\Foundation\Http\FormRequest;

class ForecastMRPRequest extends FormRequest
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
            'type',
            'date',
            'mrp_forecast_id',
            'mrp_forecastable_id',
            'mrp_forecastable_type',
            'quantity',
            'hour',
            'role_id',
            'total_duration',
            'uom_id',
            'quantity',
            'amount'
        ];
    }
}
