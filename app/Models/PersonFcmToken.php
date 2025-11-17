<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonFcmToken extends Model
{
    use HasFactory;
    protected $fillable=['fcm_token','personable_id','personable_type'];

}
