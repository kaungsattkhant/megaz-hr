<?php

namespace App\Models;

use App\Models\Staff;
use App\Models\InventoryLedger;

use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseOrder extends BaseModel
{
    use HasFactory;
    // protected $with=['items'];
    protected $fillable =[
        'po_id',
        'total_price',
        'created_by',
        'manager_check_id',
        'financial_check_id',
        'manager_check_time',
        'financial_check_time',
        'is_md_checked',
        'md_check_time',
        'status'
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

    public function createdBy(){
        return $this->belongsTo(Staff::class);
    }

    public function managerCheckedBy(){
        return $this->belongsTo(Staff::class);
    }

    public function financialCheckedBy(){
        return $this->belongsTo(Staff::class);
    }


}
