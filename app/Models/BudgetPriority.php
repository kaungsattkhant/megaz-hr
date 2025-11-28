<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetPriority extends Model
{
    //
    protected $fillable = [
        'label',
        'priority'
    ];

    public function budgetAccounts()
    {
        return $this->hasMany(BudgetAccount::class);
    }
}
