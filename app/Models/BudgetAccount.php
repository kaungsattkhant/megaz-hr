<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetAccount extends Model
{
    //
    protected $fillable = [
        'budget_priority_id',
        'date',
        'amount',
        'main_account_id',
        'main_account_type',
        'sub_account_id',
        'sub_account_type',
        'status',
        'is_active',
    ];

    public function budgetPriority()
    {
        return $this->belongsTo(BudgetPriority::class);
    }

    public function mainAccount()
    {
        return $this->morphTo('main_account');
    }

    public function subAccount()
    {
        return $this->morphTo('sub_account');
    }
}
