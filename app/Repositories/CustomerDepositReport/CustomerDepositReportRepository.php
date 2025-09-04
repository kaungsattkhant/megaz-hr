<?php

namespace App\Repositories\CustomerDepositReport;

use Illuminate\Support\Facades\DB;

class CustomerDepositReportRepository implements CustomerDepositReportRepositoryInterface
{
    public function getCustomerDeposit(string $startDate, string $endDate)
    {
        $results = DB::table('customers as c')
        ->leftJoin('customer_deposits as cd', 'c.id', '=', 'cd.customer_id')
        ->selectRaw("
            c.name,

            CAST(
            IFNULL(SUM(CASE WHEN cd.type = 'deposit' AND cd.date_time < ? THEN cd.amount
                            WHEN cd.type = 'withdrawal' AND cd.date_time < ? THEN -cd.amount END), 0) AS DOUBLE) as Opening_Balance,

            CAST(
            IFNULL(SUM(CASE WHEN cd.type = 'deposit' AND cd.date_time >= ? AND cd.date_time < ? THEN cd.amount END), 0) AS DOUBLE) as Deposit_Total,

            CAST(
            IFNULL(SUM(CASE WHEN cd.type = 'withdrawal' AND cd.date_time >= ? AND cd.date_time < ? THEN cd.amount END), 0) AS DOUBLE) as Withdrawal_Total,

            CAST(
            (
                IFNULL(SUM(CASE WHEN cd.type = 'deposit' AND cd.date_time < ? THEN cd.amount
                                WHEN cd.type = 'withdrawal' AND cd.date_time < ? THEN -cd.amount END), 0)
                +
                IFNULL(SUM(CASE WHEN cd.type = 'deposit' AND cd.date_time >= ? AND cd.date_time < ? THEN cd.amount END), 0)
                -
                IFNULL(SUM(CASE WHEN cd.type = 'withdrawal' AND cd.date_time >= ? AND cd.date_time < ? THEN cd.amount END), 0)
            ) AS DOUBLE) as Closing_Balance
        ", [
            $startDate, $startDate,  // opening balance
            $startDate, $endDate,    // deposits
            $startDate, $endDate,    // withdrawals
            $startDate, $startDate,  // closing balance (opening)
            $startDate, $endDate,    // closing balance (deposits)
            $startDate, $endDate     // closing balance (withdrawals)
        ])
        ->groupBy('c.id', 'c.name')
        ->orderBy('c.name')
        ->get();

        $openingBalanceTotal = $results->sum('Opening_Balance');
        $depositTotal = $results->sum('Deposit_Total');
        $withdrawalTotal = $results->sum('Withdrawal_Total');
        $closingBalanceTotal = $results->sum('Closing_Balance');
        $data = [
            'opening_balance_total' => $openingBalanceTotal,
            'deposit_total' => $depositTotal,
            'withdrawal_total' => $withdrawalTotal,
            'closing_balance_total' => $closingBalanceTotal,

            'customer_deposits' => $results
        ];
        return $data;
    }
}
