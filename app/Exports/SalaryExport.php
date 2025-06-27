<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalaryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $calculatedSalaryData;

    public function __construct(array $calculatedSalaryData)
    {
        $this->calculatedSalaryData = $calculatedSalaryData;
    }
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->calculatedSalaryData);
    }

    public function headings(): array
    {
        return [
            // 'Staff ID',
            'Staff Name',
            // 'Department ID',
            'Department Name',
            // 'Role ID',
            'Role Name',
            'Salary Batch Name',
            // 'Salary ID',
            'Allowance',
            'Deductions',
            'Overtime Hours',
            'Overtime Pay',
            'Basic Salary',
            'Net Salary',
        ];
    }

    public function map($row): array
    {
        return [
            // $row['staff_id'],
            $row['staff_name'],
            // $row['department_id'],
            $row['department_name'],
            // $row['role_id'],
            $row['role_name'],
            $row['salary_batch_name'],
            // $row['salary_id'],
            $row['allowance'],
            $row['deductions'],
            $row['overtime_hours'],
            $row['overtime_pay'],
            $row['basic_salary'],
            $row['netSalary'],
        ];
    }
}
