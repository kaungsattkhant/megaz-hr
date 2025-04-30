<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\LeaveCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'leave_category_id',
        'title',
        'detail',
        'start_date',
        'end_date',
        'day',
        'isIncludeWeekends',
        'staff_id',
        'status',
        'created_by',
        'image_url',
        'image_path',
        'is_unpaid_leave',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by'
    ];
    protected $hidden = ['created_at', 'updated_at'];

    public function leaveCategory(): BelongsTo
    {
        return $this->belongsTo(LeaveCategory::class, 'leave_category_id');
    }
    public function staff(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }
    public function confirmed_by()
    {
        return $this->belongsTo(Staff::class, 'confirmed_by');
    }
    public function cancelled_by()
    {
        return $this->belongsTo(Staff::class, 'cancelled_by');
    }

    public function created_by()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }
}
