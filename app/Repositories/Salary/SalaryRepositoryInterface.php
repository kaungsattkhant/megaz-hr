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
}
