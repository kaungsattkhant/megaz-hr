<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\CreditPurchaseJournal\CreditPurchaseJournalRepositoryInterface;

class CreditPurchaseJournalController extends Controller
{
    //
    private $repo;

    public function __construct(CreditPurchaseJournalRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getCreditPurchaseJournalBalance(Request $request)
    {
        if(!$request->date){
            ResponseMessage('Please provide month for report', 400);
        }
        $start_end_dates = MonthStartAndEndDatesFromDateString($request->date);
        $startDate = $start_end_dates['start_date'];
        $endDate = $start_end_dates['end_date'];

        $result = $this->repo->getCreditPurchaseJournal($startDate, $endDate);
        ResponseData($result);
    }
}
