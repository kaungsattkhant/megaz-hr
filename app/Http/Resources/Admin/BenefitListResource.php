<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BenefitListResource extends JsonResource
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
            'type'=>$this->type,
            'name'=>$this->type=='menu' ? ($this->menu->name ?? null ): $this->name,
            'cost'=>$this->type=='menu'  ? ($this->menu->prices[0]->price ?? 0) : $this->cost
        ];
    }
}
