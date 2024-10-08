<?php

namespace App\Repositories\ItemUsageForecast;

use App\Models\ForecastItem;
use App\Models\ItemUsageForecast;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemUsageForecastRepository implements ItemUsageForecastInterface
{
    public function list($request)
    {
        $staff = Staff::find(UserData()->id);
        $isStaff = $staff->checkRoles('Staff');

        if($isStaff==true)
        {
            $itemUsageForecasts=ItemUsageForecast::with(['forecast_items','department'])
            ->orderByDesc('id')
            ->where('created_by',UserData()->id)
            ->paginate(config('app.common'));
            return $itemUsageForecasts;
        }else{
            $itemUsageForecasts=ItemUsageForecast::with(['forecast_items','department'])
            ->orderByDesc('id')
            ->paginate(config('app.common'));
            return $itemUsageForecasts;
        }

    }

    public function itemUsageForecastListByMonth()
    {
        $itemUsageForecasts = ItemUsageForecast::selectRaw('MONTH(date) as month')
            ->join('forecast_items', 'item_usage_forecasts.id', '=', 'forecast_items.item_usage_forecast_id')
            ->selectRaw('SUM(forecast_items.quantity) as total_quantity')
            ->groupByRaw('MONTH(date)')
            ->paginate(config('commont.list_count'));

        ResponseData($itemUsageForecasts);
    }



    public function itemUsageForecastListByMonthwithDepartment(int $month)
    {

        $loginUserDepartment = UserData()->department_id;
        if($loginUserDepartment == 2)
        {
            $itemUsageForecast = ItemUsageForecast::select('item_usage_forecasts.department_id', 'departments.name as department_name')
            ->join('forecast_items', 'item_usage_forecasts.id', '=', 'forecast_items.item_usage_forecast_id')
            ->join('departments', 'item_usage_forecasts.department_id', '=', 'departments.id')
            ->whereMonth('item_usage_forecasts.date', $month)
            ->groupBy('item_usage_forecasts.department_id', 'departments.name')
            ->orderBy('departments.name')
            ->paginate(config('common.list_count'));
        }else{
            $itemUsageForecast = ItemUsageForecast::select('item_usage_forecasts.department_id', 'departments.name as department_name')
            ->join('forecast_items', 'item_usage_forecasts.id', '=', 'forecast_items.item_usage_forecast_id')
            ->join('departments', 'item_usage_forecasts.department_id', '=', 'departments.id')
            ->whereMonth('item_usage_forecasts.date', $month)
            ->where('item_usage_forecasts.department_id', $loginUserDepartment)
            ->groupBy('item_usage_forecasts.department_id', 'departments.name')
            ->orderBy('departments.name')
            ->paginate(config('common.list_count'));
        }

        ResponseData($itemUsageForecast);
    }


    public function iufWithMonthAndDepartment(int $month,int $department_id)
    {
        $itemUsageForecasts = ItemUsageForecast::whereMonth('date',$month)->where('department_id',$department_id)->with('forecast_items','department')->paginate(config('common.list_count'));
        ResponseData($itemUsageForecasts);
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
            $data['department_id'] = $staff->department_id;
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
    public function delete($id){
        $itemUsageForecast = ItemUsageForecast::find($id);
        if ($itemUsageForecast) {
            $itemUsageForecast->delete();
            ResponseMessage("Delete successfully", 200);
        } else {
            ResponseMessage("Data isn't found ", 404);
        }
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
