<?php

namespace App\Repositories\Salary;

interface SalaryRepositoryInterface
{
  public function getAllowances();

  public function createAllowance($data);

  public function storeSalarySetUp($data);

  public function getSalarySetUp($request);

  public function getSalarySetUpById($id);

  public function updateSalarySetUp($data, $id);

  public function deleteSalaryAllowance($salaryAllowanceId);

  public function getSalaries($request);

  public function updateBasicSalary($request, $id);

  public function getOvertimeFee($request);

  public function createOvertimeFee($data);

  public function getOvertimeCategories($request);

  public function createOvertimeCategories($data);

  public function createOvertime($data);

  public function getOvertimes($request);

  public function setOvertimeApproval($data, $id);

  public function getMobileOvertimesByStaffId($request, $staffId);

  public function createSalaryBatch($data);

  public function getSalaryBatch($request);
}
