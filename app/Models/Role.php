<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Department;
use App\Models\Staff;

class Role extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'department_id',
        'is_available'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
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

    public function skills()
    {
        return $this->hasMany(Skill::class, 'role_id');
    }

    public static function getRoleIdByDepartment($departmentId, $roleName)
    {
        $role = self::where('department_id', $departmentId)
            ->where('name', $roleName)
            ->first();

        return $role ? $role->id : null;
    }

    public static function getRoleByName($name)
    {
        $role = self::where('name', $name)
            ->first();
        if (!$role) {
            ResponseMessage('Reception Role not found', 419);
        }
        return $role;
    }

    public function mrpHrs()
    {
        return $this->hasMany(MrpHr::class, 'role_id');
    }

    public function leaveAllowances()
    {
        return $this->morphMany(LeaveAllowance::class, 'allowanceable');
    }
}
