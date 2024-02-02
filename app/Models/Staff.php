<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Staff extends Model
{
    use HasFactory;

    protected $fillable=[
        'name','phone_number','nrc_no','address','gender_id','department_id','is_verified'
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
