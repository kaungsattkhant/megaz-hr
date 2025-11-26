<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessoryPackage extends Model
{
    use HasFactory;
    protected $fillable = ['accessory_id', 'quantity', 'package_id'];

    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }

}
