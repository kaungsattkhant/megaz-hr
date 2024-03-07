<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\ItemUsageForecast;
use App\Repositories\ItemUsageForecast\ItemUsageForecastInterface;
use Illuminate\Http\Request;

class ItemUsageForecastController extends Controller
{
    //
    private ItemUsageForecastInterface $itemUsageForecastRepo;

    public function __construct(ItemUsageForecastInterface $item_forecast_repo){
        $this->itemUsageForecastRepo=$item_forecast_repo;
    }

    public function index(Request $request){
         $itemUsageForecast=$this->itemUsageForecastRepo->list($request);
         ResponseData($itemUsageForecast);
    }

    public function store(Request $request){
         $itemUsageForecast=$this->itemUsageForecastRepo->updateOrCreate($request);
         ResponseData($itemUsageForecast);
    }

    public function show(ItemUsageForecast $item_usage_forecast){
        $itemUsageForecast= $this->itemUsageForecastRepo->detail($item_usage_forecast);
        ResponseData($itemUsageForecast);
    }
    public function destroy($id){
        ResponseMessage('Deleted');
        return $this->itemUsageForecastRepo->delete($id);
    }
    public function destroyForecastItem($id){
        ResponseMessage('Deleted');
        return $this->itemUsageForecastRepo->deleteForecastItem($id);
    }
}
