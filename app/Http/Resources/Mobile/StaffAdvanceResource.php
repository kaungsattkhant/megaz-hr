<?php

namespace App\Http\Resources\Mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffAdvanceResource extends JsonResource
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
            'advance_ref_no'=>$this->advance_ref_no,
            'date_time'=>$this->date_time,
            'month'=>Carbon::parse($this->date_time)->format('F'),
            "remaining_amount"=>$this->remaining_amount,
            'advance_payment'=> $this->advance_payment->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'paid_amount' => $payment->paid_amount,
                    'payment_month' => $payment->payment_month,
                ];
            }),
        ];
    }
}
