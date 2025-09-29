<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InventoryLedgerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "name" => $this->resource['name'] ?? null,
            "item_id" => $this->resource['item_id'] ?? null,
            'current_quantity' => $this->resource['closing_balance'] ?? null,
            'base_uom_id' => $this->resource['base_unit_id'] ?? null,
            'base_uom_name' => $this->resource['base_uom_name'] ?? null,
            'uom_id' => $this->resource['item_uom_id'] ?? null,
            'uom_name' => $this->resource['conversion_uom_name'] ?? null,
            'conversion' => $this->resource['conversion'] ?? null,
        ];
    }
}
