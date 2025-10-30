<?php

namespace App\Models;

use App\Models\JobSpecification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'skill',
        'role_id',
        'created_by'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function availableCookingPlaces()
    {
        return $this->morphMany(AvailableCookingPlace::class, 'cooking_placeable');
    }

    public function staffs()
    {

        return $this->belongsToMany(Staff::class,'skill_staff');
    }

    public function jobSpecifications(): BelongsToMany
    {
        return $this->belongsToMany(JobSpecification::class, 'job_specification_skill','skill_id','job_specification_id');
    }
}
