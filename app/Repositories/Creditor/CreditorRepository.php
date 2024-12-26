<?php

namespace App\Repositories\Creditor;

use App\Models\Ledger;
use App\Models\Account;
use App\Models\CreditorBalance;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;

class CreditorRepository implements CreditorInterface
{

    public function list($request)
    {
        // config('common.creditor_account_code')
        $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
            ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
            ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->where('sub_accounts.account_code',config('common.creditor_account_code') )
            ->where('ledgers.personable_type', 'supplier')
            ->select(
                'ledgers.personable_id as supplier_id',
                'suppliers.creditor_account_id',
                'suppliers.name as supplier_name',
                DB::raw('SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as debit_amount'),
                DB::raw('SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as credit_amount'),
                DB::raw('(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) - SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END)) as total_credit_amount')
            )
            ->groupBy('ledgers.personable_id')
            ->get();
        return $ledger;
    }

    public function createCreditorAccount($request)
    {
        DB::beginTransaction();
        try {

            $latestAccount = Account::where('sub_account_id', $request->sub_account_id)
                ->orderByRaw("CAST(SUBSTRING_INDEX(account_code, '-', -1) AS UNSIGNED) DESC")
                ->first();
            // ->max('account_code');
            if ($latestAccount) {
                $latestAccountCodeNo = explode('-', $latestAccount->account_code);
                // dd($account_code_no[1]);
                $new_account_code = (int) $latestAccountCodeNo[1] + 1;
                $code = $latestAccountCodeNo[0] . '-' . $new_account_code;
                $account = Account::create([
                    'name' => $request->name,
                    'account_code' => $code,
                    'sub_account_id' => $request->sub_account_id,
                ]);
                DB::commit();
                return $account;
            }
            ResponseMessage('Something is wrong', 419);
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function getCreditorAccountList()
    {
        $creditorAccountCode = config('common.creditor_account_code');
        return Account::orderBy('accounts.id', 'asc')
            ->whereHas('sub_account', function ($q) use ($creditorAccountCode) {
                $q->where('account_code', $creditorAccountCode);
            })
            ->get();
    }

    public function listOfCreditorTransaction($request)
    {
        $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
            ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
            ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
            ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
            ->where('sub_accounts.account_code', config('common.creditor_account_code') )
            ->where('ledgers.personable_type', 'supplier')
            ->where('ledgers.personable_id', $request->supplier_id)
            ->whereNull('transactions.transactionable_id')
            ->select(
                // 'ledgers.created_at as date',
                DB::raw("DATE_FORMAT(ledgers.created_at, '%M %d %Y %H:%i') as date"),
                'accounts.name as account_name',
                'ledgers.personable_id as supplier_id',
                'suppliers.creditor_account_id',
                'suppliers.name as supplier_name',
                'ledgers.value as amount'
                // DB::raw('SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as debit_amount'),
                // DB::raw('SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as credit_amount'),
                // DB::raw('(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) - SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END)) as total_credit_amount')
            )
            ->with('account')
            // ->groupBy('ledgers.personable_id')
            ->get();
        return $ledger;
    }

    public function createCreditorTransaction($request)
    {
        DB::beginTransaction();
        try {
            $data = $request->all();
            $data['created_by'] = UserData()->id;
            $data['is_confirmed'] = 1;
            
            $creditorBalance=CreditorBalance::create([
                'type'=>'settlement',
                'date_time'=>now(),
                'amount'=>$request->value ,
                'supplier_id'=>$request->supplier_id,
                'account_id'=>$request->creditor_account_id,
                'cash_account_id' => $request->cash_account_id,
                'created_by'=>UserData()->id,
            ]);
            $transaction = (new StoreTransactionLedger())->createTransaction($data);
            $creditLedger = (new StoreTransactionLedger())->storeLedger([
                'date' => now(),
                'value' => $request->value,
                'transaction_id' => $transaction->id,
                'account_id' => $request->cash_account_id,
                'personable_id' => $request->supplier_id,
                'personable_type' => 'supplier',
                'action' => 'credit',
            ]);

            #debit
            $debitLedger = (new StoreTransactionLedger())->storeLedger([
                'value' => $request->value,
                'transaction_id' => $transaction->id,
                'account_id' => $request->creditor_account_id,
                'personable_id' => $request->supplier_id,
                'personable_type' => 'supplier',
                'action' => 'debit',
            ]);
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }
    public function  getCreditorBalance ( $request){
        $month = $request->input('month', date('m'));
        $year = $request->input('year', date('Y'));
        // $data = DB::table('creditor_balances')
        // ->join('accounts', 'creditor_balances.account_id', '=', 'accounts.id') // Ensure correct join relationship
        // ->select(
        //     'creditor_balances.account_id',
        //     'accounts.name as account_name',
        //     DB::raw('
        //         SUM(CASE 
        //             WHEN MONTH(creditor_balances.date_time) < ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' THEN 
        //                 CASE 
        //                     WHEN creditor_balances.type = "addition" THEN creditor_balances.amount 
        //                     WHEN creditor_balances.type = "settlement" THEN -creditor_balances.amount 
        //                     ELSE 0 
        //                 END 
        //             ELSE 0 
        //         END) AS opening_balance'),
        //     DB::raw('SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "settlement" THEN creditor_balances.amount ELSE 0 END) AS settlement_amount'),
        //     DB::raw('SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "addition" THEN creditor_balances.amount ELSE 0 END) AS addition_amount'),
        //     DB::raw('
        //         (SUM(CASE 
        //             WHEN MONTH(creditor_balances.date_time) < ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' THEN 
        //                 CASE 
        //                     WHEN creditor_balances.type = "addition" THEN creditor_balances.amount 
        //                     WHEN creditor_balances.type = "settlement" THEN -creditor_balances.amount 
        //                     ELSE 0 
        //                 END 
        //             ELSE 0 
        //         END)
        //         + SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "addition" THEN creditor_balances.amount ELSE 0 END)
        //         - SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "settlement" THEN creditor_balances.amount ELSE 0 END)
        //     ) AS closing_balance')
        // )
        // ->groupBy('creditor_balances.account_id', 'accounts.name')
        // ->get();

        $data = DB::table('accounts')
    ->join('sub_accounts','accounts.sub_account_id','sub_accounts.id')
    ->leftJoin('creditor_balances', 'accounts.id', '=', 'creditor_balances.account_id')
    ->select(
        'accounts.id as account_id',
        'accounts.name as account_name',
        DB::raw('
            COALESCE(SUM(CASE 
                WHEN MONTH(creditor_balances.date_time) < ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' THEN 
                    CASE 
                        WHEN creditor_balances.type = "addition" THEN creditor_balances.amount 
                        WHEN creditor_balances.type = "settlement" THEN -creditor_balances.amount 
                        ELSE 0 
                    END 
                ELSE 0 
            END), 0) AS opening_balance'),
        DB::raw('COALESCE(SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "settlement" THEN creditor_balances.amount ELSE 0 END), 0) AS settlement_amount'),
        DB::raw('COALESCE(SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "addition" THEN creditor_balances.amount ELSE 0 END), 0) AS addition_amount'),
        DB::raw('
            COALESCE((
                SUM(CASE 
                    WHEN MONTH(creditor_balances.date_time) < ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' THEN 
                        CASE 
                            WHEN creditor_balances.type = "addition" THEN creditor_balances.amount 
                            WHEN creditor_balances.type = "settlement" THEN -creditor_balances.amount 
                            ELSE 0 
                        END 
                    ELSE 0 
                END)
                + SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "addition" THEN creditor_balances.amount ELSE 0 END)
                - SUM(CASE WHEN MONTH(creditor_balances.date_time) = ' . $month . ' AND YEAR(creditor_balances.date_time) = ' . $year . ' AND creditor_balances.type = "settlement" THEN creditor_balances.amount ELSE 0 END)
            ), 0) AS closing_balance')
    )
    ->where('sub_accounts.account_code','4-2000')
    ->groupBy('accounts.id', 'accounts.name')
    ->get();
        ResponseData($data);
    }
}