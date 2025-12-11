<?php

namespace App\Http\Resources\Mobile;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BenefitRequestResourceList extends JsonResource
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
            'name'=> $this->benefit->type=='menu' ? $this->benefit->menu->name : $this->benefit->name,
            'date_time'=>Carbon::parse($this->date_time)->format('Y F d'),
            'status'=>$this->status
        ];
    }
}
