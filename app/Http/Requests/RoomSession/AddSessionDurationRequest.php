<?php

namespace App\Http\Requests\RoomSession;

use Illuminate\Foundation\Http\FormRequest;

class AddSessionDurationRequest extends FormRequest
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
            'session_duration' => 'required',
            // 'session_duration_time'=>'requried',
            // 'session_duration_minute'=>'requried',

        ];
    }
}
