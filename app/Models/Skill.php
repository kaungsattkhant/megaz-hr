<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable =[
        'skill','role_id','created_by'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id');
    }

    public function availableCookingPlaces()
    {
        return $this->morphMany(AvailableCookingPlace::class, 'cooking_placeable');
    }

        public function staffs()
        {
            return $this->belongsToMany(Staff::class,'skill_staff');
        }
}
