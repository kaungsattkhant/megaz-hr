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

    public function getForcastMenus(Request $request, $menuId)
    {
        $data =  $this->mrpForecastRepository->getForcastMenus($request, $menuId);
        ResponseData($data);
    }

    public function getForcastHR(Request $request, $menuId)
    {
        $data =  $this->mrpForecastRepository->getForcastHR($request, $menuId);

        ResponseData($data);
    }

    public function getForcastRawMaterial(Request $request, $menuId)
    {
        $data =  $this->mrpForecastRepository->getForcastRawMaterial($request, $menuId);
        ResponseData($data);
    }

    public function storeForecast(Request $request)
    {

        $data = $this->mrpForecastRepository->storeForecast($request->all());

        ResponseData($data);
    }

    public function updateForecast(Request $request, int $mrpForecastId)
    {
        $data = $this->mrpForecastRepository->updateForecast($request->all(), $mrpForecastId);

        ResponseData($data);
    }

    public function getForecasts(Request $request)
    {
        $data = $this->mrpForecastRepository->getForecasts($request->all());

        ResponseData($data);
    }

    // updateMenuForecast
    // public function updateMenuForecast(Request $request, $menuId)
    // {
    //     $data = $this->mrpForecastRepository->updateMenuForecast($request->all(), $menuId);

    //     ResponseData($data);
    // }

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
}
