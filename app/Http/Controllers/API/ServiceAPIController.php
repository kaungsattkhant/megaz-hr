<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Service\ServiceCreateRequest;
use App\Http\Requests\Service\ServiceUpdateRequest;
use App\Repositories\Service\ServiceRepositoryInterface;
use Illuminate\Http\Request;

class ServiceAPIController extends Controller
{
    //
    protected $serviceRepo;
    public function __construct(ServiceRepositoryInterface $serviceRepo)
    {
        $this->serviceRepo = $serviceRepo;
    }
    public function getServiceData(Request $request)
    {
        $services = $this->serviceRepo->listAllData($request);
        ResponseData($services);
    }


    public function createService(ServiceCreateRequest $request)
    {
        $service = $this->serviceRepo->createData($request->all());
        ResponseData($service);
    }

    public function updateService(ServiceUpdateRequest $request, $id)
    {
        $service = $this->serviceRepo->updateData($request->all(),$id);
        ResponseData($service);

    }

    public function deleteService($id)
    {
        $service = $this->serviceRepo->deleteData($id);
        if($service==true)
        {
            ResponseMessage('Service deleted');
        }else{
            ResponseMessage('Service not found or some error occur');
        }
    }



}
