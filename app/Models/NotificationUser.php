<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUser extends Model
{
    use HasFactory;
    protected $fillable = ['is_read', 'is_read_count', 'read_at', 'staff_id', 'notification_id', 'title', 'preview', 'type'];
    protected $hidden = ['created_at', 'updated_at'];
    public function notification()
    {
        return $this->belongsTo(Notification::class);
    }
}
