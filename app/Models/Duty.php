<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Duty extends Model
{
    use HasFactory;

    protected $fillable=[
        'date','staff_id','cooking_place_id','created_by'
    ];

    public function tasks()
    {
        return $this->belongsToMany(Task::class,'duty_task');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function cookingPlace()
    {
        return $this->belongsTo(CookingPlace::class);
    }
}
