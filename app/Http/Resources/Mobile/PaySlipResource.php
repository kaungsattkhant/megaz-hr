<?php

namespace App\Http\Resources\Mobile;

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
            'basic_salary'=>$this->basic_salary,
            'allowance_amount'=>$this->total_allowance,
            'deduction_amount'=>$this->total_deduction ?? 0,
        ];
    }
}
