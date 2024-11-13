<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MRP\MenuToggleRequest;
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

    public function menuToggle($menuId, MenuToggleRequest $validatedData)
    {
        $data = $this->MaterialRequirementsPlanningRepository->menuToggle($menuId, $validatedData);
        ResponseData($data);
    }

    public function getMenuStepList(int $menuStepId)
    {
        $data = $this->MaterialRequirementsPlanningRepository->getMenuStepList($menuStepId);
        ResponseData($data);
    }

    public function menuStepItemsDelete(int $menuStepItemId)
    {
        $data = $this->MaterialRequirementsPlanningRepository->menuStepItemsDelete($menuStepItemId);
        ResponseData($data);
    }

    // getCookingPlace

    public function getCookingPlace(Request $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->getCookingPlace($request);
        ResponseData($data);
    }
}
