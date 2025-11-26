<?php

namespace App\Models;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HeadCount extends Model
{
    use HasFactory;

    protected $fillable= [
        'total_head_count',
        'male',
        'child',
        'female'
    ];
    
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }
}
