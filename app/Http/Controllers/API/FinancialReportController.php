<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\FinancialReport\FinancialInterface;

class FinancialReportController extends Controller
{
    //
    private $financialRepo;
    public function __construct(FinancialInterface $repo)
    {
        $this->financialRepo=$repo;
    }

    public function CashFlowStatement(Request $request){
        $data=$this->financialRepo->CashFlowStatement($request);
        ResponseData($data);
    }
}
