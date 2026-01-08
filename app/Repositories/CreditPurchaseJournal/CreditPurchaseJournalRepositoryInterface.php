<?php

namespace App\Repositories\CreditPurchaseJournal;

interface CreditPurchaseJournalRepositoryInterface
{
    public function getCreditPurchaseJournal(string $startDate, string $endDate);
}
