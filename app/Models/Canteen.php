<?php

namespace App\Models;

use App\Models\Department;
use App\Models\CanteenItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Canteen extends Model
{
    use HasFactory;
    protected $fillable=['date','department_id','created_by'];
    public function canteen_items()
    {
        return $this->hasMany(CanteenItem::class);
    }

    public function department(){
        return $this->belongsTo(Department::class);
    }

}
