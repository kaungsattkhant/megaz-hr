<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Objective\ObjectiveRequest;
use App\Http\Requests\Objective\ObjImgRequest;
use App\Repositories\Objective\ObjectiveInterface;
use Illuminate\Http\Request;

class ObjectiveController extends Controller
{
    private ObjectiveInterface $objectiveRepository;
    public function __construct(ObjectiveInterface $objectiveRepository)
    {
        $this->objectiveRepository = $objectiveRepository;
    }

    public function getObjectives(Request $request)
    {
        $data = $this->objectiveRepository->getObjectives($request);
        ResponseData($data);
    }

    public function getRolesByDepartmentId(Request $request, int $departmentId)
    {
        $data = $this->objectiveRepository->getRolesByDepartmentId($request, $departmentId);
        ResponseData($data);
    }

    public function store(ObjectiveRequest $request)
    {

        $data = $this->objectiveRepository->store($request->all());
        ResponseData($data);
    }

    public function getObjectiveById(Request $request, int $objId)
    {

        $data = $this->objectiveRepository->getObjectiveById($request, $objId);
        ResponseData($data);
    }

    public function update(Request $request, int $objId)
    {
        $data = $this->objectiveRepository->update($request->all(), $objId);
        ResponseData($data);
    }


    public function deleteObjective(int $objId)
    {
        $data = $this->objectiveRepository->deleteObjective($objId);
        ResponseData($data);
    }


    //for mobile

    public function objectiveLists(Request $request)
    {
        $data = $this->objectiveRepository->objectiveLists($request);
        ResponseData($data);
    }

    public function getdailyObjectives(Request $request)
    {
        $data = $this->objectiveRepository->getdailyObjectives($request);
        ResponseData($data);
    }

    public function storeImages(ObjImgRequest $request)
    {
        $validatedData = $request->validated();
        $data = $this->objectiveRepository->storeImages($validatedData);
        ResponseData($data);
    }

    public function updateImages(ObjImgRequest $request, $objKeyImgId)
    {
        $validatedData = $request->validated();
        $data = $this->objectiveRepository->updateImages($validatedData, $objKeyImgId);
        ResponseData($data);
    }

    public function updateDailyObjective(Request $request, $objKeyStaffId)
    {

        $data = $this->objectiveRepository->updateDailyObjective($request->all(), $objKeyStaffId);
        ResponseData($data);
    }
}
