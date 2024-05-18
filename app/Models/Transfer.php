<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Inventory;
use App\Models\Item;
use App\Models\Staff;

class Transfer extends BaseModel
{
    use HasFactory;

    protected $fillable =[
        'transfer_id','source_inventory_id','destination_inventory_id',
        'quantity','item_id', 'date','created_by', 'confirmed_at',
        'confirmed_by', 'status','uom_id','uom_conversion_id',
    ];

    public function confirmed_by()
    {
        return $this->belongsTo(Staff::class, 'confirmed_by');
    }

    public function created_by()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function uomConversion(){
        return $this->belongsTo(UomConversion::class,'uom_conversion_id');
    }

    public function uom(){
        return $this->belongsTo(Uom::class,'uom_id');
    }

    public function source_inventory()
    {
        return $this->belongsTo(Inventory::class, 'source_inventory_id');
    }

    public function destination_inventory()
    {
        return $this->belongsTo(Inventory::class, 'destination_inventory_id');
    }
}
