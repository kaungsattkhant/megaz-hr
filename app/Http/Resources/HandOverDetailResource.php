<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\HandOverItemsResource;
use Illuminate\Http\Resources\Json\JsonResource;

class HandOverDetailResource extends JsonResource
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
            "from_staff_id" => $this->from_staff_id ?? null,
            "from_staff_name" => $this->fromStaff->name ?? null,
            "to_staff_id" => $this->to_staff_id ?? null,
            "to_staff_name" => $this->toStaff->name ?? null,
            "staff_timeshift_id" => $this->staff_timeshift_id ?? null,
            "staff_timeshift_date" => $this->staffTimeshift->date_time ?? null,
            "staff_timeshift_area_name" => $this->staffTimeshift->area->name ?? null,
            "staff_timeshift_area_id" => $this->staffTimeshift->area_id ?? null,
            "staff_timeshift_name" => $this->staffTimeshift->timeshift->shift->name ?? null,
            "handover_date" => $this->handover_date ?? null,
            "handover_note" => $this->handover_note ?? null,
            "handover_status" => $this->status,
            //"staff_equipment_handover_items" => HandOverItemsResource::collection($this->staffEquipmentHandoverItems),
            "inventory_closing_items" => HandOverItemsResource::collection(
                $this->staffEquipmentHandoverItems->where('type', 'inventory_closing')
            ),
            "personal_equipment_items" => HandOverItemsResource::collection(
                $this->staffEquipmentHandoverItems->where('type', 'personal_equipment')
            ),
            "area_equipment_items" => HandOverItemsResource::collection(
                $this->staffEquipmentHandoverItems->where('type', 'area_equipment')
            ),
        ];
    }
}
