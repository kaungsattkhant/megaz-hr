<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalaryBatchStaff extends Model
{
    use HasFactory;
    protected $fillable = [
        'salary_batch_id',
        'staff_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function salaryBatch()
    {
        return $this->belongsTo(SalaryBatch::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
