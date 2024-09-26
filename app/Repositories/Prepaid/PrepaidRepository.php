<?php

namespace App\Repositories\Prepaid;

use App\Http\Action\Transaction\StoreTransactionLedger;
use App\Models\Prepaid;
use App\Models\PrepaidBalance;
use App\Models\PrepaidPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrepaidRepository implements PrepaidRepositoryInterface
{
    public function createPrepaid(Request $request)
    {
        DB::beginTransaction();
        try {
            $currentYear = date('Y');
            $currentMonth = date('m');

            $data = $request->all();
            $data['date_time'] = "2024-07-13 07:30";
            $data['created_by'] = UserData()->id;
            $prepaid = Prepaid::create($data);

            $prepaidBalance = PrepaidBalance::create([
                'year' => $currentYear,
                'month' => $currentMonth,
                'opening_balance' => $data['prepaid_amount'],
                'cost' => 0,
                'monthly_cost' => 0,
                'closing_balance' => $data['prepaid_amount'],
                'prepaid_amount' => $data['prepaid_amount'],
                'prepaid_id' => $prepaid->id
            ]);

            $transaction = (new StoreTransactionLedger())->createTransaction([
                'date' => now(),
                'created_by' => UserData()->id,
                'description' => "prepaid",
                'transactionable_id' =>$prepaid->id,
                'transactionable_type' => 'prepaid',
                'is_confirmed' => 1,
            ]);

            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $data['prepaid_amount'],
                'transaction_id' => $transaction->id,
                'account_id' => $data['cash_account_id'],
                'action' => 'credit',
                'is_cashier_confirmed' => 0
            ]);

            $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                'value' => $data['prepaid_amount'],
                'transaction_id' => $transaction->id,
                'account_id' => $data['account_id'],
                'action' => 'debit',
                'is_cashier_confirmed' => 0
            ]);

            DB::commit();
            ResponseData($prepaid);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function addingPrepaid(Request $request)
    {
        DB::beginTransaction();
        try {
            $prepaid = Prepaid::find($request->prepaid_id);
            $prePaidPaymentValue = PrepaidPayment::where('prepaid_id',$prepaid->id)->sum('amount') ?? 0 ;
            $validationValue = $prePaidPaymentValue + $request->amount + $prepaid->prepaid_amount;
            if($prepaid->total_amount < $validationValue)
            {
                ResponseMessage('Invalid Data',422);
            }

            $prePaidPayment = PrepaidPayment::create([
                'date_time' => CurrentTime(),
                'amount' => $request->amount,
                'cash_account_id' => $request->cash_account_id,
                'prepaid_id' => $request->prepaid_id
            ]);

            $transaction = (new StoreTransactionLedger())->createTransaction([
                'date' => now(),
                'created_by' => UserData()->id,
                'description' => "prepaid",
                'transactionable_id' =>$prepaid->id,
                'transactionable_type' => 'prepaid',
                'is_confirmed' => 1,
            ]);

            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $request->amount,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'action' => 'credit',
                'is_cashier_confirmed' => 0
            ]);

            $debitLedger =  (new StoreTransactionLedger())->storeLedger([
                'value' => $request->amount,
                'transaction_id' => $transaction->id,
                'account_id' => $prepaid->account_id,
                'action' => 'debit',
                'is_cashier_confirmed' => 0
            ]);

            DB::commit();
            ResponseData($prePaidPayment);
        } catch (\Exception $e) {
            DB::rollBack();
            ResponseMessage($e->getMessage(), 422);
            throw $e;
        }
    }

    public function prepaidBalanceList($request)
    {
        $currentYear = date('Y');
        $month = $request->input('month', date('m'));

        $prepaids = PrepaidBalance::where('month', $month)
            ->where('year', $currentYear)
            ->with(['prepaid' => function ($query) use ($month, $currentYear) {
                $query->withSum(['prepaidPayments as payment' => function ($query) use ($month, $currentYear) {
                    $query->whereYear('date_time', $currentYear)
                          ->whereMonth('date_time', $month);
                }], 'amount');
            }])
            ->orderBy('created_at','desc')
            ->paginate(config('common.list_count'));

        ResponseData($prepaids);
    }
}
