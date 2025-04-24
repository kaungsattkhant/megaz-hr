<?php

namespace App\Repositories\Salary;

use App\Models\Staff;
use App\Models\Salary;
use App\Models\Allowance;
use App\Models\SalarySetup;
use App\Models\SalaryAllowance;
use Illuminate\Support\Facades\DB;

class SalaryRepository implements SalaryRepositoryInterface
{
  public function getAllowances()
  {
    return Allowance::with('role.department')->orderBy('id', 'desc')->paginate(config('common.list_count'));
  }
  public function createAllowance($data)
  {
    DB::beginTransaction();
    try {
      $existingAllowance = Allowance::where('name', $data['name'])
        ->where('role_id', $data['role_id'])
        ->where('type', $data['type'])
        ->first();
      if ($existingAllowance) {
        DB::rollback();
        ResponseMessage('Allowance already exists.', 409);
      }
      $allowance = Allowance::create([
        'name' => $data['name'],
        'amount' => $data['amount'],
        'type' => $data['type'],
        'role_id' => $data['role_id'],
      ]);
      DB::commit();
      ResponseData($allowance);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function storeSalarySetUp($data)
  {
    DB::beginTransaction();
    try {
      $salarySetup = SalarySetup::create([
        'basic_salary' => $data['basic_salary'],
        'role_id' => $data['role_id'],
      ]);

      if (isset($data['salary_allowances'])) {
        $salary_allowances = json_decode($data['salary_allowances'], true);
        foreach ($salary_allowances as $salary_allowance) {
          SalaryAllowance::create([
            'salary_setup_id' => $salarySetup->id,
            'allowance_id' => $salary_allowance['allowance_id'],
            'amount' => $salary_allowance['amount'],
          ]);
        }
      }

      $staffIds = Staff::whereHas('roles', function ($query) use ($data) {
        $query->where('id', $data['role_id']);
      })->pluck('id');
      if ($staffIds->isEmpty()) {
        ResponseMessage('No staff found for this role', 402);
      }
      foreach ($staffIds as $staffId) {
        Salary::create([
          'basic_salary' => $data['basic_salary'],
          'staff_id' => $staffId,
          'salary_setup_id' => $salarySetup->id,
          'created_by' => UserData()->id,
        ]);
      }
      DB::commit();
      ResponseData($salarySetup);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getSalarySetUp($request)
  {
    $data = SalarySetup::with(['salaryAllowances.allowance', 'role.department'])
      ->when($request->role_id, function ($query) use ($request) {
        return $query->where('role_id', $request->role_id);
      })
      ->when($request->department_id, function ($query) use ($request) {
        return $query->whereHas('role', function ($query) use ($request) {
          $query->where('department_id', $request->department_id);
        });
      })
      ->orderBy('id', 'desc')
      ->paginate(config('common.list_count'));
    $data->getCollection()->transform(function ($item) {
      $totalAllowance = (float) $item->salaryAllowances->sum('amount');
      $item->total_allowances_amount = $totalAllowance;
      $item->net_salary = (float) $item->basic_salary + $totalAllowance;
      return $item;
    });
    ResponseData($data);
  }

  public function getSalarySetUpById($id)
  {
    $data = SalarySetup::with(['salaryAllowances.allowance', 'role.department'])
      ->where('id', $id)
      ->first();
    if (!$data) {
      ResponseMessage('Salary setup not found.', 404);
    }
    // $totalAllowance = (float) $data->salaryAllowances->sum('amount');
    // $data->total_allowances_amount = $totalAllowance;
    // $data->net_salary = (float) $data->basic_salary + $totalAllowance;
    ResponseData($data);
  }

  public function updateSalarySetUp($data, $id)
  {
    DB::beginTransaction();
    try {
      $salarySetup = SalarySetup::find($id);
      if (!$salarySetup) {
        ResponseMessage('Salary setup not found.', 404);
      }

      if (isset($data['basic_salary']) && $salarySetup->basic_salary !== $data['basic_salary']) {
        $salarySetup->basic_salary = $data['basic_salary'];
      }

      if (isset($data['role_id']) && $salarySetup->role_id !== $data['role_id']) {
        $salarySetup->role_id = $data['role_id'];
      }
      $salarySetup->save();

      if (isset($data['salary_allowances'])) {
        $salary_allowances = json_decode($data['salary_allowances'], true);
        foreach ($salary_allowances as $salary_allowance) {
          SalaryAllowance::updateOrCreate(
            [
              'salary_setup_id' => $salarySetup->id,
              'allowance_id' => $salary_allowance['allowance_id'],
            ],
            [
              'amount' => $salary_allowance['amount'],
            ]
          );
        }
      }

      DB::commit();
      ResponseData($salarySetup);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function deleteSalaryAllowance($salaryAllowanceId)
  {
    DB::beginTransaction();
    try {
      $salaryAllowance = SalaryAllowance::find($salaryAllowanceId);
      if (!$salaryAllowance) {
        ResponseMessage('Salary allowance not found.', 404);
      }
      $salaryAllowance->delete();
      DB::commit();
      ResponseMessage('Salary allowance deleted successfully.', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }
}
