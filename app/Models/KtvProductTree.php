<?php

namespace App\Models;

use App\Models\Entity;
use App\Models\KtvItem;
use App\Models\KtvObjective;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class KtvProductTree extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'entity_id',
        'created_by'
    ];

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'entity_id');
    }

    public function KtvObjectives(): HasMany
    {
        return $this->hasMany(KtvObjective::class);
    }

    public function KtvItems(): HasMany
    {
        return $this->hasMany(KtvItem::class);
    }
}
