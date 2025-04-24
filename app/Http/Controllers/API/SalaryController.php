<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Salary\SalaryRepositoryInterface;
use Illuminate\Http\Request;

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
}
