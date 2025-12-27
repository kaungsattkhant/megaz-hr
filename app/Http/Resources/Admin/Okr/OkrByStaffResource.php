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
            'okr_point' => $this->objective->okr_point,
        ];
    }
}
