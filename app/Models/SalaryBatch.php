<?php

namespace App\Models;

use App\Models\SalaryBatchStaff;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'day_of_monthly',
        'created_by',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function salaryBatchStaff()
    {
        return $this->hasMany(SalaryBatchStaff::class);
    }
}
