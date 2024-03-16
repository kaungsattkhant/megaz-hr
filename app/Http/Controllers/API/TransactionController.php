<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Transaction\TransactionInterface;

class TransactionController extends Controller
{
    //
    private TransactionInterface $transactionRepo;

    public function __construct(TransactionInterface $transaction_repo)
    {
        $this->transactionRepo = $transaction_repo;
    }

    public function index(Request $request){
        $transactions= $this->transactionRepo->list($request);
        ResponseData($transactions);
    }
    public function store(Request $request){
        $transaction= $this->transactionRepo->updateOrCreate($request);
        ResponseData($transaction);
    }

    public function show(Transaction $transaction){
        $transaction= $this->transactionRepo->detail($transaction);
        ResponseData($transaction);
    }

}
