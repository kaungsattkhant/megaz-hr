<?php

namespace App\Repositories\Objective;

use Illuminate\Http\Request;

interface ObjectiveInterface
{
  //adminpanel
  public function dashboardOkr($request);
  public function getObjectives(Request $request);
  public function getObjectiveById(Request $request, $objId);
  public function getRolesByDepartmentId(Request $request, int $departmentId);
  public function store(array $validatedData);
  // public function update(array $validatedData, int $objId);
  public function deleteObjective($objId);

  //assign duties keyresults
  public function getObjectiveKeysByStaffId(int $staffId);
  public function getAssignDutiesByObjectiveKeys(Request $request, $assignDutyId = null);
  public function deleteAssignDutiesById($assignDutyId);
  public function deleteAssignObjKeyStaffById(int $objKeyStaffId);

  //okr assign 
  public function storeAssignDutiesByObjectives($validatedData);
  public function getOkrAssigns(Request $request);
  public function getOkrAssignById(int $okrAssignId);
  public function deleteOkrAssignById(int $okrAssignId);

  //mobil
  public function objectiveLists(Request $request);
  public function getdailyObjectives(Request $request, $objId);
  public function updateDailyObjective(array $data,$objStaffId);
  public function storeImages($validatedData, $objKeystaffId);
  public function updateImages($validatedData, $objKeyStaffId);
  public function getObjKeyStaffImage($objKeystaffId);
  public function deleteObjKeystaffImage($imgId);
  public function getdailyObjectivesByStaffId(Request $request, $staffId);
  public function getDailyObjectiveByAccountable($staffId);
  public function getStaffByAccountable($staffId);
  public function getCompletedObjKeysByStaffId($objectiveId,$staffId);
  public function rejectObjectKeyByObjectiveStaffId($data);

  //ktvobjtree
  public function getKtvRoom(Request $request);
  public function getKtvObjective(Request $request, $departmentId);
  public function  getKtvObjectiveTree(Request $request);
  public function storeKtvObjectiveTree(Request $request);
  public function getKtvObjTreeById(Request $request, $id);
  public function updateKtvObjTree(Request $request, $ktvObjTreeId);
}
