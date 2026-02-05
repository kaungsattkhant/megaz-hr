<?php

namespace App\Http\Requests\Staff;

use App\Enums\StaffStatus;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StaffStatusUpdate extends FormRequest
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
            "id"=>["required","exists:staff,id"],
            'status' => [
                'required',
                Rule::in([
                    StaffStatus::PROBATION->value,
                    StaffStatus::PERMANENT->value,
                ]),
            ],

            'probation_period' => [
                Rule::requiredIf(fn() => request('status') === StaffStatus::PROBATION->value),
                'integer',
                'min:1',
            ],

        ];
    }
}
