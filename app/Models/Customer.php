<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Models\Gender;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    // use HasFactory, Notifiable;
    use HasFactory, HasApiTokens;


    protected $fillable=[
        'gender_id','name','phone_number','birthdate','email','address','is_active','township_id','rentation','password','otp','image_url','image_path'
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function township()
    {
        return $this->belongsTo(Township::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }


}
