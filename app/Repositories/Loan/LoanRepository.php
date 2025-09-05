<?php

namespace App\Repositories\Loan;

use App\Models\Loan;
use App\Models\Account;
use App\Models\LoanCreditor;
use Illuminate\Support\Facades\DB;
use App\Repositories\Loan\LoanRepositoryInterface;
use App\Http\Action\Transaction\StoreTransactionLedger;

class LoanRepository implements LoanRepositoryInterface
{
    public function storeLoanCreditorAccount($data)
    {
        DB::beginTransaction();
        try {
            //'loan_account_code'=>'4-1001', //subheading - Liabilities
            //'interest_on_loan_account_code'=>'6-8001' //subheading - Finance Cost
            $name = $data['name'];
            $loan_account_code = config('common.loan_account_code');
            $interest_on_loan_account_code = config('common.interest_on_loan_account_code');

            $parentLoanAccount = Account::where('account_code', $loan_account_code)->first();
            $parentInterestOnLoanAccount = Account::where('account_code', $interest_on_loan_account_code)->first();
            $loanAccountInfo = $this->generateNextAccountCode($loan_account_code);
            $interestAccountInfo = $this->generateNextAccountCode($interest_on_loan_account_code);
            $creditorLoanAccount = Account::create([
                'name' => "Loan - {$name}",
                'account_code' => $loanAccountInfo['account_code'],
                'sub_account_id' => $parentLoanAccount->sub_account_id,
                'account_id' => $parentLoanAccount->id,
                'link_account_id' => null,
                'type' => 'is_first',
                'is_active' => 1
            ]);
            $interestOnLoanCreditorAccount = Account::create([
                'name' => "Interest on Loan - {$name}",
                'account_code' => $interestAccountInfo['account_code'],
                'sub_account_id' => $parentInterestOnLoanAccount->sub_account_id,
                'account_id' => $parentInterestOnLoanAccount->id,
                'link_account_id' => null,
                'type' => 'is_first', // is_first or is_second ??
                'is_active' => 1
            ]);

            LoanCreditor::create([
                'loan_account_id' => $parentLoanAccount->id,
                'interest_on_loan_account_id' => $parentInterestOnLoanAccount->id,
                'loan_creditor_account_id' => $creditorLoanAccount->id,
                'interest_on_loan_creditor_account_id' => $interestOnLoanCreditorAccount->id,
                'name' => $name,
                'address' => $data['address'] ?? null,
            ]);
            DB::commit();
            return [
                'creditor_loan_account' => $creditorLoanAccount,
                'creditor_interest_on_loan_account' => $interestOnLoanCreditorAccount
            ];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    private function generateNextAccountCode($baseCode)
    {

        $latestAccount = Account::where('account_code', 'like', $baseCode . '-%')
            ->orderBy('account_code', 'desc')
            ->first();
        $counter = 1;
        if ($latestAccount) {
            $parts = explode('-', $latestAccount->account_code);
            $lastNumber = intval(end($parts));

            $counter = $lastNumber + 1;
        }
        $accountCode = $baseCode . "-" . str_pad($counter, 3, '0', STR_PAD_LEFT);
        return [
            'counter' => $counter,
            'account_code' => $accountCode
        ];
    }

    public function getLoanCreditorAccount($request)
    {
        $loan_account_code = config('common.loan_account_code');
        $parentLoanAccount = Account::where('account_code', $loan_account_code)->first();
        return Account::where('account_code', 'like', $parentLoanAccount->account_code . '-%')
            ->where('account_id', $parentLoanAccount->id)
            ->where('sub_account_id', $parentLoanAccount->sub_account_id)
            ->orderBy('id', 'desc')->get();
    }

    public function  getInterestOnLoanCreditorAccount($request)
    {
        $interest_on_loan_account_code = config('common.interest_on_loan_account_code');
        $parentInterestOnLoanAccount = Account::where('account_code', $interest_on_loan_account_code)->first();
        return Account::where('account_code', 'like', $parentInterestOnLoanAccount->account_code . '-%')
            ->where('account_id', $parentInterestOnLoanAccount->id)
            ->where('sub_account_id', $parentInterestOnLoanAccount->sub_account_id)
            ->orderBy('id', 'desc')->get();
    }

    public function storeLoan(array $data)
    {
        DB::beginTransaction();
        try {
            $result = null;
            if ($data['type'] === "addition") {
                $result = $this->storeLoanAddition($data);
            } else if ($data['type'] === "settlement" || $data['type'] === "interest settlement") {
                $result = $this->storeLoanSettlement($data);
            } else {
                ResponseMessage('Invalid loan type', 419);
            }
            DB::commit();
            return  $result;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function storeLoanAddition(array $data)
    {
        DB::beginTransaction();
        try {
            $loanAccount = Account::where('id', $data['account_id'])->where('account_code', $data['account_code'])->first();
            $cashAccount = Account::where('id', $data['cash_account_id'])->first();
            if (!$loanAccount) {
                ResponseMessage('Account is Invalid', 419);
            }
            if (!$cashAccount) {
                ResponseMessage('Cash Account is Invalid', 419);
            }
            $loan = Loan::create([
                'date_time' => now(),
                'type' => $data['type'],
                'category' => $data['category'] ?? null,
                'account_id' => $data['account_id'],
                'main_account_id' => $loanAccount->account_id,
                'cash_account_id' => $data['cash_account_id'] ?? null,
                'amount' => $data['amount'],
                'interest_rate' => $data['interest_rate'] ?? null,
                'created_by' => UserData()->id,
            ]);
            // For loan addition:
            // Debit: Cash Account
            // Credit: Loan Account
            $this->createLoanTransaction($loan, $cashAccount, $loanAccount);
            DB::commit();
            return $loan;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function storeLoanSettlement(array $data)
    {
        $loanAccount = Account::where('id', $data['account_id'])->where('account_code', $data['account_code'])->first();
        $cashAccount = Account::where('id', $data['cash_account_id'])->first();

        if (!$loanAccount) {
            ResponseMessage('Account is Invalid', 419);
        }
        if (!$cashAccount) {
            ResponseMessage('Cash Account is Invalid', 419);
        }
        $loan = Loan::create([
            'date_time' => now(),
            'type' => $data['type'],
            'category' => $data['category'] ?? null,
            'account_id' => $data['account_id'],
            'main_account_id' => $loanAccount->account_id,
            'cash_account_id' => $data['cash_account_id'] ?? null,
            'amount' => $data['amount'],
            'interest_rate' => $data['interest_rate'] ?? null,
            'created_by' => UserData()->id,
        ]);
        $interestAccount = null;
        if ($data['category'] === "interest") {
            $loanCreditor = LoanCreditor::where('loan_creditor_account_id', $loanAccount->id)->first();
            if ($loanCreditor) {
                $interestAccount = Account::find($loanCreditor->interest_on_loan_creditor_account_id);
            }

            if (!$interestAccount) {
                ResponseMessage('Interest Account not found for this loan', 419);
            }
            // For interest settlement:
            // Debit: Interest Account
            // Credit: Cash Account
            $this->createLoanTransaction($loan, $interestAccount, $cashAccount);
        } else {
            // For loan settlement:
            // Debit: Loan Account
            // Credit: Cash Account
            $this->createLoanTransaction($loan, $loanAccount, $cashAccount);
        }
        DB::commit();
        return $loan;
    }

    public function createLoanTransaction($loan, $debitAccount, $creditAccount)
    {
        $transaction = (new StoreTransactionLedger())->createTransaction([
            'date' => now(),
            'created_by' => UserData()->id,
            'description' => $loan->type,
            'transactionable_id' => $loan->id,
            'is_confirmed' => 1,
            'transactionable_type' => 'loan',
        ]);
        #debit
        if ($debitAccount) {
            $debit = (new Account())->accountByCode($debitAccount->account_code);
            if ($debit) {
                (new StoreTransactionLedger())->storeLedger([
                    'value' => $loan->amount,
                    'transaction_id' => $transaction->id,
                    'account_id' => $debit->id,
                    'action' => 'debit',
                ]);
            } else {
                ResponseMessage('Account is Invalid', 419);
            }
        }
        #credit
        if ($creditAccount) {
            $credit = (new Account())->accountByCode($creditAccount->account_code);
            if ($credit) {
                (new StoreTransactionLedger())->storeLedger([
                    'value' => $loan->amount,
                    'transaction_id' => $transaction->id,
                    'account_id' =>  $credit->id,
                    'action' => 'credit',
                ]);
            } else {
                ResponseMessage('Account is Invalid', 419);
            }
        }
        return $transaction;
    }

    public function getLoans($request)
    {
        $loanSummary = DB::table('loans')
            ->join('accounts', 'loans.account_id', '=', 'accounts.id')
            ->select(
                'loans.id',
                'loans.account_id',
                'accounts.name as account_name',
                'accounts.account_code',
                'loans.interest_rate',
                // Calculate loan additions
                DB::raw('CAST(SUM(CASE WHEN loans.type = "addition" THEN loans.amount ELSE 0 END) AS DECIMAL(15,2)) as total_loan_additions'),
                DB::raw('CAST(SUM(CASE WHEN loans.type = "interest addition" THEN loans.amount ELSE 0 END) AS DECIMAL(15,2)) as total_interest_additions'),
                // Calculate loan settlements
                //DB::raw('CAST(SUM(CASE WHEN loans.type = "settlement" AND loans.category = "loan" THEN loans.amount ELSE 0 END) AS DECIMAL(15,2)) as total_loan_settlements'),
                //DB::raw('CAST(SUM(CASE WHEN loans.type = "interest settlement" THEN loans.amount ELSE 0 END) AS DECIMAL(15,2)) as total_interest_settlements'),
                //DB::raw('CAST(SUM(CASE WHEN loans.type = "addition" THEN (loans.amount * loans.interest_rate / 100) ELSE 0 END) AS DECIMAL(15,2)) as total_interest_amount'),

                DB::raw('CAST((
                    SUM(CASE WHEN loans.type = "addition" THEN loans.amount ELSE 0 END) +
                    SUM(CASE WHEN loans.type = "interest addition" THEN loans.amount ELSE 0 END) -
                    SUM(CASE WHEN loans.type = "settlement" AND loans.category = "loan" THEN loans.amount ELSE 0 END) -
                    SUM(CASE WHEN loans.type = "interest settlement" THEN loans.amount ELSE 0 END)
                ) AS DECIMAL(15,2)) as total_balance')
            )
            ->groupBy('loans.account_id', 'accounts.name', 'accounts.account_code')
            ->paginate(config('common.list_count'));
        $loanSummary->transform(function ($item) {
            $item->total_loan_additions = (float)$item->total_loan_additions;
            $item->total_interest_additions = (float)$item->total_interest_additions;
            // $item->total_loan_settlements = (float)$item->total_loan_settlements;
            // $item->total_interest_settlements = (float)$item->total_interest_settlements;
            $item->interest_rate = (float)$item->interest_rate;
            // $item->total_interest_amount = (float)$item->total_interest_amount;
            $item->total_balance = (float)$item->total_balance;
            return $item;
        });

        return $loanSummary;
    }

    public function getLoanByAccountId($loanAccountId)
    {
        return  DB::table('loans')
            ->join('accounts', 'loans.account_id', '=', 'accounts.id')
            ->select(
                'loans.id',
                'loans.date_time AS date',
                'loans.account_id',
                'accounts.name as account_name',
                'accounts.account_code',
                'loans.type',
                'loans.category',
                'loans.amount',
                DB::raw('SUM(CASE 
        WHEN loans.type = "addition" THEN loans.amount 
        WHEN loans.type = "interest addition" THEN loans.amount
        WHEN loans.type = "settlement" AND loans.category = "loan" THEN -loans.amount
        WHEN loans.type = "interest settlement" AND loans.category = "interest" THEN -loans.amount
        ELSE 0 
    END) OVER (PARTITION BY loans.account_id ORDER BY loans.date_time, loans.id) AS balance')
            )
            ->where('loans.account_id', $loanAccountId)
            ->orderBy('loans.id', 'desc')
            ->paginate(config('common.list_count'));
    }
}
