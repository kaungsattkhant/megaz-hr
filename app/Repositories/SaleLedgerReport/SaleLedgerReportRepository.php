<?php

namespace App\Repositories\SaleLedgerReport;

use Illuminate\Support\Facades\DB;

use App\Http\Action\Common\AccountFetcher;

class SaleLedgerReportRepository implements SaleLedgerReportRepositoryInterface
{
    public function getKtvSaleLedgerReport(string $startDate, string $endDate)
    {
        $accountFetcher = new AccountFetcher();
        $ktvFoodAccount = $accountFetcher->getAccountByCode('5-0101');
        $ktvBeverageAccount = $accountFetcher->getAccountByCode('5-0102');
        $ktvRoomChargesAccount = $accountFetcher->getAccountByCode('5-0103');
        $otherChargesAccount = $accountFetcher->getAccountByCode('5-1014');
        $serviceChargeAccount = $accountFetcher->getAccountByCode('5-1009');
        $results = DB::table('invoices as i')
        ->join('areas as a', 'i.area_id', '=', 'a.id')
        ->join('area_types as at', 'a.area_type_id', '=', 'at.id')
        ->join('transactions as t', function($join) {
            $join->on('t.transactionable_id', '=', 'i.id')
                ->where('t.transactionable_type', '=', 'invoice');
        })
        ->join('ledgers as le', 'le.transaction_id', '=', 't.id')
        ->selectRaw("
            DATE(i.invoice_date) as report_date,
            SUM(CASE WHEN le.account_id={$ktvFoodAccount->id} THEN le.value ELSE 0 END) as Food_Total,
            SUM(CASE WHEN le.account_id={$ktvBeverageAccount->id} THEN le.value ELSE 0 END) as Beverage_Total,
            SUM(CASE WHEN le.account_id={$ktvRoomChargesAccount->id} THEN le.value ELSE 0 END) as Room_Charges,
            SUM(CASE WHEN le.account_id={$otherChargesAccount->id} THEN le.value ELSE 0 END) as Other_Charges,
            SUM(CASE WHEN le.account_id={$serviceChargeAccount->id} THEN le.value ELSE 0 END) as Service_Charge,
            (
                SUM(CASE WHEN le.account_id IN (
                    {$ktvFoodAccount->id},
                    {$ktvBeverageAccount->id},
                    {$ktvRoomChargesAccount->id},
                    {$otherChargesAccount->id},
                    {$serviceChargeAccount->id}
                ) THEN le.value ELSE 0 END)
            ) as All_Total
        ")
        ->where('at.type', 'ktv') // or 'bar_and_restaurant'
        ->where('le.action', 'credit')
        ->whereBetween('i.invoice_date', [
            $startDate,
            $endDate,
        ])
        ->groupBy(DB::raw('DATE(i.invoice_date)'))
        ->orderBy('report_date')
        ->get();

        return $results;
    }

    public function getRestaurantSaleLedgerReport(string $startDate, string $endDate)
    {
        $accountFetcher = new AccountFetcher();
        $rtFoodAccount = $accountFetcher->getAccountByCode('5-0001');
        $rtBeverageAccount = $accountFetcher->getAccountByCode('5-0002');
        $rtRoomChargesAccount = $accountFetcher->getAccountByCode('5-0003');
        $otherChargesAccount = $accountFetcher->getAccountByCode('5-1014');
        $serviceChargeAccount = $accountFetcher->getAccountByCode('5-1009');
        $results = DB::table('invoices as i')
        ->join('areas as a', 'i.area_id', '=', 'a.id')
        ->join('area_types as at', 'a.area_type_id', '=', 'at.id')
        ->join('transactions as t', function($join) {
            $join->on('t.transactionable_id', '=', 'i.id')
                ->where('t.transactionable_type', '=', 'invoice');
        })
        ->join('ledgers as le', 'le.transaction_id', '=', 't.id')
        ->selectRaw("
            DATE(i.invoice_date) as report_date,
            SUM(CASE WHEN le.account_id={$rtFoodAccount->id} THEN le.value ELSE 0 END) as Food_Total,
            SUM(CASE WHEN le.account_id={$rtBeverageAccount->id} THEN le.value ELSE 0 END) as Beverage_Total,
            SUM(CASE WHEN le.account_id={$rtRoomChargesAccount->id} THEN le.value ELSE 0 END) as Room_Charges,
            SUM(CASE WHEN le.account_id={$otherChargesAccount->id} THEN le.value ELSE 0 END) as Other_Charges,
            SUM(CASE WHEN le.account_id={$serviceChargeAccount->id} THEN le.value ELSE 0 END) as Service_Charge,
            (
                SUM(CASE WHEN le.account_id IN (
                    {$rtFoodAccount->id},
                    {$rtBeverageAccount->id},
                    {$rtRoomChargesAccount->id},
                    {$otherChargesAccount->id},
                    {$serviceChargeAccount->id}
                ) THEN le.value ELSE 0 END)
            ) as All_Total
        ")
        ->where('at.type', 'bar_and_restaurant')
        ->where('le.action', 'credit')
        ->whereBetween('i.invoice_date', [
            $startDate,
            $endDate,
        ])
        ->groupBy(DB::raw('DATE(i.invoice_date)'))
        ->orderBy('report_date')
        ->get();

        return $results;
    }
}
