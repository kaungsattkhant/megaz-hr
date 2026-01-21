<?php

namespace App\Models;

use App\Models\Bank;
use App\Models\Role;
use App\Models\Leave;
use App\Models\Gender;
use App\Models\Salary;
use App\Models\Overtime;
use App\Models\Inventory;
use App\Models\Department;
use App\Models\TaskDetail;
use App\Models\LeaveAllowance;
use App\Models\StaffTimeshift;
use App\Models\SalaryBatchStaff;
use Laravel\Sanctum\HasApiTokens;
use App\Models\StaffCertification;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use OwenIt\Auditing\Auditable; // trait
class Staff extends Authenticatable implements AuditableContract
{
    use HasFactory, HasApiTokens,Notifiable,Auditable;

    protected $fillable = [
        'name',
        'phone_number',
        'alt_phone_number',
        'email',
        'joined_date',
        'nrc_code',
        'nrc_township_code',
        'nrc_type',
        'nrc_number',
        'bank_id',
        'birthdate',
        'father_name',
        'mother_name',
        'state',
        'city',
        'zip_code',
        'address',
        'gender_id',
        'department_id',
        'is_active',
        'password',
        'bank_account_number',
        'nrc_front_url',
        'nrc_front_path',
        'nrc_back_url',
        'nrc_back_path',
        'household_registration_url',
        'household_registration_path',
        'experience',
        'status',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by',
        'is_cv',
        'profile_image_url',
        'profile_image_path',
        'off_day_count',
        'gps_distance',
        'check_in_late_min',
        'check_out_early_min'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(StaffEmergencyContact::class);
    }

    public function routeNotificationForFcm()
    {
        return $this->personTokens()->pluck('fcm_token')->toArray();
    }

    public function personTokens()
    {
        return $this->morphMany(PersonFcmToken::class, 'personable');
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    // A mutator to encrypt the password field
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // returns Role model or null
    public function primaryRole()
    {
        return $this->roles->first(); // or $this->roles()->orderBy('pivot_priority')->first()
    }


    public function task_details()
    {
        return $this->hasMany(TaskDetail::class, 'staff_id');
    }


    // public function tasks()
    // {
    //     return $this->hasMany(Task::class, 'staff_id');
    // }

    public static function staffByDepartmentName(string $departmentName, ?string $roleName=null)
    {
        $mainQuery = self::whereHas('department', function($query) use ($departmentName, $roleName){
            $query->where('name', $departmentName);
        });

        if($roleName){
            $mainQuery->whereHas('roles', function($subQuery) use ($roleName){
                $subQuery->where('name', $roleName);
            });
        }

        return $mainQuery->get();
    }

    public static function staffByRole($roleId)
    {
        return self::whereHas('roles', function ($query) use ($roleId) {
            $query->where('id', $roleId);
        })->get();
    }

    public static function staffByRoleName($roleName)
    {
        return self::whereHas('roles', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        })->get();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function completed_tasks()
    {
        return $this->hasMany(TaskDetail::class, 'completed_by');
    }

    public function hasRoles($dept, $name)
    {
        if ($this->roles->contains('name', $name) && $this->department->name == $dept) {
            return true;
        }
        return false;
    }

    public function checkRoles($names)
    {
        // Convert $names to an array if it's not already one
        if (!is_array($names)) {
            $names = [$names];
        }

        // Check if the user has any of the roles specified in the array
        foreach ($names as $name) {
            if ($this->roles->contains('name', $name)) {
                return true;
            }
        }

        return false;
    }

    public function isDepartment($name)
    {
        if ($this->department->name == $name) {
            return true;
        }
        return false;
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class, 'feature_staff');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'skill_staff');
    }

    public function staffAdvances()
    {
        return $this->hasMany(StaffAdvance::class);
    }

    public function staffBalance()
    {
        return $this->hasOne(StaffBalance::class)->latest();
    }

    public function duties()
    {
        return $this->hasMany(Duty::class);
    }

    #scope

    #end

    public function menuSteps(): HasMany
    {
        return $this->hasMany(MenuStep::class, 'staff_id');
    }

    public function completed_objectives()
    {
        return $this->hasMany(ObjectiveStaff::class, 'completed_by');
    }
    public function in_progressed_objectives()
    {
        return $this->hasMany(ObjectiveStaff::class, 'in_progressed_by');
    }
    public function approved_objectives()
    {
        return $this->hasMany(ObjectiveStaff::class, 'approved_by');
    }

    public function cancelled_objectives()
    {
        return $this->hasMany(ObjectiveStaff::class, 'cancelled_by');
    }

    public function objectiveKeyStaff()
    {
        return $this->hasMany(ObjectiveStaff::class, 'staff_id');
    }

    public function checkIns()
    {
        return $this->hasMany(CheckIn::class, 'staff_id');
    }

    public function salaryBatchStaff()
    {
        return $this->hasMany(SalaryBatchStaff::class);
    }

    public function salary()
    {
        return $this->hasOne(Salary::class);
    }

    public function overtimes()
    {
        return $this->hasMany(Overtime::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    public function staffCertifications()
    {
        return $this->hasMany(StaffCertification::class);
    }

    public function contracts()
    {
        return $this->hasMany(StaffContract::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function leaveAllowances()
    {
        return $this->morphMany(LeaveAllowance::class, 'allowanceable');
    }
    public function staffTimeshifts()
    {
        return $this->hasMany(StaffTimeshift::class, 'staff_id');
    }
}
