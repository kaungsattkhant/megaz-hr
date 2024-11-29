<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\Creditor\CreditorInterface;
use Illuminate\Http\Request;

class CreditorController extends Controller
{
    //
    private $creditorRepo;
    public function __construct(CreditorInterface $repo){
        $this->creditorRepo=$repo;
    }

    public function index(Request $request){
        $data = $this->creditorRepo->list($request);
        ResponseData($data);
    }


    public function getCreditorAccountList(){
        $data=$this->creditorRepo->getCreditorAccountList();
        ResponseData($data);
    }

    public function createCreditorAccount(Request $request){
        $data=$this->creditorRepo->createCreditorAccount($request);
        ResponseData($data);
    }
}
