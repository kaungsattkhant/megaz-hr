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
        return $this->itemUsageForecastRepo->list($request);
    }

    public function store(Request $request){
        return $this->itemUsageForecastRepo->updateOrCreate($request);

    }

    public function show(ItemUsageForecast $item_usage_forecast){
        return $this->itemUsageForecastRepo->detail($item_usage_forecast);
    }
    public function destroy($id){
        return $this->itemUsageForecastRepo->delete($id);
    }
    public function destroyForecastItem($id){
        return $this->itemUsageForecastRepo->deleteForecastItem($id);
    }
}
