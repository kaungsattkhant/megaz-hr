<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

use Laravel\Sanctum\HasApiTokens;

use App\Models\Department;
use App\Models\Gender;
use App\Models\Role;

class Staff extends Authenticatable
{
    use HasFactory, HasApiTokens;

    protected $fillable=[
        'name','phone_number','nrc_number','address','gender_id','department_id','is_active','password'
    ];

    protected $hidden=[
        'password','remember_token','created_at','updated_at'
    ];

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

    public function completed_tasks()
    {
        return $this->hasMany(Task::class, 'completed_by');
    }

    public function hasRoles($name){
        if($this->roles->contains('name',$name)){
            return true;
        }
        return false;
    }

    #scope 
   
    #end
}
