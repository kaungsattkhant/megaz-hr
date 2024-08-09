<?php

namespace App\Repositories\StaffAdvance;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Account;
use App\Models\StaffAdvance;
use App\Models\StaffBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffAdvanceRepository implements StaffAdvanceRepositoryInterface
{
    public function createStaffAdvance(Request $reqeust)
    {
        DB::beginTransaction();
        try{
            $currentYear = date('Y');
            $currentMonth = date('m');
            dd($currentYear,$currentMonth);
            $data = $reqeust->all();
            $data['created_by'] = UserData()->id;
            $staffAdvance = StaffAdvance::create($data);
            $transaction = (new StoreTransactionLedger())->createTransaction([
                'date' => now(),
                'created_by' => UserData()->id,
                'description' => "staff",
                'transactionable_id' => $staffAdvance->id,
                'transactionable_type' => 'staff_advance',
                'is_confirmed' => 1,
            ]);
            $advanceAccount = Account::where('account_code','2-1053')->first();

            if($data['type']=='addition')
            {
                $creditLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $data['cash_account_id'],
                    'action' => 'credit',
                    'is_cashier_confirmed' => 0
                ]);

                $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $advanceAccount->id,
                    'action' => 'debit',
                    'is_cashier_confirmed' => 0
                ]);
            }else{
                $creditLedger = (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $data['cash_account_id'],
                    'action' => 'debit',
                    'is_cashier_confirmed' => 0
                ]);

                $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                    'value' => $data['amount'],
                    'transaction_id' => $transaction->id,
                    'account_id' => $advanceAccount->id,
                    'action' => 'credit',
                    'is_cashier_confirmed' => 0
                ]);
            }
            $staffBalance = StaffBalance::where('staff_id', $staffId)
                            ->where('year', $year)
                            ->where('month', $month)
                            ->first();

            if($staffBalance)
            {
                $staffBalance->closing = $staffBalance->opening + ($totalAdditional - $total);
            }


            DB::commit();
        }catch(\Exception $e)
        {
            DB::rollback();
            ResponseMessage($e->getMessage(),422);
            throw $e;
        }
    }
}
