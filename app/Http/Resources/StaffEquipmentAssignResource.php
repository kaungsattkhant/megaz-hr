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
        $baseQuantity = 0;
        $uomQuantity = 0;
        if( !empty($this->uom_type) && $this->uom_type == "uom" && $this->uom_quantity < $this->item->uom_conversion){
            $uomQuantity = $this->uom_quantity;
        }else{
            $baseQuantity = floor($this->uom_quantity / $this->item->uom_conversion); //full pkt,
            $uomQuantity = $this->uom_quantity % $this->item->uom_conversion; //remaining pcs
        }
        return [
            'id' => $this->id,
            'staff_id' => $this->staffEquipment->staff_id ?? null,
            'item_id' => $this->item_id,
            'item' => $this->item->name ?? null,
            'base_quantity' => $baseQuantity,
            'base_uom_name' => $this->item->base_uom_name ?? null,
            'base_uom_id' => $this->item->base_uom_id ?? null,
            'uom_quantity' => $uomQuantity,
            'uom_name' => $this->item->item_uom ?? null,
            'uom_id' => $this->item->uom_id ?? null,
            'uom_type' => $this->uom_type,
            'uom_conversion' => $this->item->uom_conversion ?? null,
        ];
    }
}
