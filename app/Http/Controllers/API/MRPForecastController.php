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
}
