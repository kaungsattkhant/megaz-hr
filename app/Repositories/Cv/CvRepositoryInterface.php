<?php

namespace App\Repositories\Cv;


interface CvRepositoryInterface
{
  public function getAllCvs($request);

  public function getCvById($id);
  public function skillByRoleAndDepartment($depId, $roleId);
  public function createCv(array $data);

  public function updateCv(int $id, array $data);

  public function deleteCv($id);
  public function updateCvStatus($id, array $data);

  public function getSalarySetupByDepartmentIdAndRoleId($departmentId, $roleId);
  public function createNewStaffSalary(array $data);
  public function storeNewStaffJoinDate(array $data);

  public function createCommunicationForm(array $data);
}
