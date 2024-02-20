<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\InventoryLedger;

class InventoryLedgerItem extends BaseModel
{
    use HasFactory;
    protected $fillable = ['inventory_ledger_id', 'item_id', 'quantity'];

    public function inventory_ledger()
    {
        $this->belongsTo(InventoryLedger::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
