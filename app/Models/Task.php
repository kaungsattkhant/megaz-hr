<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable=[
        'name','task','assigned_at','completed_at','completed_by',
        'is_double_checked','double_checked_by',
        'status','role_id'
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get staff of completed_by.
     */
    public function completedBy()
    {
        return $this->belongsTo(Staff::class, 'completed_by');
    }

    public function doubleCheckedBy()
    {
        return $this->belongsTo(Staff::class, 'double_checked_by');
    }
}
