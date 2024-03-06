<?php

namespace App\Repositories\ItemUsageForecast;

use App\Models\ForecastItem;
use App\Models\ItemUsageForecast;
use Illuminate\Support\Facades\DB;

class ItemUsageForecastRepository implements ItemUsageForecastInterface
{
    public function list($request)
    {
        $itemUsageForecasts=ItemUsageForecast::with(['forecast_items'])
        ->orderByDesc('id')
        ->paginate(20);
        return $itemUsageForecasts;
    }

    public function updateOrCreate($request)
    {
        $data = $request->all();
        $staff=UserData();
        $items = json_decode($request->items);
        DB::beginTransaction();
        try {
            if (!isset($request->id)) {
                $data['id'] = null;
            }
            $data['created_by']=$staff->id;
            $itemUsageForecast = ItemUsageForecast::updateOrCreate(
                ['id' => $data['id']],
                $data
            );
            foreach ($items as $item) {
                // if (!isset($item->id) || $item->id==null) {
                if (isset($item->id) && $item->id !== null) {
                    $item_data['id'] = $item->id;
                } else {
                    $item_data['id'] = null;
                }
                $item_data['quantity'] = $item->quantity;
                $item_data['item_usage_forecast_id'] = $itemUsageForecast->id;
                $item_data['item_id'] = $item->item_id;
                $item_data['amount'] = $item->amount;
                $itemUsageForecast->forecast_items()->updateOrCreate(['id' => $item_data['id']], $item_data);
            }
            DB::commit();
            return $itemUsageForecast;
        } catch (\Exception $e) {
            DB::rollback();
            ResponseMessage($e->getMessage(), 402);
            throw $e;
        }
    }

    public function detail($itemUsageForecast)
    {
        $itemUsageForecast->forecast_items=$itemUsageForecast->forecast_items;
        return $itemUsageForecast;
    }

    public function deleteForecastItem($id){
        $forecastItem = ForecastItem::find($id);
        if ($forecastItem) {
            $forecastItem->delete();
            ResponseMessage("Delete successfully", 200);
        } else {
            ResponseMessage("Data isn't found ", 404);
        }
    }

}
