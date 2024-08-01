<?php
namespace App\Http\Action\Transaction;

use App\Models\Ledger;
use App\Models\Transaction;

class StoreTransactionLedger
{
    public function createTransaction($data)
    {
        if (!isset($data->id)) {
            $data['id'] = null;
        }
        $data['date'] = now();
        return Transaction::updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }

    public function storeLedger($data)
    {
        if (!isset($data->id)) {
            $data['id'] = null;
        }
        return Ledger::updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }
}
