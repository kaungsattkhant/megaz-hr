<?php

namespace App\Http\Requests\StaffMobile;

use Illuminate\Foundation\Http\FormRequest;

class RejectObjectiveKeyRequest extends FormRequest
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
            'status' => 'required|in:rejected',
            "objective_staff_id"=> "required|exists:objective_staff,id",
            'reject_objective_keys'   => 'required|array',
            'reject_objective_keys.*' => 'integer|exists:objective_keys,id',
        ];
    }
}
