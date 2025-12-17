<?php

namespace App\Models;

use App\Models\Area;
use App\Models\ServiceCategory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        // return $this->hasOne(EntitySession::class)
        //     ->where('is_active', 0)
        //     ->where('is_available', 1)
        //     ->whereTime('start_time', '<=', $currentTime)
        //     ->whereTime('end_time', '>=', $currentTime);
        return $this->hasOne(EntitySession::class)
        ->where('is_active', 0)
        ->where('is_available', 1)
        ->where(function ($query) use ($currentTime) {
            $query->where(function ($q) use ($currentTime) {
                // Normal sessions (same day)
                $q->whereTime('start_time', '<=', $currentTime)
                  ->whereTime('end_time', '>=', $currentTime);
            })->orWhere(function ($q) use ($currentTime) {
                // Sessions that cross midnight (start > end)
                $q->whereRaw('TIME(start_time) > TIME(end_time)')
                  ->where(function ($innerQ) use ($currentTime) {
                      $innerQ->whereTime('start_time', '<=', $currentTime)  // If current time is after start
                             ->orWhereTime('end_time', '>=', $currentTime); // Or before end
                  });
            });
        });
    }

    public function ktvProductTree(): HasMany
    {
        return $this->hasMany(Ktvproducttree::class);
    }

    public function targetMrpForecasts()
    {
        return $this->morphMany(TargetMrpForecast::class, 'mrp_forecastable');
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
