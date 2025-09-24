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
        return[
            "id" => $this->id,
            "item_id" => $this->item_id ?? null,
            "quantity" => $this->quantity ?? null,
            "item_name"=>$this->item->name ?? null,
            "item_uom"=>$this->item->item_uom ?? null,
        ];
    }
}
