<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\SaleLedgerReport\SaleLedgerReportRepositoryInterface;

class SalesLedgerReportController extends Controller
{
    //
    private $repo;

    public function __construct(SaleLedgerReportRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getSaleLedgerReport(Request $request)
    {
        if(!$request->date){
            ResponseMessage('Please provide month for report', 400);
        }
        $type = ($request->type)? $request->type: 'ktv';

        $start_end_dates = MonthStartAndEndDatesFromDateString($request->date);
        $startDate = $start_end_dates['start_date'];
        $endDate = $start_end_dates['end_date'];
        if($type == 'ktv'){
            $ktvResult = $this->repo->getKtvSaleLedgerReport($startDate, $endDate);
            $result = $ktvResult;
        }else if($type == 'rt'){
            $rtResult = $this->repo->getRestaurantSaleLedgerReport($startDate, $endDate);
            $result = $rtResult;
        }else{
            // dd('this');
            $ktvResult = $this->repo->getKtvSaleLedgerReport($startDate, $endDate);
            $rtResult = $this->repo->getRestaurantSaleLedgerReport($startDate, $endDate);
            // Index by date for fast lookup
            $ktvByDate = $ktvResult->keyBy('report_date');
            $restaurantByDate = $rtResult->keyBy('report_date');
            // Get all unique dates
            $allDates = $ktvByDate->keys()->merge($restaurantByDate->keys())->unique()->sort();

            // Build merged report
            $merged = $allDates->map(function ($date) use ($ktvByDate, $restaurantByDate) {
                $ktvRow = $ktvByDate->get($date, []);
                $restaurantRow = $restaurantByDate->get($date, []);

                $row['report_date'] = $date;

                // merge dynamically (prefix each key to avoid collisions)
                foreach ($ktvRow as $key => $value) {
                    if ($key !== 'report_date') {
                        $row[$key . '_ktv'] = $value ?? 0;
                    }
                }

                foreach ($restaurantRow as $key => $value) {
                    if ($key !== 'report_date') {
                        $row[$key . '_restaurant'] = $value ?? 0;
                    }
                }

                return $row;
            });

            $result = $merged;
        }

        ResponseData($result);
    }
}
