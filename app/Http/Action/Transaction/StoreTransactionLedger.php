<?php
namespace App\Http\Action\Transaction;

use App\Models\Ledger;
use App\Models\Transaction;

class StoreTransactionLedger
{
    public function createTransaction($data){
        $data['date']=now();
        return Transaction::create($data);
    }
    
   
    public function storeLedger($data){
        return Ledger::create($data);
    }
}