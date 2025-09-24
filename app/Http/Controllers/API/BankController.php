<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Bank\BankRepositoryInterface;

class BankController extends Controller {
    protected $bankRepository;

    public function __construct(BankRepositoryInterface $bankRepository)
    {
        $this->bankRepository = $bankRepository;
    }

    public function getAllBanks()
    {
        $banks = $this->bankRepository->getAllBanks();
        ResponseData($banks);
    }

    public function createBank(Request $request)
    {
        $bank = $this->bankRepository->createBank($request->all());
        ResponseData($bank);
    }
}
