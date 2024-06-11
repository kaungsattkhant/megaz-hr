<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Gender;

class Customer extends Model
{
    use HasFactory;

    protected $fillable=[
        'gender_id','name','phone_number','birthdate','email','address','is_active','township_id','rentation'
    ];

    public function gender()
    {
        return $this->belongsTo(Gender::class);
    }

    public function township()
    {
        return $this->belongsTo(Township::class);
    }
}
