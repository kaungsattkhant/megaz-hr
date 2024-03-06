<?php

namespace App\Repositories\ItemUsageForecast;


interface ItemUsageForecastInterface
{
    public function list($request);

    public function updateOrCreate($request);

    public function detail($item_usage_forecast);

    public function  deleteForecastItem($item);
}