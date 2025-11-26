<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "date" => $this->date,
            "menu_id" => $this->menu_id ?? null,
            "quantity" => $this->quantity,
            "area_id" => $this->area_id ?? null,
            "original_price" => $this->original_price,
            "discount_value" => $this->discount_value,
            "price" => $this->price,
            "is_foc" => $this->is_foc,
            "order_id" => $this->order_id ?? null,
            "status" => $this->status,
            "is_complete" => $this->is_complete,
            "progressed_at" => $this->progressed_at,
            "progressed_by" => $this->progressed_by,
            "confirmed_at" => $this->confirmed_at,
            "confirmed_by" => $this->confirmed_by,
            "cancelled_at" => $this->cancelled_at,
            "cancelled_by" => $this->cancelled_by,
            "kitchen_cancelled_at" => $this->kitchen_cancelled_at,
            "kitchen_cancelled_by" => $this->kitchen_cancelled_by,
            "completed_at" => $this->completed_at,
            "completed_by" => $this->completed_by,
            "placed_at" => $this->placed_at,
            "placed_by" => $this->placed_by,
            "menu_service_discount_id" => $this->menu_service_discount_id,
            "remark" => $this->remark,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "area" => $this->area,
            "menu" => $this->menu ? [
                "id" => $this->menu->id,
                "code" => $this->menu->code,
                "menu_category_id" => $this->menu->menu_category_id,
                "name" => $this->menu->name,
                "image_url" => $this->menu->image_url,
                "image_path" => $this->menu->image_path,
                "is_feature" => (bool) $this->menu->is_feature,
                "is_active" => (bool) $this->menu->is_active,
                "description" => $this->menu->description,
                "menu_places" => $this->menu->menuPlaces->map(function ($place) {
                    return $place ? [
                        "id" => $place->id,
                        'name' => $place->name,
                        "area_id" => $place->area_id,
                        "pivot" => [
                            "menu_id" => $place->pivot->menu_id ?? null,
                            "cooking_place_id" => $place->pivot->cooking_place_id ?? null,
                        ],
                    ] : null;
                })->filter()->values(),
                "areas" => $this->menu->menuPlaces && $this->menu->menuPlaces->isNotEmpty()  ? $this->menu->menuPlaces->map(function ($data) {
                    return $data->area ? [
                        "id" => $data->area->id,
                        "name" => $data->area->name,
                        "area_type_id" => $data->area->area_type_id,
                        "area_category_id" => $data->area->area_category_id,
                        "department_id" => $data->area->department_id,
                        "is_active" => (bool) $data->area->is_active,
                    ] : null;
                })->filter()->values() : null,
            ] : null,
        ];
    }
}
