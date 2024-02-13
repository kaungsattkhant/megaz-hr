<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Department;
use App\Models\Staff;

class Role extends Model
{
    use HasFactory;

    protected $fillable=[
        'name','department_id'
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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function staffs()
    {
        return $this->belongsToMany(Staff::class);
    }
}
