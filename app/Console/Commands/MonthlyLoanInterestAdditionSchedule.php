<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Account;
use App\Models\LoanCreditor;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositories\Loan\LoanRepository;
use App\Http\Action\Transaction\StoreTransactionLedger;

class MonthlyLoanInterestAdditionSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:monthly-loan-interest-addition-schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        DB::beginTransaction();

        try {
            $loans = Loan::where('type', 'addition')->get();
            foreach ($loans as $loan) {
                $nextInterestDate = Carbon::parse($loan->date_time)->addMonth();

                if (Carbon::now()->startOfDay()->eq($nextInterestDate->startOfDay())) {
                    $interestAmount = ($loan->amount * $loan->interest_rate) / 100;
                    $interestAdditionLoan = Loan::firstOrCreate(
                        [
                            'date_time' => $nextInterestDate,
                            'type' => 'interest addition',
                            'account_id' => $loan->account_id,
                        ],
                        [
                            'date_time' => $nextInterestDate,
                            'type' => 'interest addition',
                            'category' => null,
                            'account_id' => $loan->account_id,
                            'main_account_id' => $loan->main_account_id,
                            'cash_account_id' => null,
                            'amount' => $interestAmount,
                            'interest_rate' => $loan->interest_rate,
                            'created_by' => $loan->created_by,
                        ]
                    );
                }
                $loanCreditor = LoanCreditor::where('loan_creditor_account_id', $loan->account_id)->first();
                if ($loanCreditor) {
                    $interestOnLoanAccount = Account::find($loanCreditor->interest_on_loan_account_id); //debit
                    $interestOnLoanCreditorAccount = Account::find($loanCreditor->interest_on_loan_creditor_account_id);
                    $transaction = (new StoreTransactionLedger())->createTransaction([
                        'date' => now(),
                        'created_by' => $interestAdditionLoan->created_by,
                        'description' => $interestAdditionLoan->type,
                        'transactionable_id' => $interestAdditionLoan->id,
                        'is_confirmed' => 1,
                        'transactionable_type' => 'loan',
                    ]);
                    #debit
                    if ($interestOnLoanAccount) {
                        $debit = (new Account())->accountByCode($interestOnLoanAccount->account_code);
                        if ($debit) {
                            (new StoreTransactionLedger())->storeLedger([
                                'value' => $interestAdditionLoan->amount,
                                'transaction_id' => $transaction->id,
                                'account_id' => $debit->id,
                                'action' => 'debit',
                            ]);
                        } else {
                            ResponseMessage('Account is Invalid', 419);
                        }
                    }
                    #credit
                    if ($interestOnLoanCreditorAccount) {
                        $credit = (new Account())->accountByCode($interestOnLoanCreditorAccount->account_code);
                        if ($credit) {
                            (new StoreTransactionLedger())->storeLedger([
                                'value' => $interestAdditionLoan->amount,
                                'transaction_id' => $transaction->id,
                                'account_id' =>  $credit->id,
                                'action' => 'credit',
                            ]);
                        } else {
                            ResponseMessage('Account is Invalid', 419);
                        }
                    }
                }
            }
            DB::commit();
            $this->info('Monthly interest added successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Failed to add monthly interest: ' . $e->getMessage());
        }
    }
}
