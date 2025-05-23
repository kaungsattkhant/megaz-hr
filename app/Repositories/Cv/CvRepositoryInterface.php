<?php

namespace App\Repositories\Cv;


interface CvRepositoryInterface
{
  public function getAllCvs($request);

  // public function getCvById(int $id);
  public function skillByRoleAndDepartment($depId, $roleId);
  public function createCv(array $data);

  // public function updateCv(int $id, array $data);

  // public function deleteCv(int $id);
}
