<?php

namespace App\Http\Action\Transaction;

use App\Models\Ledger;
use App\Models\Transaction;

class StoreTransactionLedger
{
    public function createTransaction($data): Transaction
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

    public function storeLedger($data, $transactionId = null, $isCashierConfirmed = false): Ledger
    {
        if (!isset($data->id)) {
            $data['id'] = null;
        }
        if (!isset($data['transaction_id'])) {
            if (!$transactionId)
                ResponseMessage('Transaction id must be present');
            $data['transaction_id'] = $transactionId;
        }
        // $data['is_cashier_confirmed'] = ($isCashierConfirmed) ? 1 : 0;

        return Ledger::updateOrCreate(
            ['id' => $data['id']],
            $data
        );
    }
}
