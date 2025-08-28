<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaEquipmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $areaInventoryable = collect($this->inventory_ledger->inventory->inventoryable)
        ->firstWhere('inventoryable_type', 'area');
        
        $area = $areaInventoryable ? $areaInventoryable->inventoryable : null;
        return[
            "id" => $this->id,
            "item_id" => $this->item_id ?? null,
            "quantity" => $this->quantity ?? null,
            "item_name"=>$this->item->name ?? null,
            "inventoryable" => $area ? [
                "id" => $area->id,
                "name" => $area->name,
                // "area_type_id" => $area->area_type_id,
                // "area_category_id" => $area->area_category_id,
                "department_id" => $area->department_id ?? null,
                "department" => $area->department->name ?? null,
        ] : null,
            "item_uom"=>$this->item->item_uom ?? null,
        ];
    }
}
