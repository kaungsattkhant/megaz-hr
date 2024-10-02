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

    public function IndirectCashFlowStatement(Request $request){
        $data=$this->financialRepo->IndirectCashFlowStatement($request);
        ResponseData($data);
    }

    public function BalanceSheet(Request $request){
        $data=$this->financialRepo->BalanceSheet($request);
        ResponseData($data);
    }

    public function TrialBalance(Request $request){
        $data=$this->financialRepo->TrialBalance($request);
        ResponseData($data);
    }

    public function getProfitAndLoss(Request $request){
        $data=$this->financialRepo->getProfitAndLoss($request);
        ResponseData($data);
    }
}
