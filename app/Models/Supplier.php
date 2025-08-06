<?php

namespace App\Models;

use App\Models\SupplierItem;
use App\Models\SupplierPhone;
use App\Models\AccountPayable;
use App\Models\SupplierBankAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'creditor_account_id',
        'name',
        'shop_name',
        'address',
        'email',
        'credit_limit',
        'credit_opening_date',
        'credit_opening_amount',
        'lead_time_day',
        'lead_time_hour',
        'lead_time_minutes',
        'credit_term_type',
        'day',
        'amount_limitation',
        'exact_date',
    ];

    public function items()
    {
        // return $this->belongsToMany(Item::class,);
        return $this->belongsToMany(Item::class, 'supplier_items')
            ->withPivot('brand_id'); // Include brand_id in the pivot data
        // ->withTimestamps();
    }

    public function supplier_items()
    {
        // return $this->belongsToMany(Item::class,);
        return $this->hasMany(SupplierItem::class);
        // ->withTimestamps();
    }


    public function account()
    {
        return $this->belongsTo(Account::class);
    }


    public function creditorAccount()
    {
        return $this->belongsTo(Account::class, 'creditor_account_id');
    }
    public function supplierPhone()
    {
        return $this->hasMany(SupplierPhone::class, 'supplier_id');
    }

    public function supplierBankAccount()
    {
        return $this->hasMany(SupplierBankAccount::class, 'supplier_id');
    }

    public function accountPayables()
    {
        return $this->hasMany(AccountPayable::class);
    }
}
