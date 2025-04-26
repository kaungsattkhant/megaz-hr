<?php

namespace App\Models;

use App\Models\Allowance;
use App\Models\SalarySetup;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SalaryAllowance extends Model
{
    use HasFactory;
    protected $fillable = [
        'salary_setup_id',
        'allowance_id',
        'amount',
    ];
    protected $hidden = [
        'created_at',
        'updated_at',
    ];
    public function salarySetup()
    {
        return $this->belongsTo(SalarySetup::class);
    }
    public function allowance()
    {
        return $this->belongsTo(Allowance::class);
    }
}
