<?php

namespace App\Repositories\Resignation;

interface ResignationRepositoryInterface
{
  public function getResignationCategoryLists();
  public function createResignationCategory(array $data);

  public function getAllResignations();
  public function createResignation(array  $validatedData);
  public function getResignationById($id);
  public function updateResignation(array $data, $id);
  public function getResignationByStaffId($staffId);
}
