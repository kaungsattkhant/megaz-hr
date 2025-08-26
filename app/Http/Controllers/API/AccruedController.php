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
        $data =  $this->accruedRepository->getExpenseAccount($request);
        ResponseData($data);
    }
    public function createAccrued(AccruedRequest $request)
    {
        $data = $this->accruedRepository->createAccrued($request->all());
        ResponseData($data);
    }
    public function getAccrued(Request $request)
    {
        $data = $this->accruedRepository->getAccrued($request);
        return $data;
    }

    public function detailAccrued($accountId)
    {
        $data = $this->accruedRepository->detailAccrued($accountId);
        ResponseData($data);
    }
}
