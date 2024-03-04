<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Inventory;
use App\Models\Role;
use App\Models\Staff;

class Department extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'name','inventoryable_id','inventoryable_type'
    ];

    protected $hidden=[
        'created_at','updated_at'
    ];

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    public function staffs()
    {
        return $this->hasMany(Staff::class);
    }

    public function roles()
    {
        return $this->hasMany(Role::class);
    }

    public function inventory()
    {
        return $this->morphOne(Inventory::class, 'inventoryable');
    }
}
