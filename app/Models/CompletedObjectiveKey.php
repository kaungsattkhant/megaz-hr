<?php

namespace App\Models;

use App\Models\ObjectiveKey;
use App\Models\ObjectiveStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompletedObjectiveKey extends Model
{
    use HasFactory;
    protected $fillable = [
        'objective_key_id',
        'objective_staff_id',
    ];

    public function objectiveKey(): BelongsTo
    {
        return $this->belongsTo(ObjectiveKey::class, 'objective_key_id');
    }

    public function objectiveStaff(): BelongsTo
    {
        return $this->belongsTo(ObjectiveStaff::class, 'objective_staff_id');
    }
}
