<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ObjectiveAssignCreateRequest extends FormRequest
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
    protected function prepareForValidation()
    {
        if ($this->okr_assign) {
            $this->merge([
                'okr_assign' => json_decode($this->okr_assign, true)
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'okr_assign' => ['required', 'array'],

            // 'okr_assign.*.department_name' => ['required', 'string'],
            // 'okr_assign.*.role_name' => ['required', 'string'],
            'okr_assign.*.role_id' => ['required', 'integer' , Rule::exists('roles', 'id')],
            // 'okr_assign.*.staff_name' => ['required', 'string'],
            'okr_assign.*.objective_key_name' => ['required', 'string'],
            'okr_assign.*.staff_id' => ['required', 'integer',Rule::exists('staff','id')],
            'okr_assign.*.objective_id' => ['required', 'integer',Rule::exists('objectives','id')],
            'okr_assign.*.start_date' => ['required', 'date'],
            'okr_assign.*.end_date' => ['required', 'date'],
        ];
    }
}
