<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Role;
use App\Models\Staff;

class Department extends Model
{
    use HasFactory;

    protected $fillable=[
        'name'
    ];

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }
}
