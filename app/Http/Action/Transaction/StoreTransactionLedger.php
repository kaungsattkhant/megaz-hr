<?
namespace App\Http\Action\Transaction;

use App\Models\Transaction;

class StoreTransactionLedger
{
    public function storeLedger($data){
        dd('abc');
        return Transaction::create($data);
    }
}