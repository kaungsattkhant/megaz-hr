<?php

namespace App\Repositories\CustomerDepositReport;

interface CustomerDepositReportRepositoryInterface
{
    public function getCustomerDeposit(string $startDate, string $endDate);
}
