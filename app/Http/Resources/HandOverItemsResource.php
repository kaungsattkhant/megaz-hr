<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HandOverItemsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id ?? null,
            "staff_equipment_handover_id" => $this->staff_equipment_handover_id ?? null,
            "item_id" => $this->item_id ?? null,
            "item_name" => $this->item->name ?? null,
            "item_code" => $this->item->code ?? null,
            "base_uom_id" => $this->item->base_uom_id ?? null,
            "base_uom_name" => $this->item->base_uom_name ?? null,
            "uom_id" => $this->uom_id ?? null,
            "uom_name" => $this->item->item_uom ?? null,
            "uom_quantity" => $this->uom_quantity ?? null,
            "quantity" => $this->quantity ?? null,
            "uom_type" => $this->uom_type ?? null,
            "notes" => $this->notes ?? null,
            "type" => $this->type ?? null,
            "uom_conversion" => $this->item->uom_conversion ?? null,
        ];
    }
}
