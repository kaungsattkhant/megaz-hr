<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Accrued\AccruedRequest;
use App\Repositories\Accrued\AccruedRepositoryInterface;

class AccruedController extends Controller
{
    public $accruedRepository;
    public function __construct(AccruedRepositoryInterface $accruedRepository){
        $this->accruedRepository = $accruedRepository;
    }
    public function getExpenseAccount(Request $request)
    {
        return $this->accruedRepository->getExpenseAccount($request);
    }
    public function createAccrued(AccruedRequest $request)
    {
        return $this->accruedRepository->createAccrued($request->all());
    }
    public function getAccrued(Request $request)
    {
        return $this->accruedRepository->getAccrued($request);
    }

    public function detailAccrued($accountId)
    {
        return $this->accruedRepository->detailAccrued($accountId);
    }
}
