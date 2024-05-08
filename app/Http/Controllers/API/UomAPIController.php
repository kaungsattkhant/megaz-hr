<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Uom\UomCreateRequest;
use App\Http\Requests\Uom\UomUpdateRequest;
use App\Repositories\Uom\UomRepositoryInterface;
use Illuminate\Http\Request;

class UomAPIController extends Controller
{
    //
    protected $uomRepo;
    public function __construct(UomRepositoryInterface $uomRepo)
    {
        $this->uomRepo = $uomRepo;
    }

    public function getUomData(Request $request)
    {
        $uoms = $this->uomRepo->listAllData($request);
        ResponseData($uoms);
    }

    public function createUom(UomCreateRequest $request)
    {
        $uom =  $this->uomRepo->createUom($request->all());
        ResponseData($uom);
    }

    public function updateUom(UomUpdateRequest $request,int $id)
    {
        $uom =  $this->uomRepo->updateData($request->all(),$id);
        ResponseData($uom);
    }

    public function deleteUom(int $id)
    {
        $uom = $this->uomRepo->deleteData($id);
        if($uom==true)
        {
            ResponseMessage('Uom deleted');
        }else{
            ResponseMessage('Uom not found or some error occur');
        }

    }

    // uom conversion

    public function createUomConversion(Request $request)
    {
        $uom = $this->uomRepo->createUomConversion($request->all());
        ResponseData($uom );
    }

    public function updateUomConversion(Request $request,int $id)
    {
        $uom = $this->uomRepo->updateUomConversion($request->all(),$id);
        ResponseData($uom);
    }


    public function getUomConversionList(Request $request)
    {
        $uomConversions = $this->uomRepo->uomConversaionList($request);
        ResponseData($uomConversions);
    }


}
