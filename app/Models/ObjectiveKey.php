<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjectiveKey extends Model
{
    protected $fillable = [
        'objective_id',
        'role_id',
        'name',
        'okr_point',
        'duration',
    ];

    public function objective(): BelongsTo
    {
        return  $this->belongsTo(Objective::class, 'objective_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function objKeyStaff(): HasMany
    {
        return $this->hasMany(ObjectivekeyStaff::class, 'objective_key_id');
    }


    public function getAssignedDaysAttribute($value)
    {
        return explode(',', $value);
    }
}
