<?php

namespace App\Repositories\AccountPayable;

use App\Models\Ledger;
use App\Models\Account;
use App\Models\AccountPayable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Action\Transaction\StoreTransactionLedger;

class AccountPayableRepository implements AccountPayableInterface
{
    public function list($request)
    {
        // $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
        //     ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
        //     ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
        //     ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
        //     ->where('sub_accounts.head_account_id', config('common.liabilities'))
        //     ->where('ledgers.personable_type', 'supplier')
        //     ->select(
        //         'ledgers.personable_id as supplier_id',
        //         'suppliers.account_id',
        //         'suppliers.name as supplier_name',
        //         DB::raw('SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END) as debit_amount'),
        //         DB::raw('SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) as credit_amount'),
        //         DB::raw('(SUM(CASE WHEN ledgers.action = "credit" THEN ledgers.value ELSE 0 END) - SUM(CASE WHEN ledgers.action = "debit" THEN ledgers.value ELSE 0 END)) as total_credit_amount')
        //     )
        //     ->groupBy('ledgers.personable_id')
        //     ->get();
        $ledger = DB::table('account_payables')
            ->join('suppliers', 'account_payables.supplier_id', '=', 'suppliers.id')

            ->select(
                'supplier_id',
                'suppliers.name as supplier_name',
                'suppliers.account_id',
                DB::raw('SUM(CASE WHEN type = "addition" THEN amount ELSE 0 END) as credit_amount'),
                DB::raw('SUM(CASE WHEN type = "settlement" THEN amount ELSE 0 END) as debit_amount'),
                DB::raw('SUM(CASE WHEN type = "addition" THEN amount ELSE 0 END) - SUM(CASE WHEN type = "settlement" THEN amount ELSE 0 END) as total_credit_amount')
            )
            ->groupBy('supplier_id')
            ->get();
        return $ledger;

    }
    public function createPayableAccount($request)
    {
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
            return $account;
        }
        ResponseMessage('Something is wrong', 419);
    }

    public function getPayableAccount()
    {
        return Account::orderBy('accounts.id', 'asc')
            ->whereHas('sub_account', function ($q) {
                $q->where('account_code', config('common.payable_account_code'));
            })
            // whereHas('sub_account.head_account', function ($q) {
            //     $q->where('head_account_id', config('common.liabilities'));
            // })
            ->get();
    }

    public function createPayableTransaction($request)
    {
        DB::beginTransaction();
        try {
            // $accountPayable = AccountPayable::create([
            //     'type' => 'addition',
            //     'date_time' => now(),
            //     'amount' => $ap_amount,
            //     'supplier_id' => $supplier_id,
            //     'account_id' => $supplier_account_id,
            //     'cash_account_id' => $cash_account_id,
            //     'created_by' => UserData()->id,
            // ]);
            $data = $request->all();
            $data['created_by'] = UserData()->id;
            $data['is_confirmed'] = 1;
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
                'account_id' => $request->account_id,
                'personable_id' => $request->supplier_id,
                'personable_type' => 'supplier',
                'action' => 'debit',
            ]);

            $accountPayable = AccountPayable::create([
                'type' => 'settlement',
                'date_time' => now(),
                'amount' => $request->value,
                'supplier_id' => $request->supplier_id,
                'account_id' => $request->account_id,
                'cash_account_id' => $request->cash_account_id,
                'created_by' => UserData()->id,
            ]);
            DB::commit();
            return $transaction;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function listOfAccountPayableTransaction($request)
    {

        $ledger = DB::table('account_payables')
            ->join('suppliers', 'account_payables.supplier_id', '=', 'suppliers.id')
            ->join('accounts', 'account_payables.account_id', '=', 'accounts.id')
            ->select(
                'accounts.name as account_name',
                'accounts.account_code as account_code',
                'suppliers.name as supplier_name',
                'suppliers.account_id',
                'account_payables.amount as amount',
                DB::raw("DATE_FORMAT(account_payables.created_at, '%M %d %Y %H:%i') as date"),
            )
            ->where('account_payables.type','settlement')
            ->get();
        // $ledger = Ledger::join('accounts', 'ledgers.account_id', '=', 'accounts.id')
        //     ->join('transactions', 'ledgers.transaction_id', '=', 'transactions.id')
        //     ->join('sub_accounts', 'accounts.sub_account_id', '=', 'sub_accounts.id')
        //     ->join('suppliers', 'ledgers.personable_id', '=', 'suppliers.id')
        //     ->where('sub_accounts.head_account_id', config('common.liabilities'))
        //     ->where('ledgers.personable_type', 'supplier')
        //     ->where('ledgers.personable_id', $request->supplier_id)
        //     ->whereNull('transactions.transactionable_id')
        //     ->select(
        //         // 'ledgers.created_at as date',
        //         DB::raw("DATE_FORMAT(ledgers.created_at, '%M %d %Y %H:%i') as date"),
        //         'accounts.name as account_name',
        //         'ledgers.personable_id as supplier_id',
        //         'suppliers.account_id',
        //         'suppliers.name as supplier_name',
        //         'ledgers.value as amount'
        //     )
        //     ->with('account')
        //     // ->groupBy('ledgers.personable_id')
        //     ->get();
        return $ledger;
    }

    public function getAccountPayableBalance($request)
    {
        $account_code = ['4-4000'];
        $payableAccountList = Account::orderBy('accounts.id', 'asc')
            ->whereHas('sub_account', function ($q) use ($account_code) {
                $q->whereIn('account_code', $account_code);
            })
            ->get();
        $year = isset($requset->date) || $request->date ? Carbon::parse($request->date)->format('Y') : Carbon::now()->year;
        $currentMonth = isset($requset->date) || $request->date ? Carbon::parse($request->date)->format('n') : Carbon::now()->month;

        $payableAccounts = Account::orderBy('accounts.id', 'asc')
            ->whereHas('sub_account', function ($q) use ($account_code) {
                $q->whereIn('account_code', $account_code);
            })
            ->leftJoin('account_payables', function ($join) use ($year, $currentMonth) {
                $join->on('accounts.id', '=', 'account_payables.account_id')
                    ->whereYear('account_payables.date_time', $year)
                    ->whereMonth('account_payables.date_time', '<=', $currentMonth);
            })
            ->leftJoin('suppliers', 'account_payables.supplier_id', '=', 'suppliers.id')
            ->select(
                'accounts.id as account_id',
                'accounts.name as account_name',

                // Total additions across all months
                DB::raw("COALESCE(SUM(CASE WHEN account_payables.type = 'addition' THEN account_payables.amount ELSE 0 END), 0) as total_addition"),

                // Total settlements across all months
                DB::raw("COALESCE(SUM(CASE WHEN account_payables.type = 'settlement' THEN account_payables.amount ELSE 0 END), 0) as total_settlement"),

                // Net amount (total_addition - total_settlement)
                DB::raw("COALESCE(SUM(CASE WHEN account_payables.type = 'addition' THEN account_payables.amount ELSE 0 END) - 
                  SUM(CASE WHEN account_payables.type = 'settlement' THEN account_payables.amount ELSE 0 END), 0) as total_amount")
            )
            ->groupBy('accounts.id', 'accounts.name')
            ->get()
            ->map(function ($balance) use ($year, $currentMonth) {
                // Calculate opening balance (total for previous months only)
                $balance->opening_balance = AccountPayable::where('account_id', $balance->account_id)
                    ->whereYear('date_time', $year)
                    ->whereMonth('date_time', '<', $currentMonth)
                    ->select(
                        DB::raw("SUM(CASE WHEN type = 'addition' THEN amount ELSE 0 END) - 
                          SUM(CASE WHEN type = 'settlement' THEN amount ELSE 0 END) as opening_balance")
                    )
                    ->value('opening_balance') ?? 0;

                // Calculate the current month total amount
                $currentMonthTotal = AccountPayable::where('account_id', $balance->account_id)
                    ->whereYear('date_time', $year)
                    ->whereMonth('date_time', $currentMonth)
                    ->select(
                        DB::raw("SUM(CASE WHEN type = 'addition' THEN amount ELSE 0 END) - 
                          SUM(CASE WHEN type = 'settlement' THEN amount ELSE 0 END) as current_month_amount")
                    )
                    ->value('current_month_amount') ?? 0;

                // Closing balance is opening balance plus the net current month amount
                $balance->closing_balance = $balance->opening_balance + $currentMonthTotal;

                return $balance;
            });
        $total_opening_balance = $total_closing_balance = $total_addition = $total_settlement = 0;
        foreach ($payableAccounts as $payableAccount) {
            $total_opening_balance += $payableAccount->opening_balance;
            $total_closing_balance += $payableAccount->closing_balance;
            $total_addition += $payableAccount->total_addition;
            $total_settlement += $payableAccount->total_settlement;
        }
        return [
            'payable_accounts' => $payableAccounts,
            'total_opening_balance' => $total_opening_balance,
            'total_closing_balance' => $total_closing_balance,
            'total_addition' => $total_addition,
            'total_settlement' => $total_settlement,
        ];
    }


    public function accountPayableReport($request)
    {

    }

}
