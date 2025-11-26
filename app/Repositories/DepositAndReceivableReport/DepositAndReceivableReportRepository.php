<?php

namespace App\Repositories\DepositAndReceivableReport;

use Illuminate\Support\Facades\DB;

use App\Http\Action\Common\AccountFetcher;

class DepositAndReceivableReportRepository implements DepositAndReceivableReportRepositoryInterface
{
    public function getOtherReceivableBalance(string $startDate, string $endDate)
    {
        $account_codes = ['2-1055', '2-1056'];
        $results = $this->getBalance($account_codes, $startDate, $endDate);
        return $results;
    }

    public function getDepositPaidBalances(string $startDate, string $endDate)
    {
        $account_codes = [];
        $accounts = (new AccountFetcher())->getAccountsBySubAccount('2-4000');
        foreach($accounts as $account){
            array_push($account_codes, $account->account_code);
        }
        $results = $this->getBalance($account_codes, $startDate, $endDate);
        return $results;
    }

    public function getFivePercentTaxBalances(string $startDate, string $endDate)
    {
        $account_codes = ['2-1057'];
        $results = $this->getBalance($account_codes, $startDate, $endDate);
        return $results;
    }

    private function getBalance(array $account_codes, string $startDate, string $endDate)
    {
        $account_ids = [];
        foreach($account_codes as $account_code){
            $account = (new AccountFetcher())->getAccountByCode($account_code);
            if($account){
                array_push($account_ids, $account->id);
            }
        }
        $results = DB::table('accounts as a')
        ->leftJoin('ledgers as l', 'a.id', '=', 'l.account_id')
        ->selectRaw("
            a.name,
            CAST(
            IFNULL(SUM(CASE WHEN l.action = 'debit' AND l.created_at < ? THEN l.value
                            WHEN l.action = 'credit' AND l.created_at < ? THEN -l.value END), 0) AS DOUBLE) AS Opening_Balance,

            CAST(
            IFNULL(SUM(CASE WHEN l.action = 'debit' AND l.created_at >= ? AND l.created_at < ? THEN l.value END), 0) AS DOUBLE) AS Debit_Total,

            CAST(
            IFNULL(SUM(CASE WHEN l.action = 'credit' AND l.created_at >= ? AND l.created_at < ? THEN l.value END), 0) AS DOUBLE) AS Credit_Total,

            CAST(
            (
                IFNULL(SUM(CASE WHEN l.action = 'debit' AND l.created_at < ? THEN l.value
                                WHEN l.action = 'credit' AND l.created_at < ? THEN -l.value END), 0)
                +
                IFNULL(SUM(CASE WHEN l.action = 'debit' AND l.created_at >= ? AND l.created_at < ? THEN l.value END), 0)
                -
                IFNULL(SUM(CASE WHEN l.action = 'credit' AND l.created_at >= ? AND l.created_at < ? THEN l.value END), 0)
            ) AS DOUBLE) AS Closing_Balance
        ", [
            $startDate, $startDate,  // opening
            $startDate, $endDate,    // debits
            $startDate, $endDate,    // credits
            $startDate, $startDate,  // closing (opening part)
            $startDate, $endDate,    // closing (debits)
            $startDate, $endDate     // closing (credits)
        ])
        ->whereIn('a.id', $account_ids)
        ->groupBy('a.id', 'a.name')
        ->orderBy('a.name')
        ->get();

        return $results;
    }
}
