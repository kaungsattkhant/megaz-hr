<?php

namespace App\Models;

use App\Models\SubAccount;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory;
    protected $fillable=[
        'name',
        'account_code',
        'sub_account_id',
        'account_id',
        'link_account_id',
        'type',
        'is_active'
    ];

    public function sub_account(){
        return $this->belongsTo(SubAccount::class);
    }

    public function accountByCode($code){
        return Account::where('account_code',$code)->first();
    }

    public function accountReceivables()
    {
        return $this->hasMany(AccountReceivable::class);
    }
    public static function getByAccountCode(string $accountCode)
    {
        return self::where('account_code', $accountCode)->first();
    }
}
