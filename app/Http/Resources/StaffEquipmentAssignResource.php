<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffEquipmentAssignResource extends JsonResource
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
            'staff_id' => $this->staffEquipment->staff_id ?? null,
            'item_id' => $this->item_id,
            'item' => $this->item->name ?? null,
            'quantity' => $this->quantity ?? null,
            'uom_name' => $this->uom->name ?? null,
        ];
    }
}
