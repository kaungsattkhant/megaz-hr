<?php

namespace App\Repositories\FinancialReport;

use App\Models\Ledger;


class FinancialRepository implements FinancialInterface
{
    public function CashFlowStatement($request){
        $currentMonth=now()->month;

        
        return Ledger::join('accounts','ledgers.account_id','accounts.id')
        ->join('sub_accounts','accounts.sub_account_id','sub_accounts.id')
        ->where('action','debit')
        ->groupBy('sub_accounts.id','sub_accounts.name','sub_accounts.code')
        ->select('sub_accounts.code','sub_accounts.name')
        ->get();
    }

}