<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\ServiceCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory,SoftDeletes;

    protected $with=['staff'];
    protected $fillable=['service_category_id','name','staff_id','price_per_hour','is_available','area_id','is_active'];
    public function service_category(){
        return $this->belongsTo(ServiceCategory::class);
    }
    public function staff(){
        return $this->belongsTo(Staff::class);
    }
}
