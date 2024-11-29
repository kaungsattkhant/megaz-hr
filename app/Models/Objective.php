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

    public function scopeObjectiveFilter($query, $search = null, $name = null, $days = null, $role = null, $department = null)
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
            ->when($role, function ($q) use ($role) {
                $q->whereHas('objectiveKeys.role', function ($roleQuery) use ($role) {
                    $roleQuery->where('name', '=', $role);
                });
            })
            ->when($department, function ($q) use ($department) {
                $q->whereHas('objectiveKeys.role.department', function ($deptQuery) use ($department) {
                    $deptQuery->where('name', 'like', '%' . $department . '%');
                });
            })
            ->when($name, function ($q) use ($name) {
                $q->whereHas('objectiveKeys', function ($keyQuery) use ($name) {
                    $keyQuery->where('name', 'like', '%' . $name . '%');
                });
            })
            ->when($days, function ($q) use ($days) {
                $q->whereHas('objectiveKeys', function ($subQuery) use ($days) {
                    if (is_array($days)) {
                        foreach ($days as $day) {
                            $subQuery->orWhereRaw("FIND_IN_SET(?, assigned_days)", [$day]);
                        }
                    } else {
                        $subQuery->whereRaw("FIND_IN_SET(?, assigned_days)", [$days]);
                    }
                });
            });
    }
}
