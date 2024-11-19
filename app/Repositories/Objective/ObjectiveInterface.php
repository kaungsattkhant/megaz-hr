<?php

namespace App\Repositories\Objective;

use Illuminate\Http\Request;

interface ObjectiveInterface
{
  public function getObjectives(Request $request);
  public function getObjectiveById(Request $request, $objId);
  public function getRolesByDepartmentId(Request $request, int $departmentId);
  public function store(array $validatedData);
  public function update(array $validatedData, int $objId);
  public function deleteObjective($objId);
}
