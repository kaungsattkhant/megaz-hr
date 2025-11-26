<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanCreditor extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_account_id',
        'interest_on_loan_account_id',
        'loan_creditor_account_id',
        'interest_on_loan_creditor_account_id',
        'name',
        'address',
    ];

    public function loanAccount()
    {
        return $this->belongsTo(Account::class, 'loan_account_id', 'id');
    }

    public function interestOnLoanAccount()
    {
        return $this->belongsTo(Account::class, 'interest_on_loan_account_id', 'id');
    }

    public function loanCreditorAccount()
    {
        return $this->belongsTo(Account::class, 'loan_creditor_account_id', 'id');
    }

    public function interestOnLoanCreditorAccount()
    {
        return $this->belongsTo(Account::class, 'interest_on_loan_creditor_account_id', 'id');
    }
}
