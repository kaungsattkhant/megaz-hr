<?php

namespace App\Repositories\DeliveryCharge;

use Illuminate\Http\Request;

interface DeliveryChargeRepositoryInterface
{
    public function listAllData(Request $request);

    public function createData(Request $request);

}
