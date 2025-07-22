<?php

namespace App\Models;

use App\Models\CompletedObjectiveKey;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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

    // public function getAssignedDaysAttribute($value)
    // {
    //     return explode(',', $value);
    // }
}
