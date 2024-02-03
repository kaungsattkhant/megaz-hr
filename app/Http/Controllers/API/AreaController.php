<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Repositories\AreaRepositoryInterface;

class AreaController extends Controller
{
    //
    private $areaRepo;

    public function __construct(AreaRepositoryInterface $repo)
    {
        $this->areaRepo = $repo;
    }

    public function getAreas(Request $request)
    {
        $areas = $this->areaRepo->getAreas();

        ResponseData($areas);
    }
}
