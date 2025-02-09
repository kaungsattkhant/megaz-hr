<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuStepItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_step_id',
        'item_id',
        'uom_id',
        'uom_type',
        'quantity',
        'weight'
    ];

    public function menuStep(): BelongsTo
    {
        return $this->belongsTo(MenuStep::class, 'menu_step_id');
    }
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function uom(): BelongsTo
    {
        return $this->belongsTo(Uom::class, 'uom_id');
    }
}
