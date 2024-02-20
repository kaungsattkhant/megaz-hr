<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\InventoryLedger;
use App\Models\PurchaseOrderItem;

class PurchaseOrder extends BaseModel
{
    use HasFactory;
    protected $fillable =[
        'po_id','total_price','created_by','kitchen_check_id','financial_check_id','kitchen_check_time','financial_check_time'
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
}
