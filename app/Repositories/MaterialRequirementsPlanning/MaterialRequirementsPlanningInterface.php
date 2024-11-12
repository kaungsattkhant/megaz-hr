<?php

namespace App\Repositories\MaterialRequirementsPlanning;

use Illuminate\Http\Request;

interface MaterialRequirementsPlanningInterface
{
  public function getMrpLists(Request $request);

  public function store(Request $data);

  public function showMrpList($menuId, $request);

  public function updateMrpList($menuId, $validatedData);

  public function menuToggle($menuId, $validatedData);

  public function getMenuStepList($menuStepId);

  public function menuStepItemsDelete($menuStepItemId);
}
