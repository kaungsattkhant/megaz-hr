<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\LoanController;
use App\Http\Controllers\API\CashbookController;
use App\Http\Controllers\API\CreditorController;
use App\Http\Controllers\API\AccountPayableController;
use App\Http\Controllers\API\AdvanceController;
use App\Http\Controllers\API\FinancialReportController;
use App\Http\Controllers\API\SalesLedgerReportController;
use App\Http\Controllers\API\CustomerDepositReportController;
use App\Http\Controllers\API\DepositReceivableReportController;
use App\Http\Controllers\API\BudgetAccountController;

Route::middleware('auth:api')->group(function () {
    Route::controller(FinancialReportController::class)->group(function () {
        Route::get('cash_flow_statement', 'CashFlowStatement');
        Route::get('indirect_cash_flow_statement', 'IndirectCashFlowStatement');
        Route::get('balance_sheet', 'BalanceSheet');
        Route::get('trial_balance', 'TrialBalance');
        Route::get('inventory_schedule', 'getInventorySchedule');
        // Route::get('profit_and_loss', 'getProfitAndLoss');
        Route::get('working_capital', 'getWorkingCapital');
    });
    Route::controller(AccountPayableController::class)->group(function () {
        Route::get('get_account_payable_balance','getAccountPayableBalance');
        Route::get('account_payable_report','accountPayableReport');
    });
    Route::controller(CreditorController::class)->group(function () {
        Route::get('get_creditor_balance','getCreditorBalance');
    });
    Route::controller(CashbookController::class)->group(function () {
        Route::get('get_cashbook_closing_history','getCashbookClosingHistory');
    });

    Route::controller(BudgetAccountController::class)->group(function () {
        Route::get('budget_priorities','getBudgetPriorities');
        Route::get('budget_accounts','index');
        Route::post('budget_accounts','create');
        Route::post('budget_accounts/{id}','edit');
        Route::post('budget_accounts/{id}/confirm','confirmBudgetAccount');
    });
    // Route::controller(AdvanceController::class)->group(function(){
    //     Route::post('advances','create');
    // });
    Route::prefix('advances')->controller(AdvanceController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/', 'create');
        Route::get('advance_payment_detail/{advance_id}', 'getAdvancePaymentDetailByAdvance');
        Route::post('/create_advance_payments', 'createAdvancePayment');
    });
});

Route::controller(FinancialReportController::class)->group(function () {
    Route::get('profit_and_loss', 'getProfitAndLoss');
});

Route::controller(SalesLedgerReportController::class)->group(function () {
    Route::get('get_sale_ledgers','getSaleLedgerReport');
});

Route::controller(LoanController::class)->group(function () {
    Route::post('loan-creditor-accounts','storeLoanCreditorAccount');
    Route::get('loan-creditor-accounts','getLoanCreditorAccount');
    Route::get('interest-on-loan-creditor-accounts','getInterestOnLoanCreditorAccount');
    Route::post('loans','storeLoan');
    Route::get('loans','getLoans');
    Route::get('loans/{loanAccountId}','getLoanByAccountId');
});
Route::controller(CustomerDepositReportController::class)->group(function () {
    Route::get('get_customer_deposits','getCustomerDepositReport');
});
Route::controller(DepositReceivableReportController::class)->group(function () {
    Route::get('get_deposit_receivable_balance','getDepositReceivalbeBalance');
});
