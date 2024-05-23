<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\UomConversion;
use App\Http\Controllers\Controller;
use App\Http\Requests\Uom\UomCreateRequest;
use App\Http\Requests\Uom\UomUpdateRequest;
use App\Repositories\Uom\UomRepositoryInterface;

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


     #test
     public function getUomConversionByUom(Request $request){
        // $request['base_uom_id']=6;
        // $request['item_uom_id']=7;
        // $request['item_price']=30000;
        // $request['quantity']=3;
        if($request->po_uom_id==$request->item_uom_id){
            $uom_conversion=UomConversion::where('base_unit_id',$request->po_uom_id)
            ->where('conversion_unit_id',$request->base_uom_id)
            ->first();
            // $request->item_price*$request->quantity/$uom_conversion->conversion;
        }
        if($request->po_uom_id==$request->base_uom_id){
            $uom_conversion=UomConversion::where('base_unit_id',$request->item_uom_id)
            ->where('conversion_unit_id',$request->po_uom_id)
            ->first();
        }
        if($uom_conversion){
            $priceByItem=$request->item_price/$uom_conversion->conversion;
            $uom_conversion->price=$priceByItem;
            ResponseData($uom_conversion);
        }
        ResponseMessage('Uom conversion is required');
    }
    #end

}
