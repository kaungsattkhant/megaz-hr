<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KtvObjective extends Model
{
    use HasFactory;

    protected $fillable =
    [
        'ktv_product_tree_id',
        'objective_id'
    ];

    public function ktvProductTree(): BelongsTo
    {
        return $this->belongsTo(KtvProductTree::class, 'ktv_product_tree_id');
    }

    public function objective(): BelongsTo
    {
        return $this->belongsTo(Objective::class, 'objective_id');
    }
}
