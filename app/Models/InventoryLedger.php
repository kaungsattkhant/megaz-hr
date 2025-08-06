<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Inventory;
use App\Models\InventoryLedgerItem;

class InventoryLedger extends BaseModel
{
    use HasFactory;
    protected $fillable = ['inventory_id', 'date', 'ledgerable_id', 'ledgerable_type','action','batch_no'];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    public function inventory_ledger_items()
    {
        return $this->hasMany(InventoryLedgerItem::class);
    }

    public function ledgerable()
    {
        return $this->morphTo();
    }

}
