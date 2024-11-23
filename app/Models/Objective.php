<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Objective extends Model
{
    protected $fillable = [
        'objective_name',
        'role_id',
        'created_by',
        'is_active',
    ];


    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function objectiveKeys(): HasMany
    {
        return $this->hasMany(ObjectiveKey::class, 'objective_id');
    }

    public function scopeObjectiveFilter($query, $search = null, $name = null, $days = null)
    {
        return $query

            ->when($search, function ($q) use ($search) {
                $q->where(function ($query) use ($search) {
                    $query->where('objective_name', 'like', '%' . $search . '%')
                        ->orWhereHas('objectiveKeys', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', '%' . $search . '%')
                                ->orWhere('okr_point', 'like', '%' . $search . '%')
                                ->orWhere('assigned_days', 'like', '%' . $search . '%');
                        });
                });
            })

            ->when($name, function ($q) use ($name) {
                $q->whereHas('objectiveKeys', function ($sq) use ($name) {
                    $sq->where('name', [$name]);
                });
            })

            ->when($days, function ($q) use ($days) {
                $q->whereHas('objectiveKeys', function ($subQuery) use ($days) {
                    $subQuery->whereRaw("FIND_IN_SET(?, assigned_days)", [$days]);
                });
            });
    }
}
