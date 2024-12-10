<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\MRPForecast\MRPForecastRepositoryInterface;
use Illuminate\Http\Request;

class MRPForecastController extends Controller
{
    private MRPForecastRepositoryInterface $mrpForecastRepository;

    public function __construct(MRPForecastRepositoryInterface $mrpForecastRepository)
    {
        $this->mrpForecastRepository = $mrpForecastRepository;
    }

    public function getForcastMenusByMenuId(Request $request, $menuId)
    {
        $data =  $this->mrpForecastRepository->getForcastMenusByMenuId($request, $menuId);
        ResponseData($data);
    }

    public function getForcastMenus(Request $request)
    {

        $data =  $this->mrpForecastRepository->getForcastMenus($request->all());
        ResponseData($data);
    }

    public function getForcastHR(Request $request)
    {
        $data =  $this->mrpForecastRepository->getForcastHR($request->all());

        ResponseData($data);
    }
    public function getForcastHrByMenuId(Request $request, $menuId)
    {
        $data =  $this->mrpForecastRepository->getForcastHrByMenuId($request, $menuId);

        ResponseData($data);
    }

    public function getForcastRawMaterialByMenuId(Request $request, $menuId)
    {
        $data =  $this->mrpForecastRepository->getForcastRawMaterialByMenuId($request, $menuId);
        ResponseData($data);
    }



    public function getForcastRawMaterial(Request $request)
    {
        $data =  $this->mrpForecastRepository->getForcastRawMaterial($request);
        ResponseData($data);
    }

    public function storeForecast(Request $request)
    {

        $data = $this->mrpForecastRepository->storeForecast($request->all());

        ResponseData($data);
    }

    public function updateMenuForecast(Request $request, int $mrpForecastId)
    {
        $data = $this->mrpForecastRepository->updateMenuForecast($request->all(), $mrpForecastId);

        ResponseData($data);
    }

    public function getMonthlyMenuForecasts(Request $request)
    {
        $data = $this->mrpForecastRepository->getMonthlyMenuForecasts($request->all());

        ResponseData($data);
    }

    // getMonthlyMenuForecastsById

    public function getMonthlyMenuForecastsById(int $mrpForecastId)
    {

        $data = $this->mrpForecastRepository->getMonthlyMenuForecastsById($mrpForecastId);

        ResponseData($data);
    }

    public function getPoForecasts(Request $request)
    {
        $data = $this->mrpForecastRepository->getPoForecasts($request->all());

        ResponseData($data);
    }

    // storePoForecastsByItemId
    public function storePoForecastsByItemId(Request $request, $itemId)
    {
        $data = $this->mrpForecastRepository->storePoForecastsByItemId($request->all(), $itemId);

        ResponseData($data);
    }

    public function deleteMenuForecast($mrp_forecastable_id, $mrp_forecastable_type)
    {
        $data = $this->mrpForecastRepository->deleteMenuForecast($mrp_forecastable_id, $mrp_forecastable_type);

        ResponseData($data);
    }

    // deleteMonthlyMenuForecast
    public function deleteMonthlyMenuForecast($forecastId)
    {
        $data = $this->mrpForecastRepository->deleteMonthlyMenuForecast($forecastId);

        ResponseData($data);
    }
}
