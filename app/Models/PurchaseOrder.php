<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Staff;

use App\Models\InventoryLedger;
use App\Models\PurchaseOrderItem;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends BaseModel
{
    use HasFactory;
    // protected $with=['items'];
    protected $fillable = [
        'po_id',
        'total_price',
        'date',
        'created_by',
        'manager_check_id',
        'financial_check_id',
        'manager_check_time',
        'financial_check_time',
        'is_md_checked',
        'md_check_time',
        'status',
        'created_at',
        'updated_at',
        'is_bought',
        'purchased_date_time',
        'procurement_manager_check_id',
        'procurement_manager_check_time',
        'type',
        'event_id'
    ];

    public function getCreatedAt()
    {
        return parent::getCreatedAt();
    }

    public function getUpdatedAt()
    {
        return parent::getUpdatedAt();
    }


    public function inventory_ledgers()
    {
        return $this->morphMany(InventoryLedger::class, 'ledgerable');
    }

    public function items()
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function managerCheckedBy()
    {
        return $this->belongsTo(Staff::class, 'manager_check_id');
    }

    public function financialCheckedBy()
    {
        return $this->belongsTo(Staff::class, 'financial_check_id');
    }

    public function procurementCheckedBy()
    {
        return $this->belongsTo(Staff::class, 'procurement_manager_check_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }
}
