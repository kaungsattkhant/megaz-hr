<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Repositories\CustomerLevelDiscount\CustomerLevelDiscountRepositoryInterface;
use Illuminate\Http\Request;

class CustomerLevelDiscountAPIController extends Controller
{
    //
    protected $cldRepo;
    public function __construct(CustomerLevelDiscountRepositoryInterface $cldRepo)
    {
        $this->cldRepo = $cldRepo;
    }

    public function getCustomerLevelDiscountData(Request $request)
    {
        $cld = $this->cldRepo->listAllData($request);
    }

    public function createCustomerLevelDiscount(Request $request)
    {
        $cld = $this->cldRepo->createData($request->all());
    }

    public function updateCustomerLevelDiscount(Request $request, int $id)
    {
        $cld = $this->cldRepo->updateData($request->all(),$id);
    }

    public function deleteCustomerLevelDiscount(int $id)
    {
        $cld = $this->cldRepo->deleteData($id);
    }
}
