<?php

namespace App\Repositories\FinancialReport;

interface FinancialInterface
{
    public function CashFlowStatement($request);

    public function IndirectCashFlowStatement($request);

    public function BalanceSheet($request);

    public function TrialBalance($request);

    public function getProfitAndLoss($request);
}