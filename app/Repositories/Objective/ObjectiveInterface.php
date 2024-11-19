<?php

namespace App\Repositories\Objective;

use Illuminate\Http\Request;

interface ObjectiveInterface
{
  public function getObjectives(Request $request);
 public function getRolesByDepartmentId(Request $request,int $departmentId); 
  public function store($validatedData);
}
