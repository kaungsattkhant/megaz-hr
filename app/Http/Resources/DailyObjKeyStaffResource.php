<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DailyObjKeyStaffResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'assign_date' => $this->objectiveKeyDuty->assign_date,
            'staff_id' => $this->staff_id,
            'objective_key_id' => $this->objective_key_id,
            'status' => $this->status,
            // 'created_at' => $this->created_at,
            // 'updated_at' => $this->updated_at,
            'objective_key' => new ObjectiveKeyResource($this->objectiveKey),
        ];
    }
}
