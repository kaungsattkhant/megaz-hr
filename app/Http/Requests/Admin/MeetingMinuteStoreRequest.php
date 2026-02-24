<?php

namespace App\Http\Requests\Admin;

use App\Enums\PDCAEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MeetingMinuteStoreRequest extends FormRequest
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
            'meeting_id' => [
                'required',
                'integer',
                Rule::exists('meetings', 'id'),
                Rule::unique('meeting_minutes', 'meeting_id')->ignore($this->id),
            ],
            'meeting_minute' => ['required'],
            'attendances' => ['required', 'array', 'min:1'],
            'attendances.*' => ['integer', 'distinct', 'exists:staff,id'],
            'instructions' => ['required', 'array', 'min:1'],
            'instructions.*.objective_id' => ['required', 'integer', 'exists:objectives,id'],
            'instructions.*.okr_point' => ['nullable', 'numeric'],
            'instructions.*.project_id' => ['required', 'integer', 'exists:projects,id'],
            'instructions.*.tag' => ['required', Rule::in(PDCAEnum::getValues())],
            'instructions.*.priority' => ['required', 'integer', 'between:1,10'],
            'instructions.*.assign_to' => ['required', 'integer', 'exists:staff,id'],
            'instructions.*.responsible_id' => ['required', 'integer', 'exists:staff,id'],
            'instructions.*.accountable' => ['required', 'integer', 'exists:staff,id'],
            'instructions.*.consulted_id' => ['nullable', 'integer', 'exists:staff,id'],
            'instructions.*.informed_id' => ['nullable', 'integer', 'exists:staff,id'],
            'instructions.*.start_date' => ['required'],
            'instructions.*.due_date' => ['required','after_or_equal:instructions.*.start_date'],
            'instructions.*.instruction_objective_key' => ['required', 'array', 'min:1'],
            'instructions.*.instruction_objective_key.*' => ['integer', 'distinct', 'exists:objective_keys,id'],
        ];
    }
}
