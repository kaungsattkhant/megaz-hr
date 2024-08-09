<?php

namespace App\Repositories\Journal;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Journal;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalRepository implements JournalRepositoryInterface
{

    public function listAllData(Request $request)
    {
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));

        $journalLedger = Transaction::where('transactionable_type', 'journal')
            ->whereMonth('date', '=', $month)
            ->whereYear('date', '=', $year)
            ->with('ledgers.account')
            ->get();

        ResponseData($journalLedger);
    }


    public function createData(Request $request)
    {
        DB::beginTransaction();
        try {
            $journal = Journal::create($request->all());
            $transaction = (new StoreTransactionLedger())->createTransaction([
                'date' => now(),
                'created_by' => UserData()->id,
                'description' => $request->particular,
                'transactionable_id' => $journal->id,
                'transactionable_type' => 'journal',
                'is_confirmed' => 0,
            ]);

            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $request->amount,
                'transaction_id' => $transaction->id,
                'account_id' => $request->credit_account_id,
                'action' => 'credit',
                'is_cashier_confirmed' => 0
            ]);

            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $request->amount,
                'transaction_id' => $transaction->id,
                'account_id' => $request->debit_account_id,
                'action' => 'debit',
                'is_cashier_confirmed' => 0
            ]);
            DB::commit();
            ResponseData($journal, 200);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            return false;
        }
    }
}
