<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MRP\MrpStoreRequest;
use App\Repositories\MaterialRequirementsPlanning\MaterialRequirementsPlanningInterface;
use Illuminate\Http\Request;

class MaterialRequirementsPlanningAPIController extends Controller
{
    private MaterialRequirementsPlanningInterface $MaterialRequirementsPlanningRepository;
    public function __construct(MaterialRequirementsPlanningInterface $MaterialRequirementsPlanningRepository)
    {
        $this->MaterialRequirementsPlanningRepository = $MaterialRequirementsPlanningRepository;
    }

    public function index(Request $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->getMrpLists($request);
        ResponseData($data);
    }

    public function store(MrpStoreRequest $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->store($request->validated());
        ResponseData($data);
    }

    public function show(int $menuId, Request $request)
    {

        $data = $this->MaterialRequirementsPlanningRepository->showMrpList($menuId, $request);
        ResponseData($data);
    }

    public function update(Request $request, int $menuId)
    {
        $data = $this->MaterialRequirementsPlanningRepository->updateMrpList($menuId, $request->all());
        ResponseData($data);
    }


    public function destroy(string $id)
    {
        //
    }
}
