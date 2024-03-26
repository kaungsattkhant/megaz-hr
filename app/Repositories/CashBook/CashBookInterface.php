<?php

namespace App\Repositories\CashBook;

interface CashBookInterface
{
    public function list($request);

    public function closeTransaction($request);
}