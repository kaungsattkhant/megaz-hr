<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\CustomerCreateRequest;
use App\Http\Requests\Customer\CustomerUpdateRequest;
use App\Repositories\Customer\CustomerRepositoryInterface;
use Illuminate\Http\Request;

class CustomerAPIController extends Controller
{
    //
    protected $customerRepo;
    public function __construct(CustomerRepositoryInterface $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    public function getCustomerData(Request $request)
    {
        $customers = $this->customerRepo->listAllData($request);
        ResponseData($customers);
    }

    public function createCustomer(CustomerCreateRequest $request)
    {
        $customer = $this->customerRepo->createData($request->all());
        ResponseData($customer);
    }

    public function updateCustomer(CustomerUpdateRequest $request, int $id)
    {
        $customer = $this->customerRepo->updateData($request->all(),$id);
        ResponseData($customer);
    }

    public function deleteCustomer(int $id)
    {
        $customer = $this->customerRepo->deleteData($id);
        if($customer==true)
        {
            ResponseMessage('Customer deleted');
        }else{
            ResponseMessage('Customer not found or some error occur');
        }
    }

    public function listOfCustomer(Request $request){
        $customers = $this->customerRepo->listOfCustomer($request);
        ResponseData($customers);
    }
}
