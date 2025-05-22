<?php

namespace App\Models;

use App\Models\User;
use App\Models\Staff;
use App\Models\ResignCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resignation extends Model
{
    use HasFactory;

    protected $fillable = [
        'resign_category_id',
        'resignation_date',
        'status',
        'detail',
        'staff_id',
        'image_path',
        'image_url',
        'confirmed_at',
        'confirmed_by',
        'cancelled_at',
        'cancelled_by'
    ];

    public function resignCategory()
    {
        return $this->belongsTo(ResignCategory::class);
    }
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
    public function confirmedBy()
    {
        return $this->belongsTo(Staff::class, 'confirmed_by');
    }
    public function cancelledBy()
    {
        return $this->belongsTo(Staff::class, 'cancelled_by');
    }
    public function getResignationDateAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('d-m-Y');
    }
}
