<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Actions\Inventory\GetInventoryStockAction;

use App\Models\Inventory;
use App\Models\InventoryLedger;
use App\Models\Item;

class TestController extends Controller
{
    //
    public function index()
    {
        $items = (new GetInventoryStockAction(2))->run();

        ResponseData($items);
    }
}
