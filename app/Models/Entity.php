<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ServiceCategory;

class Entity extends BaseModel
{
    use HasFactory;

    protected $fillable=[
        'area_id','name','service_category_id','price_per_hour','is_available','entity_type',
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }

    public function service_category()
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function menuServiceDiscounts()
    {
        return $this->morphMany(MenuServiceDiscount::class, 'discountable');
    }

    public function roomDiscounts()
    {
        return $this->belongsToMany(RoomDiscount::class);
    }


}
