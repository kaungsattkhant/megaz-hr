<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'menu_id',
        'quantity',
        'original_price',
        'discount_value',
        'price',
        'is_foc',
        'order_id',
        'status',
        'is_complete',
        'menu_service_discount_id',
        'price',
        'remark',
        'area_id',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
        'completed_at',
        'completed_by',
        'placed_at',
        'placed_by',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // public function getEntityNameAttribute()
    // {
    //     if ($this->order && $this->order->invoice) {
    //         // If entity_id is null, get unique entity names from room sessions
    //         if (is_null($this->order->invoice->entity_id)) {
    //             $uniqueEntities = $this->order->invoice->roomSession
    //                 ->pluck('entitySession.entity')
    //                 ->unique('id') // Keep unique entities by ID
    //                 ->pluck('name'); // Extract names

    //             return $uniqueEntities->implode(', '); // Join names with a comma
    //         }

    //         // If entity_id is not null, return the table name
    //         return $this->order->invoice->table->name ?? '';
    //     }

    //     return '';
    // }
}
