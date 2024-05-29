<?php

namespace App\Repositories\AccountPayable;

use App\Models\Ledger;
use Illuminate\Support\Facades\DB;

class AccountPayableRepository implements AccountPayableInterface
{
    public function list($request){
        // $ledger=Ledger::join('accounts','ledgers.account_id','accounts.id')
        // ->join('sub_accounts','accounts.sub_account_id','sub_accounts.id')
        // ->where('sub_accounts.head_account_id',config('common.liabilities'))
        // ->where('ledgers.personable_type','supplier')
        // ->select('ledgers.id','ledgers.personable_id as supplier_id',
        // DB::raw('SUM(CASE WHEN action = "debit" THEN value ELSE 0 END) as debit_amount'),
        // DB::raw('SUM(CASE WHEN action = "credit" THEN value ELSE 0 END) as credit_amount'),
        // )
        // ->groupBy('ledgers.personable_id')
        // ->get();
        $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
        ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
        ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
        ->where('sub_accounts.head_account_id', config('common.liabilities'))
        ->where('ledgers.personable_type', 'supplier')
        ->select(
            'ledgers.personable_id as supplier_id',
            'suppliers.name as supplier_name',
            DB::raw('SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as debit_amount'),
            DB::raw('SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as credit_amount'),
            DB::raw('(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) - SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END)) as total_credit_amount')
        )
        ->groupBy('ledgers.personable_id')
        ->get();
        return $ledger;
    }
}
