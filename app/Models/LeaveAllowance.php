<?php

namespace App\Models;

use App\Models\Role;
use App\Models\Staff;
use App\Models\LeaveCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveAllowance extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'role_id',
        'leave_category_id',
        'day',
        'created_by'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function leaveCategory(): BelongsTo
    {
        return $this->belongsTo(LeaveCategory::class, 'leave_category_id');
    }
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
    public function created_by()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }
}
