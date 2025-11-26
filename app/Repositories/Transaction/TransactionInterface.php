<?php

namespace App\Repositories\Transaction;


interface TransactionInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($transaction);

    public function delete($id);

    public function transactionConfirmed($request);
}
