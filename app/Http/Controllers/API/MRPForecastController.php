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

    // public function getObjectives(Request $request)
    // {
    //     $data =  $this->mrpForecastRepository->getObjectives($request);

    //     ResponseData($data);
    // }
}
