<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HrForecastResource extends JsonResource
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

            "menu_id" => $this->menu_id,
            "role_id" => $this->role_id ?? null,
            "role_name" => $this->role->name ?? null,
            "duration" =>  $this->duration,
        ];
    }
}
