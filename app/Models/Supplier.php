<?php

namespace App\Models;

use App\Models\SupplierItem;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    
    protected $fillable=['name','shop_name','phone_number','address','credit_limit','account_id','creditor_account_id'];

    public function items()
    {
        // return $this->belongsToMany(Item::class,);
        return $this->belongsToMany(Item::class,'supplier_items')
        ->withPivot('brand_id'); // Include brand_id in the pivot data
        // ->withTimestamps();
    }

    public function supplier_items()
    {
        // return $this->belongsToMany(Item::class,);
        return $this->hasMany(SupplierItem::class);
        // ->withTimestamps();
    }
    
   

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function creditorAccount(){
        return $this->belongsTo(Account::class,'creditor_account_id');
    }
}
