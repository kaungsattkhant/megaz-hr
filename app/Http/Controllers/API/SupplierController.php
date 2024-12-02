<?php

namespace App\Http\Controllers\API;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Supplier\SupplierInterface;

class SupplierController extends Controller
{

    private SupplierInterface $supplierRepo;

    public function __construct(SupplierInterface $supplier_repo)
    {
        $this->supplierRepo = $supplier_repo;
    }

    public function index(Request $request){
        $suppliers = $this->supplierRepo->list($request);
        ResponseData($suppliers);
    }

    public function store(Request $request){
        $supplier = $this->supplierRepo->updateOrCreate($request);
        ResponseData($supplier);
    }

    public function show(Supplier $supplier){
        $supplier = $this->supplierRepo->detail($supplier);
        ResponseData($supplier);
    }

    public function destroy($id){
    }

    public function createSupplierAccount(Request $request){
        $createdSupplierAccount = $this->supplierRepo->createSupplierAccount($request);
        return $createdSupplierAccount;
    }
}
