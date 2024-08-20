<?php

use App\Http\Controllers\API\FinancialReportController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {
    Route::controller(FinancialReportController::class)->group(function () {
        Route::get('cash_flow_statement','CashFlowStatement');
        Route::get('balance_sheet','BalanceSheet');
    });
});