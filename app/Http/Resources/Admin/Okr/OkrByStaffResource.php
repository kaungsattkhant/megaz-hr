<?php

namespace App\Http\Resources\Admin\Okr;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OkrByStaffResource extends JsonResource
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
            'objective_name'=>$this->objective->objective_name,
            'staff_name'=>$this->staff->name,
            'type' => $this->objective->type,
            'okr_point' => $this->objective_assign_staff->okr_point,
            'status' => $this->objective_assign_staff->status,
            'start_date' => $this->objective_assign_staff->start_date,
            'end_date' => $this->objective_assign_staff->end_date,
            'remark' => $this->objective_assign_staff->remark,
        ];
    }
}
