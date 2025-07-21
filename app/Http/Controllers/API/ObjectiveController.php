<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Objective\AssignRequest;
use App\Http\Requests\Objective\ObjImgRequest;
use App\Http\Requests\Objective\ObjectiveRequest;
use App\Repositories\Objective\ObjectiveInterface;
use App\Http\Requests\Objective\KtvProductTreeRequest;

class ObjectiveController extends Controller
{
    private ObjectiveInterface $objectiveRepository;
    public function __construct(ObjectiveInterface $objectiveRepository)
    {
        $this->objectiveRepository = $objectiveRepository;
    }

    public function dashboardOkr(Request $request)
    {
        $data = $this->objectiveRepository->dashboardOkr($request);
        ResponseData($data);
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

    public function store(Request $request)
    {

        $data = $this->objectiveRepository->store($request->all());
        ResponseData($data);
    }

    public function getObjectiveById(Request $request, int $objId)
    {

        $data = $this->objectiveRepository->getObjectiveById($request, $objId);
        ResponseData($data);
    }

    // public function update(Request $request, int $objId)
    // {
    //     $data = $this->objectiveRepository->update($request->all(), $objId);
    //     ResponseData($data);
    // }
    public function deleteObjective(int $objId)
    {
        $data = $this->objectiveRepository->deleteObjective($objId);
        ResponseData($data);
    }

    // assign duties 

    public function getObjectiveKeysByStaffId(int $staffId)
    {
        $data = $this->objectiveRepository->getObjectiveKeysByStaffId($staffId);
        ResponseData($data);
    }

    public function getAssignDutiesByObjectiveKeys(Request $request, $assignDutyId = null)
    {
        $data = $this->objectiveRepository->getAssignDutiesByObjectiveKeys($request, $assignDutyId = null);
        ResponseData($data);
    }

    public function showAssignDutiesById(Request $request, $assignDutyId)
    {
        $data = $this->objectiveRepository->getAssignDutiesByObjectiveKeys($request, $assignDutyId);
        ResponseData($data);
    }


    public function deleteAssignDutiesById($assignDutyId)
    {
        $data = $this->objectiveRepository->deleteAssignDutiesById($assignDutyId);
        ResponseData($data);
    }

    public function deleteAssignObjKeyStaffById(int $objKeyStaffId)
    {
        $data = $this->objectiveRepository->deleteAssignObjKeyStaffById($objKeyStaffId);
        ResponseData($data);
    }


    public function storeAssignDutiesByObjectives(Request $request)
    {
        $data = $this->objectiveRepository->storeAssignDutiesByObjectives($request->all());
        ResponseData($data);
    }

    public function getOkrAssigns(Request $request)
    {
        $data = $this->objectiveRepository->getOkrAssigns($request);
        ResponseData($data);
    }

    public function getOkrAssignById(int $okrAssignId)
    {
        $data = $this->objectiveRepository->getOkrAssignById($okrAssignId);
        ResponseData($data);
    }
    public function deleteOkrAssignById(int $okrAssignId)
    {
        $data = $this->objectiveRepository->deleteOkrAssignById($okrAssignId);
        ResponseData($data);
    }

    //for mobile

    public function objectiveLists(Request $request)
    {
        $data = $this->objectiveRepository->objectiveLists($request);
        ResponseData($data);
    }

    public function getdailyObjectives(Request $request, $objId)
    {
        $data = $this->objectiveRepository->getdailyObjectives($request, $objId);
        ResponseData($data);
    }
    public function getObjKeyStaffImage($objKeystaffId)
    {
        $data = $this->objectiveRepository->getObjKeyStaffImage($objKeystaffId);
        ResponseData($data);
    }


    public function storeImages(ObjImgRequest $request, $objKeystaffId)
    {
        $validatedData = $request->validated();

        $data = $this->objectiveRepository->storeImages($validatedData, $objKeystaffId);
        ResponseData($data);
    }

    public function updateImages(ObjImgRequest $request, $objKeyStaffId)
    {
        $validatedData = $request->validated();
        $data = $this->objectiveRepository->updateImages($validatedData, $objKeyStaffId);
        ResponseData($data);
    }

    public function updateDailyObjective(Request $request, $objKeyStaffId)
    {

        $data = $this->objectiveRepository->updateDailyObjective($request->all(), $objKeyStaffId);
        ResponseData($data);
    }


    public function deleteObjKeystaffImage($imgId)
    {
        $data = $this->objectiveRepository->deleteObjKeystaffImage($imgId);
        ResponseData($data);
    }

    public function getdailyObjectivesByStaffId(Request $request, $staffId)
    {
        $data = $this->objectiveRepository->getdailyObjectivesByStaffId($request, $staffId);
        ResponseData($data);
    }

    public function storeCompletedObjKeys(Request $request)
    {
        $data = $this->objectiveRepository->storeCompletedObjKeys($request->all());
        ResponseData($data);
    }

    //ktvProductTree

    public function getKtvRoom(Request $request)
    {
        $data = $this->objectiveRepository->getKtvRoom($request);
        ResponseData($data);
    }



    public function  getKtvObjectiveTree(Request $request)
    {
        $data = $this->objectiveRepository->getKtvObjectiveTree($request);
        ResponseData($data);
    }

    public function getKtvObjective(Request $request, $departmentId)
    {
        $data = $this->objectiveRepository->getKtvObjective($request, $departmentId);
        ResponseData($data);
    }

    public function storeKtvObjectiveTree(KtvProductTreeRequest $request)
    {
        $data = $this->objectiveRepository->storeKtvObjectiveTree($request);
        ResponseData($data);
    }

    public function getKtvObjTreeById(Request $request, $id)
    {
        $data = $this->objectiveRepository->getKtvObjTreeById($request, $id);
        ResponseData($data);
    }

    public function updateKtvObjTree(Request $request, $ktvObjTreeId)
    {
        $data = $this->objectiveRepository->updateKtvObjTree($request, $ktvObjTreeId);
        ResponseData($data);
    }
}
