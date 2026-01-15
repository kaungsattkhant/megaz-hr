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
        'is_available',
        'parent_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected static function booted()
    {
        static::saving(function ($role) {
            $parentLevel = $role->parent_id ? self::find($role->parent_id)->level ?? 0 : 0;
            $role->level = $parentLevel + ($role->parent_id ? 1 : 0);
        });

        static::saved(function ($role) {
            $role->updateDescendantLevels();
        });
    }

    public function updateDescendantLevels()
    {
        foreach ($this->children as $child) {
            $child->level = $this->level + 1;
            $child->saveQuietly();
            $child->updateDescendantLevels();
        }
    }

    // public static function rebuildLevels()
    // {
    //     foreach (self::whereNull('parent_id')->get() as $root) {
    //         $root->level = 0;
    //         $root->saveQuietly();
    //         $root->updateDescendantLevels();
    //     }
    // }

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

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Recursively collect all descendants (depth-first).
     * Returns a nested array representing the subtree.
     */
    public function descendants()
    {
        $result = [];
        foreach ($this->children as $child) {
            $result[] = [
                'role' => $child,
                'children' => $child->descendants()
            ];
        }
        return $result;
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
