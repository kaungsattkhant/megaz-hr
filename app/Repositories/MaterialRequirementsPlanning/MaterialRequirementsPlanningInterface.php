<?php

namespace App\Repositories\MaterialRequirementsPlanning;

use Illuminate\Http\Request;

interface MaterialRequirementsPlanningInterface
{
  public function getMrpLists(Request $data);
  public function store(Request $data);
  public function showMrpList($menuId, $request);
  public function updateMrpList($menuId, $validatedData);
}
