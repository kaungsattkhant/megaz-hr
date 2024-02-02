<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable=[
        'name','task','assigned_at','completed_at','completed_by','is_double_checked','doubled_checked_by','status','role_id'
    ];
}
