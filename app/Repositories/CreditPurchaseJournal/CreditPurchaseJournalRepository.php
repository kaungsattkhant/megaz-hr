<?php

namespace App\Repositories\CreditPurchaseJournal;

use Illuminate\Support\Facades\DB;

use App\Http\Action\Common\AccountFetcher;

class CreditPurchaseJournalRepository implements CreditPurchaseJournalRepositoryInterface
{
    public function getCreditPurchaseJournal(string $startDate, string $endDate)
    {
        $directCostAccounts = (new AccountFetcher())->getAccountsBySubAccount('2-1020');
        $creditorACAccounts = (new AccountFetcher())->getAccountsBySubAccount('4-2000');
        $directCostAccountIds = [];
        $creditorACAccountIds = [];
        foreach($directCostAccounts as $directCostAccount){
            array_push($directCostAccountIds, $directCostAccount->id);
        }
        foreach($creditorACAccounts as $creditorACAccount){
            array_push($creditorACAccountIds, $creditorACAccount->id);
        }

        $data = [
            'direct_cost_accounts' => $this->getBalance($directCostAccountIds, $startDate, $endDate),
            'creditor_ac_accounts' => $this->getBalance($creditorACAccountIds, $startDate, $endDate)
        ];

        return $data;
    }

    private function getBalance(array $accountIds, string $startDate, string $endDate)
    {
        $results = DB::table('accounts as a')
        ->leftJoin('ledgers as l', 'a.id', '=', 'l.account_id')
        ->selectRaw("
            a.name,

            CAST(
            IFNULL(SUM(CASE WHEN l.action = 'debit' AND l.created_at >= ? AND l.created_at < ? THEN l.value END), 0) AS DOUBLE) AS Debit_Total,

            CAST(
            IFNULL(SUM(CASE WHEN l.action = 'credit' AND l.created_at >= ? AND l.created_at < ? THEN l.value END), 0) AS DOUBLE) AS Credit_Total

        ", [
            $startDate, $endDate,    // debits
            $startDate, $endDate,    // credits
        ])
        ->whereIn('a.id', $accountIds)
        ->groupBy('a.id', 'a.name')
        ->havingRaw('Debit_Total <> 0 OR Credit_Total <> 0') // 🔑 filter out zero totals
        ->orderBy('a.name')
        ->get();

        return $results;
    }
}
