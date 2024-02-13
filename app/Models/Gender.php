<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Staff;

class Gender extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name'
    ];

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }
}
