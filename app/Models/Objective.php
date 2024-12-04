<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Objective extends Model
{
    protected $fillable = [
        'objective_name',
        'created_by',
        'is_active',
    ];



    public function objectiveKeys(): HasMany
    {
        return $this->hasMany(ObjectiveKey::class, 'objective_id');
    }

    public function scopeObjectiveFilter($query, $search = null, $roleId = null)
    {
        return $query
            ->when($search, function ($q) use ($search) {
                $q->where('objective_name', 'like', '%' . $search . '%')
                    ->orWhereHas('objectiveKeys', function ($subQuery) use ($search) {
                        $subQuery->where('name', 'like', '%' . $search . '%')
                            ->orWhere('okr_point', 'like', '%' . $search . '%')
                            ->orWhere('assigned_days', 'like', '%' . $search . '%');
                    });
            })
            ->when($roleId, function ($q) use ($roleId) {

                $q->whereHas('objectiveKeys.role', function ($roleQuery) use ($roleId) {

                    $roleQuery->where('role_id', $roleId);
                });
            });
    }
}
