<?php

namespace App\Http\Resources\Mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaySlipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'name' => Carbon::parse($this->confirmed_at)->format('F').' Salary',
            'basic_salary'=>$this->basic_salary,
            'date_time'=>$this->confirmed_at ?? $this->created_at,
            'allowance_amount'=>$this->total_allowance,
            'deduction_amount'=>$this->total_deduction ?? 0,
            'unpaid_leave' => $this->unpaid_leave ?? 0,
        ];
    }
}
