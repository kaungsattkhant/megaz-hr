<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\ServiceCategory;

class Entity extends BaseModel
{
    use HasFactory;

    protected $fillable = [
        'area_id',
        'name',
        'service_category_id',
        'price_per_hour',
        'is_available',
        'entity_type',
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

    public function latestInvoice()
    {
        return $this->hasOne(Invoice::class)->orderBy('id', 'desc');
    }

    public function menuServiceDiscounts()
    {
        return $this->morphMany(MenuServiceDiscount::class, 'discountable');
    }

    public function roomDiscounts()
    {
        return $this->belongsToMany(RoomDiscount::class);
    }

    public function entitySessions()
    {
        return $this->hasMany(EntitySession::class);
    }

    public function latestRoomSession()
    {
        return $this->hasOne(RoomSession::class)->latestOfMany();
    }

    public function currentEntitySession($currentTime)
    {
        return $this->hasOne(EntitySession::class)
            ->where('is_active', 0)
            ->where('is_available', 1)
            ->whereTime('start_time', '<=', $currentTime)
            ->whereTime('end_time', '>=', $currentTime);
    }


}
