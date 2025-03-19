<?php

namespace App\Models;

use App\Models\AccessoryItem;
use App\Models\AccessoryCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Accessory extends Model
{
    use HasFactory;
    protected $fillable=['code','accessory_category_id','name','image_url','image_path','is_feature','is_active','created_by'];
    
    protected $with=['accessory_price'];
    public function accessory_category(){
        return $this->belongsTo(AccessoryCategory::class);
    }

    public function accessory_price(){
        return $this->hasOne(AccessoryPrice::class)->orderBy('id', 'desc');
    }
    public function accessory_items(){
        return $this->hasMany(AccessoryItem::class);
    }
}
