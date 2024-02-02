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

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function staffs()
    {
        return $this->belongsToMany(Staff::class);
    }
}
