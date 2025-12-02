<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminCheckInRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
g

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'staff_id'=>'required|exists:staff,id',
            'time_shift_id'=>'required|exists:time_shifts,id',
            // 'staff_timeshift_id' => 'required|exists:staff_timeshifts,id',
            'check_in_date_time'=>'required',
            'check_out_date_time' => 'required'
        ];
    }
}
