<?php

namespace App\Repositories\ItemUsageForecast;

use Illuminate\Http\Request;

interface ItemUsageForecastInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($item_usage_forecast);

    public function delete($id);

    public function  deleteForecastItem($item);

    public function itemUsageForecastListByMonth();

    public function itemUsageForecastListByMonthwithDepartment(int $month);

    public function iufWithMonthAndDepartment(int $month,int $department_id);

}
