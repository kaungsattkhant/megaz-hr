<?php

namespace App\Models;

use App\Models\Sop;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Objective extends Model
{
    protected $fillable = [
        'objective_name',
        'created_by',
        'is_active',
        'okr_point',
        'type',
        'repetition',
        'role_id',
        'sop_id',
    ];



    public function objectiveKeys(): HasMany
    {
        return $this->hasMany(ObjectiveKey::class, 'objective_id');
    }

    public function scopeObjectiveFilter($query, $search = null, $roleId = null, $type = null)
    {
        return $query
            ->when($search, function ($q) use ($search) {
                $q->where('objective_name', 'like', '%' . $search . '%');
            })
            ->when($roleId, function ($q) use ($roleId) {

                $q->where('role_id', $roleId);
            })
            ->when($type, function ($q) use ($type) {
                $q->where('type', $type);
            });
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function sop(): BelongsTo
    {
        return $this->belongsTo(Sop::class, 'sop_id');
    }
}
