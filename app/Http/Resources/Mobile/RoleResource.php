<?php

namespace App\Http\Resources\Mobile;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
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
            'name'=>$this->name,
            'level'=>$this->level,
            'department'=>$this->department,
            'staffs'=>$this->staffs->map(function($staff) {
                return [
                    'id'=>$staff->id,
                    'name'=>$staff->name,
                ];
            }),
            'children' => isset($this->children) ? RoleResource::collection($this->children) : [],
        ];
    }
}
