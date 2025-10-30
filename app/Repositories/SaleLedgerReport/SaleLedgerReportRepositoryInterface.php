<?php

namespace App\Repositories\SaleLedgerReport;

interface SaleLedgerReportRepositoryInterface
{
    public function getKtvSaleLedgerReport(string $startDate, string $endDate);

    public function getRestaurantSaleLedgerReport(string $startDate, string $endDate);
}
