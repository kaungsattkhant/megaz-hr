<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ObjectiveKey extends Model
{
    protected $fillable = [
        'objective_id',
        'name'
    ];

    public function objective(): BelongsTo
    {
        return  $this->belongsTo(Objective::class, 'objective_id');
    }

    public function getAssignedDaysAttribute($value)
    {
        return explode(',', $value);
    }
}
