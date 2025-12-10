<?php

namespace App\Models;

use App\Models\PaySlipAllowance;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaySlip extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'salary_batch_id',
        'salary_id',
        'basic_salary',
        'allowance',
        'deduction',
        'is_confirm',
        'added_allowance',
        'added_deduction',
        'total_allowance',
        'overtime',
        'net_salary',
        'confirmed_at',
        'confirmed_by',
        'created_by'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function salaryBatch()
    {
        return $this->belongsTo(SalaryBatch::class, 'salary_batch_id');
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    public function salary()
    {
        return $this->belongsTo(Salary::class, 'salary_id');
    }
    public function paySlipAllowances()
    {
        return $this->hasMany(PaySlipAllowance::class, 'pay_slip_id');
    }
}
