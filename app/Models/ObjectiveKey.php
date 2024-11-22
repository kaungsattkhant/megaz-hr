<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjectiveKey extends Model
{
    protected $fillable = [
        'objective_id',
        'name',
        'okr_point'
    ];

    public function objective(): BelongsTo
    {
        return  $this->belongsTo(Objective::class, 'objective_id');
    }
    public function objKeyStaff(): HasMany
    {
        return $this->hasMany(ObjectivekeyStaff::class);
    }

    public function objImages(): HasMany
    {
        return $this->hasMany(ObjectivekeyImage::class);
    }
}
