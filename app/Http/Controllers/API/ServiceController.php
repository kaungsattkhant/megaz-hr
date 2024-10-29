<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Repositories\Service\ServiceInterface;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    //
    private $serviceRepo;
    public function __construct(ServiceInterface $repo){
        $this->serviceRepo=$repo;
    }
    public function index(Request $request){
        $data=$this->serviceRepo->list($request);
        ResponseData($data);
    }

    public function store(Request $request){
        $data=$this->serviceRepo->updateOrCreate($request);
        ResponseData($data);
    }

    public function show(Service $service){
        $data=$this->serviceRepo->detail($service);
        ResponseData($data);
    }

    //pos 

    public function getService(Request $request){
        $data=$this->serviceRepo->getService($request);
        ResponseData($data);
    }

    

}
