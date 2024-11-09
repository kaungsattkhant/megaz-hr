<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuStep extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'staff_id',
        'staff_quantity',
        'duration',
        'order_time',
        'expected_quantity',
        'level',
        'type'
    ];

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function menuStepItem(): HasMany
    {
        return $this->hasMany(MenuStepItem::class);
    }
}
