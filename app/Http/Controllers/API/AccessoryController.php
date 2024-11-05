<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AccessoryCreateRequest;
use App\Models\Accessory;
use App\Models\AccessoryCategory;
use App\Repositories\Accessory\AccessoryInterface;
use Illuminate\Http\Request;

class AccessoryController extends Controller
{
    //
    private $accessoryRepo;
    public function __construct(AccessoryInterface $repo) {
        $this->accessoryRepo=$repo;
    }
    public function index(Request $request){
        $data=$this->accessoryRepo->list($request);
        ResponseData($data);
    }

    public function store(AccessoryCreateRequest $request){
        $data=$this->accessoryRepo->store($request);
        ResponseData($data);
    }

    public function show(Accessory $accessory){
        $data=$this->accessoryRepo->detail($accessory);
        ResponseData($data);
    }

    public function getAccessoryCategory(){
        $accessoryCategories=AccessoryCategory::where('is_active',1)->get();
        ResponseData($accessoryCategories);
    }

     public function deletAccessoryItem($id): void{
        $this->accessoryRepo->deletAccessoryItem($id);
     }

     public function getAccessoryByCategory($accessory_category_id){
        // dd($accessory_category_id);
        $data=$this->accessoryRepo->getAccessoryByCategory($accessory_category_id);
        ResponseData($data);
     }

     public function createInvoiceAccessory(Request $request){
        $data=$this->accessoryRepo->createInvoiceAccessory($request);
        ResponseData($data);
     }

}
