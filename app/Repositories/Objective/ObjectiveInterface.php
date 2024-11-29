<?php

namespace App\Repositories\Objective;

use Illuminate\Http\Request;

interface ObjectiveInterface
{
  //adminpanel
  public function getObjectives(Request $request);
  public function getObjectiveById(Request $request, $objId);
  public function getRolesByDepartmentId(Request $request, int $departmentId);
  public function store(array $validatedData);
  public function update(array $validatedData, int $objId);
  public function deleteObjective($objId);

  //mobil
  public function objectiveLists(Request $request);
  public function getdailyObjectives(Request $request, $objId);
  public function updateDailyObjective(array $data, $objKeyStaffId);
  public function storeImages($validatedData, $objKeystaffId);
  public function updateImages($validatedData, $objKeyStaffId);
  public function getObjKeyStaffImage($objKeystaffId);
  public function deleteObjKeystaffImage($imgId);
  public function getdailyObjectivesById(Request $request, $objId);

  //ktvobjtree
  public function getKtvRoom(Request $request);
  public function getKtvObjective(Request $request);
  public function  getKtvObjectiveTree(Request $request);
  public function storeKtvObjectiveTree(Request $request);
  public function getKtvObjTreeById(Request $request, $id);
  public function updateKtvObjTree(Request $request, $ktvObjTreeId);
}
