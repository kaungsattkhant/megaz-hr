<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveCategory extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'is_active'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function leaveAllowances()
    {
        return $this->hasMany(LeaveAllowance::class);
    }
    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }
}
