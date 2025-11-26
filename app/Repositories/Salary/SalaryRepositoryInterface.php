<?php

namespace App\Repositories\Salary;

interface SalaryRepositoryInterface
{
  public function getAllowances($request);

  public function createAllowance($data);

  public function storeSalarySetUp($data);

  public function getSalarySetUp($request);

  public function getSalarySetUpById($id);

  public function updateSalarySetUp($data, $id);

  public function deleteSalaryAllowance($salaryAllowanceId);

  public function getSalaries($request);

  public function getSalarySetupByRoleId($roleId);

  public function createSalary($data);

  public function updateBasicSalary($request, $id);

  public function getOvertimeFee($request);

  public function createOvertimeFee($data);

  public function deleteOvertimeFee($id);

  public function getOvertimeCategories($request);

  public function createOvertimeCategories($data);

  public function createOvertime($data);

  public function getOvertimes($request);

  public function setOvertimeApproval($data, $id);

  public function getMobileOvertimesByStaffId($request, $staffId);

  public function createSalaryBatch($data);

  public function getSalaryBatch($request);

  public function getSalaryBatchById($id);

  public function updateSalaryBatch($data, $id);

  public function deleteSalaryBatch($id);

  public function deleteSalaryBatchStaff($id);

  public function calculateSalary($request);

  public function getAllowanceTypes($request);

  public function createPaySlip($data);

  public function getPaySlips($request);

  public function deletePaySlip($id);
}
