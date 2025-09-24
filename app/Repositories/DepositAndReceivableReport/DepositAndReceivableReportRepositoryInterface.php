<?php

namespace App\Repositories\DepositAndReceivableReport;

interface DepositAndReceivableReportRepositoryInterface
{
    public function getOtherReceivableBalance(string $startDate, string $endDate);

    public function getDepositPaidBalances(string $startDate, string $endDate);

    public function getFivePercentTaxBalances(string $startDate, string $endDate);
}
