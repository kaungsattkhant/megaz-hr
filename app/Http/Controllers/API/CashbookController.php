<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CashBook\CashBookInterface;
use App\Http\Action\Transaction\CashBookTransaction;
use App\Http\Requests\Financial\CashbookCloseRequest;
use stdClass;

class CashbookController extends Controller
{
    //
    private CashBookInterface $cashBookRepo;

    public function __construct(CashBookInterface $cashBook_repo)
    {
        $this->cashBookRepo = $cashBook_repo;
    }

    public function index(Request $request){
        $cashbooks= $this->cashBookRepo->list($request);
        ResponseData($cashbooks);
    }

    public function closeTransaction(CashbookCloseRequest $request){
        $cashbooks= $this->cashBookRepo->closeTransaction($request);
    }

    public function getCashbookClosingHistory(Request $request){
        $cashbooks= $this->cashBookRepo->getCashbookClosingHistory($request);
        ResponseData($cashbooks);

    }
}
