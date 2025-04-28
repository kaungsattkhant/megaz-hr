<?php

namespace App\Repositories\Salary;

use App\Models\Staff;
use App\Models\Salary;
use App\Models\CheckIn;
use App\Models\Overtime;
use App\Models\Allowance;
use App\Models\OvertimeFee;
use App\Models\SalaryBatch;
use App\Models\SalarySetup;
use Illuminate\Support\Carbon;
use App\Models\SalaryAllowance;
use App\Models\OvertimeCategory;
use App\Models\SalaryBatchStaff;
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
      $totalAllowance = 0;
      $totalDeduction = 0;
      foreach ($item->salaryAllowances as $salaryAllowance) {
        if ($salaryAllowance->allowance->type == 'allowance') {
          $totalAllowance += (float) $salaryAllowance->amount;
        } elseif ($salaryAllowance->allowance->type == 'deduction') {
          $totalDeduction += (float) $salaryAllowance->amount;
        }
      }
      $item->total_allowances_amount = $totalAllowance;
      $item->total_deductions_amount = $totalDeduction;
      $item->net_salary = (float) $item->basic_salary + $totalAllowance - $totalDeduction;
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

  public function getSalaries($request)
  {
    $data = Salary::with(['staff.department', 'salarySetup.role.department', 'salarySetup.salaryAllowances.allowance'])
      ->when($request->role_id, function ($query) use ($request) {
        return $query->whereHas('salarySetup', function ($query) use ($request) {
          $query->where('role_id', $request->role_id);
        });
      })
      ->when($request->department_id, function ($query) use ($request) {
        return $query->whereHas('salarySetup.role', function ($query) use ($request) {
          $query->where('department_id', $request->department_id);
        });
      })
      ->orderBy('id', 'desc')
      ->paginate(config('common.list_count'));

    $data->getCollection()->transform(function ($item) {
      $totalAllowance = 0;
      $totalDeduction = 0;
      foreach ($item->salarySetup->salaryAllowances as $salaryAllowance) {
        if ($salaryAllowance->allowance->type == 'allowance') {
          $totalAllowance += (float) $salaryAllowance->amount;
        } elseif ($salaryAllowance->allowance->type == 'deduction') {
          $totalDeduction += (float) $salaryAllowance->amount;
        }
      }
      $item->total_allowances_amount = $totalAllowance;
      $item->total_deductions_amount = $totalDeduction;
      $item->net_salary = (float) $item->basic_salary + $totalAllowance - $totalDeduction;
      return $item;
    });
    ResponseData($data);
  }

  public function updateBasicSalary($request, $id)
  {
    DB::beginTransaction();
    try {
      $salary = Salary::find($id);
      if (!$salary) {
        ResponseMessage('Salary not found.', 404);
      }
      $salary->basic_salary = $request['basic_salary'];
      $salary->save();
      DB::commit();
      ResponseData($salary);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }


  public function getOvertimeFee($request)
  {
    $data = OvertimeFee::with('role.department')
      // ->when($request->role_id, function ($query) use ($request) {
      //   return $query->where('role_id', $request->role_id);
      // })
      ->orderBy('id', 'desc')
      ->paginate(config('common.list_count'));

    ResponseData($data);
  }

  public function createOvertimeFee($data)
  {
    DB::beginTransaction();
    try {
      $overtimeFee = OvertimeFee::updateOrCreate(
        [
          'id' => $data['id'] ?? null,
        ],
        [
          'role_id' => $data['role_id'],
          'fee' => $data['fee'],
        ]
      );
      DB::commit();
      ResponseData($overtimeFee);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function createOvertimeCategories($data)
  {
    DB::beginTransaction();
    try {
      $overtimeCategory = OvertimeCategory::updateOrCreate(
        ['id' => $data['id'] ?? null],
        [
          'name' => $data['name']
        ]
      );
      DB::commit();
      ResponseData($overtimeCategory);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getOvertimeCategories($request)
  {
    $data = OvertimeCategory::orderBy('id', 'desc')
      ->paginate(config('common.list_count'));
    ResponseData($data);
  }

  public function createOvertime($data)
  {
    DB::beginTransaction();
    try {
      $overtime = Overtime::updateOrCreate(
        ['id' => $data['id'] ?? null],
        [
          'from_date' => $data['from_date'],
          'to_date' => $data['to_date'],
          'staff_id' => $data['staff_id'],
          'overtime_category_id' => $data['overtime_category_id'],
          'time_shift_id' => $data['time_shift_id'],
          'from_time' => $data['from_time'],
          'to_time' => $data['to_time'],
          'remark' => $data['remark'] ?? null,
          'status' => $data['status'],
          'created_by' => UserData()->id,
          'confirmed_at' => $data['confirmed_at'] ?? null,
          'confirmed_by' => $data['confirmed_by'] ?? null,
          'cancelled_at' => $data['cancelled_at'] ?? null,
          'cancelled_by' => $data['cancelled_by'] ?? null,
        ]
      );
      DB::commit();
      ResponseData($overtime);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getOvertimes($request)
  {
    $data = Overtime::with(['staff.department', 'overtimeCategory', 'timeShift.shift'])
      ->when($request->role_id, function ($query) use ($request) {
        return $query->whereHas('staff.roles', function ($query) use ($request) {
          $query->where('id', $request->role_id);
        });
      })
      ->when($request->department_id, function ($query) use ($request) {
        return $query->whereHas('staff.department', function ($query) use ($request) {
          $query->where('id', $request->department_id);
        });
      })
      ->orderBy('id', 'desc')
      ->paginate(config('common.list_count'));

    ResponseData($data);
  }

  public function setOvertimeApproval($data, $id)
  {
    DB::beginTransaction();
    try {
      $overtime = Overtime::find($id);
      if (!$overtime) {
        ResponseMessage('Overtime not found.', 404);
      }
      if ($overtime->status === 'confirmed') {
        ResponseMessage('Overtime already confirmed.', 409);
      }

      $status = $data['status'] ?? null;
      if (!$status) {
        ResponseMessage('Status is required.', 400);
      }
      if ($status === 'cancelled') {
        $overtime->status = 'cancelled';
        $overtime->cancelled_at = now();
        $overtime->cancelled_by = UserData()->id;
      }

      if ($status === 'confirmed') {
        $overtime->status = 'confirmed';
        $overtime->confirmed_at = now();
        $overtime->confirmed_by = UserData()->id;
      }

      $overtime->save();
      DB::commit();
      ResponseData($overtime);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getMobileOvertimesByStaffId($request, $staffId)
  {
    $data = Overtime::with(['overtimeCategory', 'timeShift.shift'])
      ->where('staff_id', $staffId)->orderBy('id', 'desc')
      ->paginate(config('common.list_count'));

    if ($data->isEmpty()) {
      ResponseMessage('Overtime not found.', 404);
    }
    ResponseData($data);
  }

  public function createSalaryBatch($data)
  {
    DB::beginTransaction();
    try {
      $salaryBatch = SalaryBatch::create([
        'name' => $data['name'],
        'day_of_monthly' => $data['day_of_monthly'],
        'created_by' => UserData()->id
      ]);

      if (isset($data['staff_ids'])) {
        $staff_ids = json_decode($data['staff_ids'], true);

        foreach ($staff_ids as $staffId) {
          $existingBatch = SalaryBatchStaff::where('staff_id', $staffId)->first();
          if ($existingBatch) {
            DB::rollback();
            ResponseMessage('Staff already exists in the batch.', 409);
          }
          SalaryBatchStaff::create([
            'salary_batch_id' => $salaryBatch->id,
            'staff_id' => $staffId,
          ]);
        }
      }

      DB::commit();
      ResponseData($salaryBatch);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function getSalaryBatch($request)
  {
    $data = SalaryBatch::with(['salaryBatchStaff.staff'])
      ->withCount('salaryBatchStaff')
      ->orderBy('id', 'desc')
      ->paginate(config('common.list_count'));

    ResponseData($data);
  }

  public function getSalaryBatchById($id)
  {
    $data = SalaryBatch::with(['salaryBatchStaff.staff.department', 'salaryBatchStaff.staff.roles'])
      ->where('id', $id)
      ->get();
    if ($data->isEmpty()) {
      ResponseMessage('Salary batch not found.', 404);
    }
    ResponseData($data);
  }

  public function updateSalaryBatch($data, $id)
  {
    DB::beginTransaction();
    try {
      $salaryBatch = SalaryBatch::findOrFail($id);
      if (!$salaryBatch) {
        ResponseMessage('Salary batch not found.', 404);
      }
      $salaryBatch->name = $data['name'];
      $salaryBatch->day_of_monthly = $data['day_of_monthly'];
      $salaryBatch->save();

      if (isset($data['salary_batch_staffs'])) {
        $salary_batch_staffs = json_decode($data['salary_batch_staffs'], true);

        foreach ($salary_batch_staffs as $salary_batch_staff) {
          $existingBatch = SalaryBatchStaff::where('staff_id', $salary_batch_staff['staff_id'])->first();
          if ($existingBatch) {
            DB::rollback();
            ResponseMessage('Staff already exists in the batch.', 409);
          }
          SalaryBatchStaff::updateOrCreate(
            [
              'id' => $salary_batch_staff['id'] ?? null,
              'salary_batch_id' => $salaryBatch->id,
            ],
            [
              'staff_id' => $salary_batch_staff['staff_id'],
            ]
          );
        }
      }

      DB::commit();
      ResponseData($salaryBatch);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function deleteSalaryBatch($id)
  {
    DB::beginTransaction();
    try {
      $salaryBatch = SalaryBatch::find($id);
      if (!$salaryBatch) {
        ResponseMessage('Salary batch not found.', 404);
      }
      $salaryBatch->salaryBatchStaff()->delete();
      $salaryBatch->delete();
      DB::commit();
      ResponseMessage('Salary batch deleted successfully.', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }

  public function deleteSalaryBatchStaff($id)
  {
    DB::beginTransaction();
    try {
      $salaryBatchStaff = SalaryBatchStaff::find($id);
      if (!$salaryBatchStaff) {
        ResponseMessage('Salary batch staff not found.', 404);
      }
      $salaryBatchStaff->delete();
      DB::commit();
      ResponseMessage('Salary batch staff deleted successfully.', 200);
    } catch (\Exception $e) {
      DB::rollback();
      ResponseMessage($e->getMessage(), 402);
      throw $e;
    }
  }


  public function calculateSalary($request)
  {
    $salaryBatchStaffs = SalaryBatchStaff::where('salary_batch_id', $request->salary_batch_id)
      ->with('staff.overtimes', 'staff.salary', 'staff.salary.salarySetup', 'staff.salary.salarySetup.salaryAllowances')
      ->get();

    if ($salaryBatchStaffs->isEmpty()) {
      ResponseMessage('No staff found in this batch.', 404);
    }

    $salaryDetails = [];
    foreach ($salaryBatchStaffs as $salaryBatchStaff) {
      $staff = $salaryBatchStaff->staff; //stafflist based on batch
      $salary = $staff->salary;   //basic salary
      if ($salary) {
        $totalAllowance = 0;
        $totalDeduction = 0;
        foreach ($salary->salarySetup->salaryAllowances as $salaryAllowance) {
          if ($salaryAllowance->allowance->type == 'allowance') {
            $totalAllowance += (float) $salaryAllowance->amount;
          } elseif ($salaryAllowance->allowance->type == 'deduction') {
            $totalDeduction += (float) $salaryAllowance->amount;
          }
        }

        $checkIns = CheckIn::where('staff_id', $staff->id)
          ->whereBetween('check_in_date_time', [$request->from_date, $request->to_date])
          ->whereBetween('check_out_date_time', [$request->from_date, $request->to_date])
          ->get();


        $totalWorkedHours = 0;
        $totalOvertimeHours = 0;
        //to get total worked hours from request from date to to date
        foreach ($checkIns as $checkIn) {
          $checkInTime = Carbon::parse($checkIn->check_in_date_time);
          $checkOutTime = Carbon::parse($checkIn->check_out_date_time);
          if ($checkOutTime->gt($checkInTime)) {

            $workedHours =  $checkInTime->diffInHours($checkOutTime);
            $totalWorkedHours += (float) $workedHours;
          }
        }

        $overtimes = Overtime::where('staff_id', $staff->id)
          ->whereBetween('from_date', [$request->from_date, $request->to_date])
          ->whereBetween('to_date', [$request->from_date, $request->to_date])
          ->get();

        foreach ($overtimes as $overtime) {
          $actualOvertimes = CheckIn::where('staff_id', $overtime->staff_id)
            ->where('time_shift_id', $overtime->time_shift_id)
            ->get();
          foreach ($actualOvertimes as $actualOvertime) {
            $overtimeCheckIn = Carbon::parse($actualOvertime->check_in_date_time);
            $overtimeCheckOut = Carbon::parse($actualOvertime->check_out_date_time);
            if ($overtimeCheckOut->gt($overtimeCheckIn)) {
              // Calculate the overtime worked hours
              $overtimeWorkedHours = $overtimeCheckIn->diffInHours($overtimeCheckOut);
              $totalOvertimeHours += (float)$overtimeWorkedHours;
            }
          }
        }
        $totalOvertimeHours = max(0, $totalOvertimeHours);
        $actualWorkedHours = $totalWorkedHours - $totalOvertimeHours;
        $hourlyRate = $salary->basic_salary / $actualWorkedHours;
        $role = $staff->roles->first();
        $overtimePay = 0;
        if ($role) {
          $overtimeFee = OvertimeFee::where('role_id', $role->id)->first();
          $overtimePay = $overtimeFee ? $overtimeFee->fee * $hourlyRate * $totalOvertimeHours : 0;
        } else {
          $overtimePay = 0;
        }
        $netSalary = (float) ($salary->basic_salary - $totalDeduction) + ($totalAllowance + $overtimePay);
        $salaryDetails[] = [
          'staff_id' => $staff->id,
          'staff_name' => $staff->name,
          'department_id' => $staff->department->id,
          'department_name' => $staff->department->name,
          'role_id' => $staff->roles->first() ? $staff->roles->first()->id : null,
          'role_name' => $staff->roles->first() ? $staff->roles->first()->name : null,
          'salary_batch_id' => $request->salary_batch_id,
          'salary_id' => $salary->id,
          'basic_salary' =>  $salary->basic_salary,
          'allowance' => $totalAllowance,
          'deductions' => $totalDeduction,
          'overtime_hours' => max(0, $totalOvertimeHours),
          'overtime_pay' => max(0, $overtimePay),
          'netSalary' => $netSalary
        ];
      }
    }
    return ResponseData($salaryDetails);
  }
}
