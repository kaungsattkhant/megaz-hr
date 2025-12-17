<?php

namespace App\Repositories\Loan;

interface LoanRepositoryInterface
{
    public function storeLoanCreditorAccount(array $data);

    public function getLoanCreditorAccount($request);

    public function getInterestOnLoanCreditorAccount($request);

    public function storeLoan(array $data);

    public function createLoanTransaction($loan, $debitAccount, $creditAccount);

    public function getLoans($request); 

    public function getLoanByAccountId($loanAccountId);
}