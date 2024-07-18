<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Gender;
use App\Models\Inventory;

use App\Models\Department;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Staff extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable=[
        'name',
        'phone_number',
        'alt_phone_number',
        'email',
        'joined_date',
        'nrc_number',
        'birthdate',
        'father_name',
        'mother_name',
        'state',
        'city',
        'zip_code',
        'address',
        'gender_id',
        'department_id',
        'area_id',
        'is_active',
        'password',
        'bank_account_number',
        'nrc_front_url',
        'nrc_front_path',
        'nrc_back_url',
        'nrc_back_path',
        'household_registration_url',
        'household_registration_path'
    ];

    protected $hidden=[
        'password','remember_token','created_at','updated_at'
    ];

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class);
    }

    public function emergencyContacts()
    {
        return $this->hasMany(StaffEmergencyContact::class);
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

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public static function staffByRole($roleId)
    {
        return self::whereHas('roles', function($query) use ($roleId) {
            $query->where('id', $roleId);
        })->get();
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function completed_tasks()
    {
        return $this->hasMany(Task::class, 'completed_by');
    }

    public function hasRoles($dept,$name){
        if($this->roles->contains('name',$name) && $this->department->name==$dept){
            return true;
        }
        return false;
    }

    public function checkRoles($names){
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

    public function isDepartment($name){
        if($this->department->name==$name){
            return true;
        }
        return false;
    }

    public function features()
    {
        return $this->belongsToMany(Feature::class,'feature_staff');
    }

    #scope

    #end
}
