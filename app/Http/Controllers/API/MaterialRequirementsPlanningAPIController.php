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

    public function store(Request $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->store($request);
        ResponseData($data);
    }

    public function show(int $menuId, Request $request)
    {

        $data = $this->MaterialRequirementsPlanningRepository->showMrpList($menuId, $request);
        ResponseData($data);
    }

    public function updateMrpList(Request $request, int $menuId)
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

    public function getRoles(Request $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->getRoles($request);
        ResponseData($data);
    }

    public function getMenuCategoryCookingAreas(Request $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->getMenuCategoryCookingAreas($request);
        ResponseData($data);
    }

    public function createMenuCategoryCookingAreas(Request $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->createMenuCategoryCookingAreas($request);
        ResponseData($data);
    }

    public function getAreaCategories(Request $request)
    {
        $data = $this->MaterialRequirementsPlanningRepository->getAreaCategories($request);
        ResponseData($data);
    }
}
