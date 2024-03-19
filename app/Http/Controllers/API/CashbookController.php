<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\CashBook\CashBookInterface;
use App\Http\Action\Transaction\CashBookTransaction;

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
        
        $balance=(new CashBookTransaction())->getOpeningBalance($request);
        return response()->json([
            'success'=>true,
            'opening_balance'=>$balance->opening_balance,
            // 'closing_balance'=>$balance->closing_balance,
            'data'=>$cashbooks,
        ]);
        // ResponseData($cashbooks);
    }

    public function closeTransaction(Request $request){
        $cashbooks= $this->cashBookRepo->closeTransaction($request);
    }

}
