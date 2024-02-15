<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\Area\AreaCreateRequest;
use App\Http\Requests\Area\AreaUpdateRequest;

use App\Repositories\Area\AreaRepositoryInterface;

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
        $areas = $this->areaRepo->getAreas($request);
        ResponseData($areas);
    }

    public function createArea(AreaCreateRequest $request)
    {
        $data = $request->all();
        $area = $this->areaRepo->createData($data);

        ResponseData($area);
    }

    public function updateArea(AreaUpdateRequest $request, int $id)
    {
        $data = $request->all();
        $area = $this->areaRepo->updateData($data, $id);
        if($area){
            ResponseData($area);
        }

        ResponseMessage('No area found with given id', 404);
    }

    public function deleteArea(int $id)
    {
        $isDeleted = $this->areaRepo->deleteData($id);
        if($isDeleted){
            ResponseMessage('Area deleted');
        }
        ResponseMessage('Area not deleted', 404);
    }
}
