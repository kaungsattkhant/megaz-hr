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
        'role_id',
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function menuStepItem(): HasMany
    {
        return $this->hasMany(MenuStepItem::class);
    }
}
