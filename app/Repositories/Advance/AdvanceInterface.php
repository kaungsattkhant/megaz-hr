<?php

namespace App\Repositories\Advance;

use Illuminate\Http\Request;

interface AdvanceInterface
{
    public function list($data);

    public function create($data);

    public function getAdvancePaymentDetailByAdvance($advanceId);

    public function createAdvancePayment($data);
}