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
        'name','phone_number','nrc_number','address','gender_id','department_id','is_active'
    ];

    protected $hidden=[
        'password'
    ];

    public function getAuthPassword()
    {
        return $this->password;
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
}
