<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StaffOrganizationChartResource extends JsonResource
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
            'name' => $this->name,
            'department'=>$this->department,
            'role' => $this->when($this->primaryRole(), [
                'id' => $this->primaryRole()->id ?? null,
                'name' => $this->primaryRole()->name ?? null,
                'level' => $this->primaryRole()->level ?? null,
            ]),
            'children' => isset($this->children) ? StaffOrganizationChartResource::collection($this->children) : [],
        ];
    }
}