<?php

namespace App\Models;

use App\Models\KtvProductTree;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KtvItem extends Model
{
    use HasFactory;
    protected $fillable =
    [
        'ktv_product_tree_id',
        'item_id',
        'quantity'
    ];

    public function ktvProductTree(): BelongsTo
    {
        return $this->belongsTo(KtvProductTree::class, 'ktv_product_tree_id');
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
