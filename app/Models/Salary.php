<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\SalarySetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Salary extends Model
{
    use HasFactory;

    protected $fillable = [
        'basic_salary',
        'staff_id',
        'salary_setup_id',
        'created_by',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function salarySetup()
    {
        return $this->belongsTo(SalarySetup::class);
    }
}
