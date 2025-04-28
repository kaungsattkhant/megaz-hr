<?php

namespace App\Models;

use App\Models\Role;
use App\Models\SalaryAllowance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalarySetup extends Model
{
    use HasFactory;

    protected $fillable = [
        'basic_salary',
        'role_id',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
    public function salaryAllowances()
    {
        return $this->hasMany(SalaryAllowance::class);
    }
}
