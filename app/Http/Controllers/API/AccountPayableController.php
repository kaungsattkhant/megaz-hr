<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\AccountPayable\AccountPayableInterface;
use Illuminate\Http\Request;

class AccountPayableController extends Controller
{
    //
    private $accountPayableRepo;

    public function __construct(AccountPayableInterface $repo)
    {
        $this->accountPayableRepo = $repo;
    }

    public function index(Request $request){
        $data = $this->accountPayableRepo->list($request);
        ResponseData($data);
    }

    public function getPayableAccount(){
        $data= $this->accountPayableRepo->getPayableAccount();
        ResponseData($data);
    }

    public function createPayableAccount(Request $request){
        $data= $this->accountPayableRepo->createPayableAccount($request);
        ResponseData($data);
    }

    public function createPayableTransaction(Request $request){
        $data= $this->accountPayableRepo->createPayableTransaction($request);
        ResponseData($data);
    }

    public function listOfAccountPayableTransaction(Request $request){
        $data= $this->accountPayableRepo->listOfAccountPayableTransaction($request);
        ResponseData($data);
    }
}
