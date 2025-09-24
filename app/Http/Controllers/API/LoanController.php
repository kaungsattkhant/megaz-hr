<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Loan\LoanRequest;
use App\Repositories\Loan\LoanRepositoryInterface;

class LoanController extends Controller
{

    private LoanRepositoryInterface $loanRepository;
    public function __construct(LoanRepositoryInterface $loanRepository)
    {
        $this->loanRepository = $loanRepository;
    }
    public function storeLoanCreditorAccount(Request $request)
    {
        $data = $this->loanRepository->storeLoanCreditorAccount($request->all());
        ResponseData($data);
    }

    public function getLoanCreditorAccount(Request $request)
    {
        $data = $this->loanRepository->getLoanCreditorAccount($request);
        ResponseData($data);
    }

    public function getInterestOnLoanCreditorAccount(Request $request)
    {
        $data = $this->loanRepository->getInterestOnLoanCreditorAccount($request);
        ResponseData($data);
    }

    public function storeLoan(LoanRequest $request)
    {
        $data = $this->loanRepository->storeLoan($request->validated());
        ResponseData($data);
    }

    public function getLoans(Request $request)
    {
        $data = $this->loanRepository->getLoans($request);
        ResponseData($data);
    }

    public function getLoanByAccountId($loanAccountId)
    {
        $data = $this->loanRepository->getLoanByAccountId($loanAccountId);
        ResponseData($data);
    }
}
