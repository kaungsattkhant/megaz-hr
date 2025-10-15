<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pack extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'menu_id','date','expired_at','created_by','status','pack_quantity','inventory_id',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
    public function inventory()
    {
        return $this->belongsTo(Inventory::class,'inventory_id');
    }

    public function pack_items()
    {
        return $this->hasMany(PackItem::class);
    }
}
