<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\DepositAndReceivableReport\DepositAndReceivableReportRepositoryInterface;

class DepositReceivableReportController extends Controller
{
    //
    private $repo;

    public function __construct(DepositAndReceivableReportRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getDepositReceivalbeBalance(Request $request)
    {
        if(!$request->date){
            ResponseMessage('Please provide month for report', 400);
        }
        $start_end_dates = MonthStartAndEndDatesFromDateString($request->date);
        $startDate = $start_end_dates['start_date'];
        $endDate = $start_end_dates['end_date'];

        $otherReceivableBalances = $this->repo->getOtherReceivableBalance($startDate, $endDate);
        $otherReceivableTotalOpening = $otherReceivableBalances->sum('Opening_Balance');
        $otherReceivableTotalDebit = $otherReceivableBalances->sum('Debit_Total');
        $otherReceivableTotalCredit = $otherReceivableBalances->sum('Credit_Total');
        $otherReceivableTotalClosing = $otherReceivableBalances->sum('Closing_Balance');

        $depositPaidBalances = $this->repo->getDepositPaidBalances($startDate, $endDate);
        $depositPaidTotalOpening = $depositPaidBalances->sum('Opening_Balance');
        $depositPaidTotalDebit = $depositPaidBalances->sum('Debit_Total');
        $depositPaidTotalCredit = $depositPaidBalances->sum('Credit_Total');
        $depositPaidTotalClosing = $depositPaidBalances->sum('Closing_Balance');

        $fivePercentTaxBalances = $this->repo->getFivePercentTaxBalances($startDate, $endDate);
        $fivePercentTaxTotalOpening = $fivePercentTaxBalances->sum('Opening_Balance');
        $fivePercentTaxTotalDebit = $fivePercentTaxBalances->sum('Debit_Total');
        $fivePercentTaxTotalCredit = $fivePercentTaxBalances->sum('Credit_Total');
        $fivePercentTaxTotalClosing = $fivePercentTaxBalances->sum('Closing_Balance');

        $data = [
            'other_receivable_balances' => [
                "opening_balance_total" => $otherReceivableTotalOpening,
                "debit_total" => $otherReceivableTotalDebit,
                "credit_total" => $otherReceivableTotalCredit,
                "closing_balance_total" => $otherReceivableTotalClosing,
                "other_receivables" => $otherReceivableBalances
            ],

            'deposit_paid_balances' => [
                "opening_balance_total" => $depositPaidTotalOpening,
                "debit_total" => $depositPaidTotalDebit,
                "credit_total" => $depositPaidTotalCredit,
                "closing_balance_total" => $depositPaidTotalClosing,
                'deposit_paids' => $depositPaidBalances
            ],

            'five_percent_tax_balances' => [
                "opening_balance_total" => $fivePercentTaxTotalOpening,
                "debit_total" => $fivePercentTaxTotalDebit,
                "credit_total" => $fivePercentTaxTotalCredit,
                "closing_balance_total" => $fivePercentTaxTotalClosing,
                'five_percent_taxes' => $fivePercentTaxBalances
            ],
        ];

        ResponseData($data);
    }
}
