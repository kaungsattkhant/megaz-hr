<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Hr\SalaryBatchRequest;
use App\Repositories\Salary\SalaryRepositoryInterface;

class SalaryController extends Controller
{
    private SalaryRepositoryInterface $salaryRepository;
    public function __construct(SalaryRepositoryInterface $salaryRepository)
    {
        $this->salaryRepository = $salaryRepository;
    }

    public function getAllowances(Request $request)
    {
        $data = $this->salaryRepository->getAllowances($request);
        ResponseData($data);
    }

    public function createAllowance(Request $request)
    {
        $data = $this->salaryRepository->createAllowance($request->all());
        ResponseData($data);
    }
    public function storeSalarySetUp(Request $request)
    {
        $data = $this->salaryRepository->storeSalarySetUp($request->all());
        ResponseData($data);
    }
    public function getSalarySetUp(Request $request)
    {
        $data = $this->salaryRepository->getSalarySetUp($request);
        ResponseData($data);
    }
    public function getSalarySetUpById($id)
    {
        $data = $this->salaryRepository->getSalarySetUpById($id);
        ResponseData($data);
    }

    public function updateSalarySetUp(Request $request, $id)
    {
        $data = $this->salaryRepository->updateSalarySetUp($request->all(), $id);
        ResponseData($data);
    }

    public function deleteSalaryAllowance($salaryAllowanceId)
    {
        $data = $this->salaryRepository->deleteSalaryAllowance($salaryAllowanceId);
        ResponseData($data);
    }

    public function getSalaries(Request $request)
    {
        $data = $this->salaryRepository->getSalaries($request);
        ResponseData($data);
    }
    public function updateBasicSalary(Request $request, $id)
    {
        $data = $this->salaryRepository->updateBasicSalary($request->all(), $id);
        ResponseData($data);
    }

    public function createOvertimeFee(Request $request)
    {
        $data = $this->salaryRepository->createOvertimeFee($request->all());
        ResponseData($data);
    }

    public function getOvertimeFee(Request $request)
    {
        $data = $this->salaryRepository->getOvertimeFee($request);
        ResponseData($data);
    }

    public function deleteOvertimeFee($id)
    {
        $data = $this->salaryRepository->deleteOvertimeFee($id);
        ResponseData($data);
    }

    public function createOvertimeCategories(Request $request)
    {
        $data = $this->salaryRepository->createOvertimeCategories($request->all());
        ResponseData($data);
    }

    public function getOvertimeCategories(Request $request)
    {
        $data = $this->salaryRepository->getOvertimeCategories($request);
        ResponseData($data);
    }

    public function createOvertime(Request $request)
    {
        $data = $this->salaryRepository->createOvertime($request->all());
        ResponseData($data);
    }

    public function getOvertimes(Request $request)
    {
        $data = $this->salaryRepository->getOvertimes($request);
        ResponseData($data);
    }

    public function setOvertimeApproval(Request $request, $id)
    {
        $data = $this->salaryRepository->setOvertimeApproval($request->all(), $id);
        ResponseData($data);
    }

    public function getMobileOvertimesByStaffId(Request $request, $staffId)
    {
        $data = $this->salaryRepository->getMobileOvertimesByStaffId($request, $staffId);
        ResponseData($data);
    }

    public function createSalaryBatch(SalaryBatchRequest $request)
    {
        $data = $this->salaryRepository->createSalaryBatch($request->validated());
        ResponseData($data);
    }

    public function getSalaryBatch(Request $request)
    {
        $data = $this->salaryRepository->getSalaryBatch($request);
        ResponseData($data);
    }

    public function getSalaryBatchById($id)
    {
        $data = $this->salaryRepository->getSalaryBatchById($id);
        ResponseData($data);
    }

    public function updateSalaryBatch(Request $request, $id)
    {
        $data = $this->salaryRepository->updateSalaryBatch($request->all(), $id);
        ResponseData($data);
    }

    public function deleteSalaryBatch($id)
    {
        $data = $this->salaryRepository->deleteSalaryBatch($id);
        ResponseData($data);
    }

    public function deleteSalaryBatchStaff($id)
    {
        $data = $this->salaryRepository->deleteSalaryBatchStaff($id);
        ResponseData($data);
    }

    public function calculateSalary(Request $request)
    {
        $data = $this->salaryRepository->calculateSalary($request);
        ResponseData($data);
    }
    public function getAllowanceTypes(Request $request)
    {
        $data = $this->salaryRepository->getAllowanceTypes($request);
        ResponseData($data);
    }

    public function createPaySlip(Request $request)
    {
        $data = $this->salaryRepository->createPaySlip($request->all());
        ResponseData($data);
    }
    public function getPaySlips(Request $request)
    {
        $data = $this->salaryRepository->getPaySlips($request);
        ResponseData($data);
    }

    public function deletePaySlip($id)
    {
        $data = $this->salaryRepository->deletePaySlip($id);
        ResponseData($data);
    }
}
