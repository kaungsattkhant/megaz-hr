<?php

namespace App\Repositories\MaterialRequirementsPlanning;

use Illuminate\Http\Request;

interface MaterialRequirementsPlanningInterface
{
  public function getMrpLists(Request $request);

  public function store(Request $data);

  public function showMrpList($menuId, $request);

  public function updateMrpList($menuId, $validatedData);

  public function createMenuCategory($data);
  
  public function menuToggle($menuId, $validatedData);

  public function getMenuStepList($menuStepId);

  public function menuStepItemsDelete($menuStepItemId);

  public function getCookingPlace(Request $request);

  public function getRoles(Request $request);

  public function getMenuCategoryCookingAreas(Request $request);

  public function updateMenuCategoryCookingAreas($menuAreaId);

  public function getSellingAreas(Request $request);

  public function getMenuPrices($menuId);

  public function updateMenuPrices(int $menuId, array $data);

  public function getRemarks();

  public function createRemark($data);

  public function saleReport($request);
}
